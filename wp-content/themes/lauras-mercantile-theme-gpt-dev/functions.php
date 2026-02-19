<?php
/**
 * Laura's Mercantile Theme GPT-DEV Functions
 */
echo "<!-- FUNCTIONS_PHP_LOADED_V8 -->";
echo "<!-- INIT_HOOK_REGISTERED: YES -->";

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Enqueue base styles and custom style.css.
 */
function lm_enqueue_base_styles() {
  $base_css_path = get_stylesheet_directory() . '/assets/base.css';
  if (file_exists($base_css_path)) {
    wp_enqueue_style('lm-base', get_stylesheet_directory_uri() . '/assets/base.css', [], filemtime($base_css_path));
  }
  
  // Explicitly enqueue main style.css to ensure it loads with correct versioning
  $style_css_path = get_stylesheet_directory() . '/style.css';
  if (file_exists($style_css_path)) {
    wp_enqueue_style('lm-style', get_stylesheet_uri(), ['lm-base'], filemtime($style_css_path));
  }
}
add_action('wp_enqueue_scripts', 'lm_enqueue_base_styles');

/**
 * Enqueue mobile navigation script.
 */
function lm_enqueue_mobile_nav_script() {
  $script_path = get_stylesheet_directory() . '/assets/mobile-nav.js';
  if (file_exists($script_path)) {
    wp_enqueue_script('lm-mobile-nav', get_stylesheet_directory_uri() . '/assets/mobile-nav.js', [], filemtime($script_path), true);
  }
}
add_action('wp_enqueue_scripts', 'lm_enqueue_mobile_nav_script');

/**
 * Determine if we should mount the React application.
 */
function lm_should_mount_app() {
  // Check if React is explicitly disabled by a constant (useful for debugging).
  if (defined('LM_REACT_DISABLED') && LM_REACT_DISABLED) {
    return false;
  }
  
  // Do NOT mount React on WooCommerce core pages.
  if (function_exists('is_woocommerce')) {
    if (is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url()) {
      return false;
    }
  }
  
  // Mount on homepage or if the page template is set to React.
  if (is_front_page() || is_home() || is_page_template('page-react.php')) {
    return true;
  }
  
  return false;
}

/**
 * Enqueue React production bundle.
 */
function lm_enqueue_react_app() {
  if (!lm_should_mount_app()) {
    return;
  }

  $manifest_path = get_stylesheet_directory() . '/dist/.vite/manifest.json';
  if (!file_exists($manifest_path)) {
    return;
  }

  $manifest = json_decode(file_get_contents($manifest_path), true);
  if (!isset($manifest['index.html'])) {
    return;
  }

  $entry = $manifest['index.html'];

  // Enqueue the main JS file.
  wp_enqueue_script(
    'lm-react-app',
    get_stylesheet_directory_uri() . '/dist/' . $entry['file'],
    [],
    null,
    true
  );

  // Enqueue CSS files if they exist.
  if (isset($entry['css'])) {
    foreach ($entry['css'] as $index => $css_file) {
      wp_enqueue_style(
        'lm-react-style-' . $index,
        get_stylesheet_directory_uri() . '/dist/' . $css_file,
        [],
        null
      );
    }
  }

  // Localize data for the React app.
  wp_localize_script('lm-react-app', 'lm_data', [
    'theme_url' => get_stylesheet_directory_uri(),
    'site_url'  => home_url(),
    'rest_url'  => esc_url_raw(rest_url()),
    'nonce'     => wp_create_nonce('wp_rest'),
    'is_logged_in' => is_user_logged_in(),
    'endpoints' => [
      'logout' => wp_logout_url(home_url()),
      'shop'   => home_url('/shop/'),
      'cart'   => home_url('/cart/'),
    ]
  ]);
}
add_action('wp_enqueue_scripts', 'lm_enqueue_react_app');

/**
 * Add a root element for the React app.
 */
function lm_add_mount_point() {
  if (lm_should_mount_app()) {
    echo '<div id="root"></div>';
  }
}
add_action('wp_body_open', 'lm_add_mount_point');

/**
 * Template redirect for the React application.
 */
