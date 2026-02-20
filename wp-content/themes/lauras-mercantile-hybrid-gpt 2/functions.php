<?php
if (!defined('ABSPATH')) exit;

require_once get_stylesheet_directory() . '/inc/class-lm-walker-nav.php';

/**
 * Global kill switch: set in wp-config.php
 * define('LM_REACT_DISABLED', true);
 */
function lm_react_disabled(): bool {
  return defined('LM_REACT_DISABLED') && LM_REACT_DISABLED;
}

/** Critical WooCommerce paths that should always be PHP-rendered. */
function lm_is_woo_protected_path(): bool {
  if (!function_exists('is_woocommerce')) return false;
  return (is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url());
}

/** Determine whether to mount the app on this request. */
function lm_should_mount_app(): bool {
  if (lm_react_disabled()) return false;
  if (lm_is_woo_protected_path()) return false;

  if (is_front_page()) return true;
  if (is_page_template('page-react.php')) return true;

  // Mount React on story pages too
  $path = $_SERVER['REQUEST_URI'];
  if (strpos($path, '/meet-laura') !== false || 
      strpos($path, '/about-laura') !== false ||
      strpos($path, '/lauras-story-from-lauras-lean-beef-to-full-spectrum-cbd') !== false) {
    return true;
  }

  return false;
}

/** 
 * Helper to check if current request should render the React app.
 * Used in index.php to toggle classes and root divs.
 */
function lm_is_react_page(): bool {
  return lm_should_mount_app();
}

function lm_enqueue_base_styles() {
  $base_css_path = get_stylesheet_directory() . '/assets/base.css';
  if (file_exists($base_css_path)) {
    wp_enqueue_style('lm-base', get_stylesheet_directory_uri() . '/assets/base.css', [], filemtime($base_css_path));
  }
}
add_action('wp_enqueue_scripts', 'lm_enqueue_base_styles', 5);

function lm_enqueue_app_assets() {
  if (!lm_should_mount_app()) return;

  $theme_uri  = get_stylesheet_directory_uri();
  $theme_path = get_stylesheet_directory();

  // Runtime config available to the JS app
  $account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/');
  $cart_url    = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
  $checkout_url= function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/checkout/');
  $shop_url    = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');

  $data = [
    'siteUrl' => home_url('/'),
    'restUrl' => esc_url_raw(rest_url()),
    'nonce'   => wp_create_nonce('wp_rest'),
    'loggedIn'=> is_user_logged_in(),
    'accountUrl' => $account_url,
    'logoutUrl'  => wp_logout_url(home_url('/')),
    'cartUrl'    => $cart_url,
    'checkoutUrl'=> $checkout_url,
    'shopUrl'    => $shop_url,
    'assetBase'  => $theme_uri . '/assets/',
  ];

  // Prefer Vite manifest build if present; otherwise fall back to bundled app.js/app.css (uses wp.element).
  $manifest_path = $theme_path . '/assets/dist/.vite/manifest.json';
  if (!file_exists($manifest_path)) {
    $manifest_path = $theme_path . '/assets/dist/manifest.json';
  }
  if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (is_array($manifest)) {
      $entry = $manifest['react-src/main.jsx'] ?? $manifest['main.jsx'] ?? null;
      if ($entry && !empty($entry['file'])) {
        wp_enqueue_script('lm-app', $theme_uri . '/assets/dist/' . $entry['file'], [], null, true);
        if (!empty($entry['css']) && is_array($entry['css'])) {
          foreach ($entry['css'] as $css_file) {
            wp_enqueue_style('lm-app-' . md5($css_file), $theme_uri . '/assets/dist/' . $css_file, [], null);
          }
        }
        wp_add_inline_script('lm-app', 'window.__LM__=' . wp_json_encode($data) . ';', 'before');
        return;
      }
    }
  }

  // Fallback: ship a working React app using WordPress' bundled React (wp-element).
  $css_path = $theme_path . '/assets/dist/app.css';
  if (file_exists($css_path)) {
    wp_enqueue_style('lm-app-fallback', $theme_uri . '/assets/dist/app.css', ['lm-base'], filemtime($css_path));
  }

  $js_path = $theme_path . '/assets/dist/app.js';
  if (file_exists($js_path)) {
    wp_enqueue_script('lm-app-fallback', $theme_uri . '/assets/dist/app.js', ['wp-element'], filemtime($js_path), true);
    wp_add_inline_script('lm-app-fallback', 'window.__LM__=' . wp_json_encode($data) . ';', 'before');
  }
}
add_action('wp_enqueue_scripts', 'lm_enqueue_app_assets', 20);

