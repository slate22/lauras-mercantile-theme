<?php
if (!defined('ABSPATH')) exit;

require_once get_stylesheet_directory() . '/inc/class-lm-walker-nav.php';

/** 
 * Global kill switch: set in wp-config.php
 */

// Klaviyo Configuration
if (!defined('KLAVIYO_API_KEY')) {
    define('KLAVIYO_API_KEY', 'your_klaviyo_api_key_here');
}
if (!defined('KLAVIYO_LIST_ID')) {
    define('KLAVIYO_LIST_ID', 'your_list_id_here');
}

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
 * Klaviyo Newsletter Integration
 */
function lm_klaviyo_subscribe_newsletter() {
  if (!isset($_POST['action']) || $_POST['action'] !== 'lm_klaviyo_subscribe') {
    return;
  }
  
  $email = sanitize_email($_POST['email']);
  
  if (!is_email($email)) {
    wp_send_json_error(['success' => false, 'message' => 'Invalid email address']);
    return;
  }
  
  // Check if Klaviyo is available
  if (!class_exists('Klaviyo')) {
    wp_send_json_error(['success' => false, 'message' => 'Klaviyo plugin not available']);
    return;
  }
  
  // Subscribe to Klaviyo list via API
  try {
    $klaviyo = new \Klaviyo();
    $klaviyo->setApiKey(KLAVIYO_API_KEY);
    
    $list = $klaviyo->getLists(); // Correct usage depends on Klaviyo library
    // The previous code had $klaviyo->getList(KLAVIYO_LIST_ID)
    
    // I'll stick to the logic provided but clean it up
    $result = $klaviyo->subscribeToList(KLAVIYO_LIST_ID, $email);
    
    if ($result) {
      // Generate WooCommerce coupon
      $coupon_code = strtoupper(substr(md5($email . time() . 'SECRET_KEY'), 0, 8));
      $coupon_id = wc_create_coupon([
        'code' => $coupon_code,
        'discount_type' => 'fixed_cart',
        'amount' => 15,
        'individual_use' => true,
        'usage_limit' => 1,
        'exclude_sale_items' => true,
        'expiry_date' => date('Y-m-d', strtotime('+30 days')),
      ]);
      
      // Send confirmation email
      $subject = "Your 15% Off Coupon from Laura's Mercantile!";
      $message = "Thank you for subscribing! Use coupon code: {$coupon_code} for 15% off your next order.";
      $headers = ['Content-Type: text/html; charset=UTF-8'];
      
      wp_mail($email, $subject, $message, $headers);
      
      wp_send_json_success([
        'success' => true, 
        'message' => 'Subscription successful! Check your email for the coupon code.',
        'coupon_code' => $coupon_code,
        'coupon_discount' => 15
      ]);
    } else {
      wp_send_json_error(['success' => false, 'message' => 'Subscription failed']);
    }
  } catch (Exception $e) {
    wp_send_json_error(['success' => false, 'message' => 'Klaviyo API error: ' . $e->getMessage()]);
  }
}

// AJAX handler
add_action('wp_ajax_lm_klaviyo_subscribe_newsletter', 'lm_klaviyo_subscribe_newsletter');
add_action('wp_ajax_nopriv_lm_klaviyo_subscribe_newsletter', 'lm_klaviyo_subscribe_newsletter');

/**
 * Product Description Updates for Sleep Ailment Section
 */
function lm_update_product_descriptions() {
  $gummies = get_posts([
    'post_type' => 'product',
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'tax_query' => [
      [
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => ['gummies']
      ]
    ]
  ]);
  
  $oils = get_posts([
    'post_type' => 'product',
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'tax_query' => [
      [
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => ['oils']
      ]
    ]
  ]);
  
  $chocolates_caramels = get_posts([
    'post_type' => 'product',
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'tax_query' => [
      [
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => ['chocolates', 'caramels']
      ]
    ]
  ]);
  
  // Update CBD Gummies
  foreach ($gummies as $gummy) {
    if (has_term($gummy->ID, 'gummies')) {
      $new_content = $gummy->post_content . '<p class="sleep-combo-info"><strong>🌙 Sleep Combination:</strong> CBD + CBN + Valerian for enhanced relaxation and deeper sleep cycles. Our specially formulated gummies work together to promote calm, reduce anxiety, and support restful sleep throughout the night.</p>';
      
      wp_update_post($gummy->ID, [
        'post_content' => $new_content,
        'post_excerpt' => 'Enhanced sleep formula with CBD, CBN, and Valerian for maximum restful benefits'
      ]);
    }
  }
  
  // Update CBD Oils
  foreach ($oils as $oil) {
    if (has_term($oil->ID, 'oils')) {
      $new_content = $oil->post_content . '<p class="flexibility-info"><strong>💪 Superior Delivery:</strong> Fast-absorbing CBD oils for quicker onset with lasting effects. Perfect for those needing rapid relief from discomfort, inflammation, or sleep disturbances. Our premium carrier oils ensure maximum bioavailability and consistent dosing.</p>';
      
      wp_update_post($oil->ID, [
        'post_content' => $new_content,
        'post_excerpt' => 'Fast-acting CBD oils with superior absorption for immediate and lasting relief'
      ]);
    }
  }
  
  // Update Chocolates/Caramels
  foreach ($chocolates_caramels as $choco) {
    if (has_term($choco->ID, ['chocolates', 'caramels'])) {
      $new_content = $choco->post_content . '<p class="comfort-info"><strong>🍬 Softer, Better Sleep:</strong> Artisanal chocolates and caramels crafted for enhanced relaxation. Premium ingredients like L-theanine, magnesium, and passionflower help promote calm and improve sleep quality without the grogginess of traditional sleep aids.</p>';
      
      wp_update_post($choco->ID, [
        'post_content' => $new_content,
        'post_excerpt' => 'Premium chocolates and caramels with sleep-promoting ingredients for restful nights'
      ]);
    }
  }
}

// Hook into WordPress init
add_action('init', 'lm_update_product_descriptions');

function lm_is_react_page(): bool {
  return lm_should_mount_app();
}

function lm_enqueue_base_styles() {
  $base_css_path = get_stylesheet_directory() . '/assets/base.css';
  if (file_exists($base_css_path)) {
    wp_enqueue_style('lm-base', get_stylesheet_directory_uri() . '/assets/base.css', [], filemtime($base_css_path));
  }
  
  // Enqueue main style.css last to ensure it has highest priority for overrides
  wp_enqueue_style('lm-style', get_stylesheet_uri(), ['lm-base'], filemtime(get_stylesheet_directory() . '/style.css'));
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
    'footer'  => __('Footer Menu', 'lauras-hybrid'),
  ]);
}
add_action('after_setup_theme', 'lm_theme_setup');

// Remove WooCommerce sidebar on shop and product archive pages
add_action('wp', function () {
    if (function_exists('is_woocommerce') && (is_woocommerce() || is_shop() || is_product_category() || is_product_tag())) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
        add_filter('is_active_sidebar', '__return_false');
    }
});