function lm_handle_react_routing() {
  if (!lm_should_mount_app()) {
    return;
  }

  // Ensure we use the React template if we're on the homepage or a React-enabled page.
  // This helps prevent default WP templates from conflicting with the React mount point.
  if (is_front_page() || is_home()) {
    // If you want to force a specific template file:
    // locate_template('page-react.php', true);
    // exit;
  }
}
add_action('template_redirect', 'lm_handle_react_routing');

/**
 * Custom WooCommerce overrides and layout adjustments.
 */
function lm_setup_woocommerce() {
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'lm_setup_woocommerce');

/**
 * Remove default WooCommerce breadcrumbs from the shop and category pages.
 */
add_action('wp', function() {
    if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag() || is_post_type_archive('product'))) {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        add_filter('is_active_sidebar', '__return_false');
    }
}, 10);

/**
 * Product Sorting Logic - Refined for Staging Environment IDs
 */
function lm_custom_posts_orderby($orderby, $query) {
    if (is_admin() || !$query->is_main_query()) {
        return $orderby;
    }

    // Check if it's a product query
    $is_product_query = (
        $query->get('post_type') === 'product' || 
        is_shop() || 
        is_product_category() || 
        is_product_tag() || 
        is_post_type_archive('product')
    );

    if (!$is_product_query || $query->get('s')) {
        return $orderby;
    }

    global $wpdb;

    // Correct IDs for Staging
    $mushrooms_ids = [165275, 165279, 165274, 165277];
    $jtp_ids = [150318, 150315, 157876, 164145];
    $turmeric_ids = [163552, 166466, 166471, 166473];

    $mushrooms_ids_str = implode(',', $mushrooms_ids);
    $jtp_ids_str = implode(',', $jtp_ids);
    $turmeric_ids_str = implode(',', $turmeric_ids);

    // Build Priority CASE statement with fallbacks for titles
    $priority_sql = " (CASE 
        WHEN {$wpdb->posts}.ID IN ($mushrooms_ids_str) THEN 10
        WHEN {$wpdb->posts}.post_title LIKE '%Mushroom%' THEN 15
        WHEN {$wpdb->posts}.ID IN ($jtp_ids_str) THEN 20
        WHEN {$wpdb->posts}.post_title LIKE '%ONCO-ADJUNCT%' THEN 25
        WHEN {$wpdb->posts}.ID IN ($turmeric_ids_str) THEN 30
        WHEN {$wpdb->posts}.post_title LIKE '%Turmeric%' THEN 35
        ELSE 45 
    END) ASC";

    if (empty($orderby)) {
        return $priority_sql;
    }
    
    return $priority_sql . ", " . $orderby;
}
add_filter('posts_orderby', 'lm_custom_posts_orderby', 999999, 2);

/**
 * Ensure 'Default sorting' (menu_order) is available in the sorting dropdown.
 */
function lm_add_default_sorting_to_dropdown($sortby) {
    echo "<!-- LM_CATALOG_ORDERBY_TRIGGERED: YES -->";
    if (!isset($sortby['menu_order'])) {
        $sortby = array('menu_order' => 'Default sorting') + $sortby;
    }
    return $sortby;
}
add_filter('woocommerce_catalog_orderby', 'lm_add_default_sorting_to_dropdown', 999999);

/**
 * Force Default sorting (menu_order) as the base orderby.
 */
add_filter('woocommerce_default_catalog_orderby', function($orderby) {
    return 'menu_order';
}, 999999);

/**
 * Diagnostic debug feedback in footer.
 */
add_action('wp_footer', function() {
    echo "<!-- ACTIVE_THEME_SLUG: " . esc_html(get_stylesheet()) . " -->";
    echo "<!-- FILTERS_REGISTERED_CHECK: " . (has_filter('woocommerce_catalog_orderby', 'lm_add_default_sorting_to_dropdown') ? 'YES' : 'NO') . " -->";
    echo "<!-- DEFAULT_ORDERBY_CHECK: " . (has_filter('woocommerce_default_catalog_orderby') ? 'YES' : 'NO') . " -->";
    global $wp_query;
    echo "<!-- IS_PRODUCT_QUERY_MAIN: " . (($wp_query->get('post_type') === 'product' || is_shop() || is_product_category()) ? 'YES' : 'NO') . " -->";
    echo "<!-- POSTS_ORDERBY_FILTER_COUNT: " . (has_filter('posts_orderby', 'lm_custom_posts_orderby') ? 'YES' : 'NO') . " -->";
}, 999999);