function lm_theme_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('woocommerce');
  register_nav_menus([
    'primary' => __('Primary Menu', 'lauras-hybrid'),
    'top_bar' => __('Top Bar Menu', 'lauras-hybrid'),
    'footer'  => __('Footer Menu', 'lauras-hybrid'),
  ]);
}
add_action('after_setup_theme', 'lm_theme_setup');

// Remove WooCommerce sidebar on shop and product archive pages
// Completely remove WooCommerce sidebar on shop and product-related pages
add_action('wp', function () {
    if (function_exists('is_woocommerce') && (is_woocommerce() || is_shop() || is_product_category() || is_product_tag())) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
        add_filter('is_active_sidebar', '__return_false');
    }
});

/**
 * Force specific products to the top of the shop and category pages.
 */
add_filter('posts_orderby', function($orderby, $query) {
    if (is_admin()) return $orderby;
    
    // Robust detection for product queries (Archives, Store API, REST)
    $is_product_query = false;
    $pt = $query->get('post_type');
    
    if ($pt === 'product' || (is_array($pt) && in_array('product', $pt))) {
        $is_product_query = true;
    }
    
    // Explicit check for shop-related globals
    if (function_exists('is_shop') && (is_shop() || is_product_category() || is_product_tag() || is_post_type_archive('product'))) {
        $is_product_query = true;
    }
    
    // Check if we are in a WooCommerce Store API/REST request for products
    if (defined('REST_REQUEST') && REST_REQUEST) {
        $path = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($path, '/wc/store/v1/products') !== false || strpos($path, '/wc/store/products') !== false) {
            $is_product_query = true;
        }
    }

    if (!$is_product_query) return $orderby;
    if ($query->get('s')) return $orderby;

    global $wpdb;
    $mushrooms_slug = 'functional-mushrooms';
    $cbd_slug       = 'cbd-products-and-bundles';
    $tippens_slug   = 'joe-tippens-protocol-products';
    $turmeric_ids = [166466, 139017, 166471, 166473, 163552, 165372];
    $turmeric_ids_str = implode(',', $turmeric_ids);

    $priority_sql = " (
        CASE 
            WHEN (
                SELECT COUNT(*)
                FROM {$wpdb->term_relationships} tr_m
                INNER JOIN {$wpdb->term_taxonomy} tt_m ON tr_m.term_taxonomy_id = tt_m.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t_m ON tt_m.term_id = t_m.term_id
                WHERE tr_m.object_id = {$wpdb->posts}.ID
                  AND tt_m.taxonomy = 'product_cat'
                  AND t_m.slug = '$mushrooms_slug'
            ) > 0 THEN 10
            WHEN (
                SELECT COUNT(*)
                FROM {$wpdb->term_relationships} tr_t
                INNER JOIN {$wpdb->term_taxonomy} tt_t ON tr_t.term_taxonomy_id = tt_t.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t_t ON tt_t.term_id = t_t.term_id
                WHERE tr_t.object_id = {$wpdb->posts}.ID
                  AND tt_t.taxonomy = 'product_cat'
                  AND t_t.slug = '$tippens_slug'
            ) > 0 THEN 15
            WHEN (
                SELECT COUNT(*)
                FROM {$wpdb->term_relationships} tr_cbd
                INNER JOIN {$wpdb->term_taxonomy} tt_cbd ON tr_cbd.term_taxonomy_id = tt_cbd.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t_cbd ON tt_cbd.term_id = t_cbd.term_id
                WHERE tr_cbd.object_id = {$wpdb->posts}.ID
                  AND tt_cbd.taxonomy = 'product_cat'
                  AND t_cbd.slug = '$cbd_slug'
            ) > 0 THEN 20
            WHEN {$wpdb->posts}.post_title LIKE '%Turmeric%' THEN 30
            WHEN {$wpdb->posts}.post_title LIKE '%Curcumin%' THEN 30
            WHEN {$wpdb->posts}.ID IN ($turmeric_ids_str) THEN 30
            ELSE 40
        END
    ) ASC ";

    if (empty($orderby)) return $priority_sql;
    return $priority_sql . ", " . $orderby;
}, 99999, 2);

/**
 * Force WooCommerce sorting logic to be registered late via init hook.
 */
