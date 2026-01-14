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

  // NOTE: we intentionally do NOT mount React automatically on Woo shop/category/product pages yet.
  // Those templates are high-impact; once the menu route-map is confirmed we can selectively
  // convert them. For now, Woo pages render via PHP for maximum stability.

  return false;
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