add_action('init', function() {
    /**
     * Set the default shop sorting to "menu_order" (Default sorting) 
     */
    add_filter('woocommerce_default_catalog_orderby', function($orderby) {
        return 'menu_order';
    }, 999999);

    /**
     * Ensure 'Default sorting' (menu_order) is available in the sorting dropdown.
     */
    add_filter('woocommerce_catalog_orderby', function($sortby) {
        // Debugging: rename popularity to see if this filter is active
        if (isset($sortby['popularity'])) {
            $sortby['popularity'] = 'Sort by Popularity (Active Filter)';
        }
        
        // Force menu_order to be the very first item
        $sortby = array('menu_order' => 'Default sorting') + $sortby;
        
        return $sortby;
    }, 999999);

    /**
     * Forcibly set the ordering args for the catalog.
     */
    add_filter('woocommerce_get_catalog_ordering_args', function($args) {
        if (!isset($_GET['orderby'])) {
            $args['orderby'] = 'menu_order';
            $args['order']   = 'ASC';
        }
        return $args;
    }, 999999);
}, 9999);

/**
 * Filter for WooCommerce Store API (used by blocks and React app)
 */
add_filter('woocommerce_store_api_products_query', function($query_args) {
    if (!isset($_GET['orderby'])) {
        $query_args['orderby'] = 'menu_order';
        $query_args['order']   = 'ASC';
    }
    return $query_args;
}, 999999);

/**
 * Forcibly set the shop sorting to menu_order if no explicit sorting is requested by the user.
 */
add_action('pre_get_posts', function($query) {
    if (is_admin() || !$query->is_main_query()) return;
    
    // Robust detection for product queries
    $is_product_query = false;
    $pt = $query->get('post_type');
    
    if ($pt === 'product' || (is_array($pt) && in_array('product', $pt))) {
        $is_product_query = true;
    }
    
    if (function_exists('is_shop') && (is_shop() || is_product_category() || is_product_tag() || is_post_type_archive('product'))) {
        $is_product_query = true;
    }
    
    // Check for Store API requests (though they usually don't hit pre_get_posts main query)
    if (defined('REST_REQUEST') && REST_REQUEST) {
        $path = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($path, '/wc/store/v1/products') !== false || strpos($path, '/wc/store/products') !== false) {
            $is_product_query = true;
        }
    }

    if ($is_product_query) {
        if (!isset($_GET['orderby'])) {
            $query->set('orderby', 'menu_order');
            $query->set('order', 'ASC');
        }
    }
}, 999999);

/**
 * Remove pagination from the shop and category pages to show all products at once.
 */
add_filter('loop_shop_per_page', function($cols) {
    if (is_admin()) return $cols;
    return -1; // -1 shows all products
}, 9999);

/**
 * Debug: Verify active theme in footer.
 */
add_action('wp_footer', function() {
    echo "<!-- ACTIVE_THEME_SLUG: " . esc_html(get_stylesheet()) . " -->";
    echo "<!-- FILTERS_REGISTERED_CHECK: " . (has_filter('woocommerce_catalog_orderby') ? 'YES' : 'NO') . " -->";
}, 999999);

/**
 * URL Normalization Map
 * Ensures common variations of links point to their canonical long-form counterparts.
 */
function lm_normalize_menu_urls($url) {
    if (empty($url)) return $url;
    
    $map = [
        'military' => home_url('/cbd-military-veteran-discount-program/'),
        'military-discount' => home_url('/cbd-military-veteran-discount-program/'),
        'loyalty' => home_url('/becoming-a-friend-of-laura-a-loyalty-program/'),
        'loyalty-program' => home_url('/becoming-a-friend-of-laura-a-loyalty-program/'),
    ];

    foreach ($map as $key => $target) {
        if (strpos($url, '/'.$key.'/') !== false || substr($url, -strlen($key)-1) === '/'.$key) {
            return $target;
        }
    }
    return $url;
}
add_filter('nav_menu_link_attributes', function($atts) {
    if (isset($atts['href'])) {
        $atts['href'] = lm_normalize_menu_urls($atts['href']);
    }
    return $atts;
}, 10, 1);


/**
 * Homepage-only: editorialize the Outcomes section.
 */
function lm_enqueue_home_outcomes_editorial() {
    if (!is_front_page() && !is_home()) return;
    // Note: this depends on lm_should_mount_app() logic which should already be in this file.
    if (function_exists('lm_should_mount_app') && !lm_should_mount_app()) return;

    $theme_path = get_stylesheet_directory();
    $theme_uri  = get_stylesheet_directory_uri();
    $js_path = $theme_path . '/assets/js/home-outcomes-editorial.js';
    if (file_exists($js_path)) {
        wp_enqueue_script(
            'lm-home-outcomes-editorial',
            $theme_uri . '/assets/js/home-outcomes-editorial.js',
            [],
            filemtime($js_path),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'lm_enqueue_home_outcomes_editorial', 100);
