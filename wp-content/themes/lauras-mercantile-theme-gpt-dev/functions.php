<?php
/**
 * Laura's Mercantile Theme GPT-DEV Functions
 */
echo "<!-- FUNCTIONS_PHP_LOADED_V10 -->";
echo "<!-- INIT_HOOK_REGISTERED: YES -->";

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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

  if (is_front_page() || is_home()) return true;
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
  
  // Explicitly enqueue main style.css to ensure it loads
  $style_css_path = get_stylesheet_directory() . '/style.css';
  if (file_exists($style_css_path)) {
    wp_enqueue_style('lm-style', get_stylesheet_uri(), ['lm-base'], filemtime($style_css_path));
  }
}
// Load base styles after any Vite/dist CSS so our theme rules reliably win.
add_action('wp_enqueue_scripts', 'lm_enqueue_base_styles', 10);
// also late-load to win specificity
add_action('wp_enqueue_scripts', 'lm_enqueue_base_styles', 90);

function lm_enqueue_mobile_nav_script() {
  $path = get_stylesheet_directory() . '/assets/mobile-nav.js';
  if (file_exists($path)) {
    wp_enqueue_script('lm-mobile-nav', get_stylesheet_directory_uri() . '/assets/mobile-nav.js', [], filemtime($path), true);
  }
}
add_action('wp_enqueue_scripts', 'lm_enqueue_mobile_nav_script', 95);


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
        // IMPORTANT: use filemtime for versioning to prevent stale-cached JS/CSS when the hash
        // does not change (common during iterative theme zip workflows).
        $js_rel  = $entry['file'];
        $js_path = $theme_path . '/assets/dist/' . $js_rel;
        $js_ver  = file_exists($js_path) ? filemtime($js_path) : null;
        wp_enqueue_script('lm-app', $theme_uri . '/assets/dist/' . $js_rel, [], $js_ver, true);
        if (!empty($entry['css']) && is_array($entry['css'])) {
          foreach ($entry['css'] as $css_file) {
            $css_path = $theme_path . '/assets/dist/' . $css_file;
            $css_ver  = file_exists($css_path) ? filemtime($css_path) : null;
            wp_enqueue_style('lm-app-' . md5($css_file), $theme_uri . '/assets/dist/' . $css_file, [], $css_ver);
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

/**
 * Global enhancements that should work across PHP Woo pages as well as React pages.
 * Keep this vanilla and dependency-free so it cannot break WooCommerce.
 */
function lm_enqueue_global_scripts() {
  $theme_path = get_stylesheet_directory();
  $theme_uri  = get_stylesheet_directory_uri();

  $qty_js = $theme_path . '/assets/lm-qty.js';
  if (file_exists($qty_js)) {
    wp_enqueue_script('lm-qty', $theme_uri . '/assets/lm-qty.js', [], filemtime($qty_js), true);
  }
}
add_action('wp_enqueue_scripts', 'lm_enqueue_global_scripts', 30);

/**
 * Homepage-only: editorialize the Outcomes section.
 * Scoped + auditable. Does not affect checkout/cart/product pages.
 */
function lm_enqueue_home_outcomes_editorial() {
  if (!is_front_page() && !is_home()) return;
  if (!lm_should_mount_app()) return;

  $theme_path = get_stylesheet_directory();
  $theme_uri  = get_stylesheet_directory_uri();
  $js_path = $theme_path . '/assets/js/home-outcomes-editorial.js';
  if (file_exists($js_path)) {
    wp_enqueue_script(
      'lm-home-outcomes-editorial',
      $theme_uri . '/assets/js/home-outcomes-editorial.js',
      array(),
      filemtime($js_path),
      true
    );
  }
}
add_action('wp_enqueue_scripts', 'lm_enqueue_home_outcomes_editorial', 95);

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
 * Ensure "Our Approach" page exists so the header link never 404s.
 * The content is fully editable from the WordPress admin (Pages → Our Approach).
 */
function lm_ensure_our_approach_page() {
  // Look for an existing page by path first
  $page = get_page_by_path('our-approach');
  if ($page) {
    return;
  }

  // Try to find by title in case slug changed
  $existing = get_page_by_title('Our Approach');
  if ($existing) {
    // Make sure permalink slug is "our-approach" for consistency
    if ($existing->post_name !== 'our-approach') {
      wp_update_post([
        'ID'        => $existing->ID,
        'post_name' => 'our-approach',
      ]);
    }
    return;
  }

  // Create a simple, fully CMS-editable page
  $page_id = wp_insert_post([
    'post_title'   => 'Our Approach',
    'post_name'    => 'our-approach',
    'post_status'  => 'publish',
    'post_type'    => 'page',
    'post_content' => "Add your approach content here in the WordPress editor. This page was created automatically by the theme so the header link never returns a 404.",
  ]);

  // No need to do anything with $page_id here; admin can edit as needed.
}
add_action('after_switch_theme', 'lm_ensure_our_approach_page');

// Ensure legacy Visual Composer / WPBakery shortcodes still render
add_filter('the_content', 'do_shortcode', 11);




/**
 * Lightweight replacement for legacy The7 [dt_blog_list] shortcode.
 * The old theme used this to render an education/blog grid. The original
 * plugin is no longer active, so we provide a compatible shortcode that
 * outputs a simple, well-styled article grid using this theme.
 */
function lm_dt_blog_list_replacement( $atts = array() ) {
  $atts = shortcode_atts(
    array(
      'category'           => '',
      'posts_per_page'     => 5,
      'jsm_posts_per_page' => '',
      'posts_offset'       => 0,
    ),
    $atts,
    'dt_blog_list'
  );

  if ( ! empty( $atts['jsm_posts_per_page'] ) ) {
    $atts['posts_per_page'] = (int) $atts['jsm_posts_per_page'];
  }

  $per_page = max( 1, (int) $atts['posts_per_page'] );
  $offset   = max( 0, (int) $atts['posts_offset'] );

  $cat_ids = array();
  if ( ! empty( $atts['category'] ) ) {
    $parts   = preg_split( '/\s*,\s*/', $atts['category'] );
    $cat_ids = array_filter( array_map( 'intval', (array) $parts ) );
  }

  $query_args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'offset'         => $offset,
  );

  if ( ! empty( $cat_ids ) ) {
    $query_args['category__in'] = $cat_ids;
  }

  $q = new WP_Query( $query_args );
  if ( ! $q->have_posts() ) {
    return '';
  }

  ob_start();
  ?>
  <div class="lm-blog-grid">
    <?php while ( $q->have_posts() ) : $q->the_post(); ?>
      <article class="lm-blog-card">
        <?php if ( has_post_thumbnail() ) : ?>
          <a class="lm-blog-card-thumb" href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium_large' ); ?>
          </a>
        <?php endif; ?>
        <div class="lm-blog-card-body">
          <h2 class="lm-blog-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <p class="lm-blog-card-meta"><?php echo esc_html( get_the_date() ); ?></p>
          <p class="lm-blog-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26, '…' ) ); ?></p>
          <a class="lm-blog-card-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Keep Reading', 'lauras-mercantile' ); ?> &rarr;</a>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
  <?php
  wp_reset_postdata();
  return trim( ob_get_clean() );
}
add_shortcode( 'dt_blog_list', 'lm_dt_blog_list_replacement' );

/**
 * Disable cross-sells on the cart page only.
 * Removes the "You may be interested in…" section to prevent cart layout offset issues.
 */
add_action('wp', function () {
    if (is_admin()) { return; }
    if (function_exists('is_cart') && is_cart()) {
        remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    }
});


/**
 * Blog sidebar widgets
 */
function lm_register_blog_sidebar() {
  register_sidebar( array(
    'name'          => __( 'Blog Sidebar', 'lauras-mercantile' ),
    'id'            => 'lm-blog-sidebar',
    'description'   => __( 'Sidebar for blog posts and articles.', 'lauras-mercantile' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ) );
}
add_action( 'widgets_init', 'lm_register_blog_sidebar' );


/**
 * Outcome landing pages
 *
 * These provide sales-focused landing pages for "Shop by Outcome" links without
 * requiring the site owner to create WordPress Pages in wp-admin.
 *
 * Routes:
 *  - /outcomes/sleep-better/
 *  - /outcomes/move-without-pain/
 *  - /outcomes/brain-health/
 */
function lm_register_outcome_routes() {
  add_rewrite_rule(
    '^outcomes/(sleep-better|move-without-pain|brain-health)/?$',
    'index.php?lm_outcome=$matches[1]',
    'top'
  );
}
add_action('init', 'lm_register_outcome_routes');

function lm_register_outcome_query_var($vars) {
  $vars[] = 'lm_outcome';
  return $vars;
}
add_filter('query_vars', 'lm_register_outcome_query_var');

function lm_outcome_template_include($template) {
  $outcome = get_query_var('lm_outcome');
  if (!$outcome) return $template;

  $candidate = get_stylesheet_directory() . '/page-outcome.php';
  if (file_exists($candidate)) return $candidate;
  return $template;
}
add_filter('template_include', 'lm_outcome_template_include', 50);

function lm_flush_rewrite_rules_on_switch() {
  lm_register_outcome_routes();
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'lm_flush_rewrite_rules_on_switch');

/**
 * Ensure outcome routes keep working after theme updates.
 *
 * On many hosts, deploying a new theme zip does NOT trigger after_switch_theme,
 * so rewrite rules never get refreshed and /outcomes/* pages can 404.
 *
 * We flush once per theme version (lightweight + safe).
 */
function lm_maybe_flush_outcome_rewrites() {
  $theme = wp_get_theme();
  $ver = (string) $theme->get('Version');
  if ($ver === '') {
    // Fallback to stylesheet directory mtime if Version missing.
    $ver = (string) @filemtime(get_stylesheet_directory());
  }

  $opt_key = 'lm_outcome_rewrites_flushed_ver';
  $last = (string) get_option($opt_key, '');
  if ($last === $ver) return;

  lm_register_outcome_routes();
  // false => don't hard flush .htaccess on every update (still refreshes WP rules)
  flush_rewrite_rules(false);
  update_option($opt_key, $ver, true);
}
add_action('init', 'lm_maybe_flush_outcome_rewrites', 20);

/**
 * Ensure "Shop by Outcome" menu items always point at the real outcome landing pages.
 *
 * Some historical menu setups used WordPress search URLs (/?s=pain) or placeholder links.
 * Fixing this server-side makes the behavior consistent across staging/production and
 * avoids timing issues with React/JS-rendered menus.
 */
function lm_normalize_outcome_menu_urls( $items, $args ) {
  if ( empty( $items ) || ! is_array( $items ) ) return $items;

  $map = array(
    // Primary labels
    'sleep better' => home_url('/outcomes/sleep-better/'),
    'move without pain' => home_url('/outcomes/move-without-pain/'),
    'keep your brain healthy' => home_url('/outcomes/brain-health/'),
    // Secondary/short labels
    'sleep' => home_url('/outcomes/sleep-better/'),
    'pain' => home_url('/outcomes/move-without-pain/'),
    'brain health' => home_url('/outcomes/brain-health/'),
  );

  foreach ( $items as $item ) {
    if ( ! is_object( $item ) ) continue;

    $title = isset($item->title) ? strtolower( trim( wp_strip_all_tags( $item->title ) ) ) : '';
    $url   = isset($item->url) ? trim( (string) $item->url ) : '';

    // If the label matches, always normalize.
    if ( $title !== '' && isset( $map[ $title ] ) ) {
      $item->url = $map[ $title ];
      continue;
    }

    // If it was set up as a WP search URL, normalize based on the search term.
    // Examples: /?s=pain, https://example.com/?s=sleep
    if ( $url !== '' && preg_match( '/[\?&]s=([^&]+)/', $url, $m ) ) {
      $term = strtolower( urldecode( $m[1] ) );
      $term = preg_replace('/\s+/', ' ', trim($term));

      if ( $term === 'sleep' || $term === 'sleep better' ) {
        $item->url = home_url('/outcomes/sleep-better/');
      } elseif ( $term === 'pain' || $term === 'move without pain' || $term === 'move-without-pain' ) {
        $item->url = home_url('/outcomes/move-without-pain/');
      } elseif ( $term === 'brain' || $term === 'brain health' || $term === 'keep your brain healthy' ) {
        $item->url = home_url('/outcomes/brain-health/');
      }
    }
  }

  return $items;
}
add_filter( 'wp_nav_menu_objects', 'lm_normalize_outcome_menu_urls', 20, 2 );


/**
 * Blog sidebar CTA context.
 */
function lm_get_blog_sidebar_cta() {
  $default = array(
    'aria' => 'Recommended next step',
    'title' => 'CBD works better with fats',
    'body'  => 'Mt. Folly products emphasize lipid-based delivery to support absorption.',
    'button_text' => 'Shop Full-Spectrum CBD →',
    'button_url'  => home_url('/shop/'),
  );

  if ( ! is_singular('post') ) return $default;

  $post_id = get_the_ID();
  if ( ! $post_id ) return $default;

  $title = strtolower( get_the_title( $post_id ) );
  $terms = wp_get_post_terms( $post_id, array('category','post_tag'), array('fields' => 'names') );
  $haystack = $title . ' ' . strtolower( implode(' ', (array)$terms ) );

  if ( strpos($haystack, 'reishi') !== false || strpos($haystack, 'cordyceps') !== false || strpos($haystack, 'mushroom') !== false ) {
    return array(
      'aria' => 'Recommended next step',
      'title' => 'Functional mushrooms, evidence-first',
      'body'  => 'Formulations verified for active compounds.',
      'button_text' => 'Shop Mushrooms →',
      'button_url'  => home_url('/shop/'),
    );
  }

  if ( strpos($haystack, 'runoff') !== false || strpos($haystack, 'water') !== false || strpos($haystack, 'organic') !== false || strpos($haystack, 'karst') !== false ) {
    return array(
      'aria' => 'Recommended next step',
      'title' => 'Start with full-spectrum CBD',
      'body'  => 'Explore core products and dosing basics.',
      'button_text' => 'Shop CBD →',
      'button_url'  => home_url('/shop/'),
    );
  }

  return $default;
}

/**
 * Seed owner-requested content as BLOG POSTS (content-only).
 * Runs once per activation; to re-run, delete option lm_owner_content_seed_v1_done.
 */
function lm_seed_owner_content_posts() {
  if ( get_option('lm_owner_content_seed_v1_done') ) return;

  $upsert = function($args) {
    $slug = $args['post_name'];
    $existing = get_page_by_path( $slug, OBJECT, 'post' );
    if ( $existing && isset($existing->ID) ) {
      wp_update_post( array(
        'ID' => $existing->ID,
        'post_title' => $args['post_title'],
        'post_content' => $args['post_content'],
        'post_status' => 'publish',
      ) );
      return $existing->ID;
    }
    return wp_insert_post( $args );
  };

  $lm_farming_orgwater_html = <<<'HTML'
<h2>The runoff challenge</h2>
<p>Conventional farming often relies on nitrogen and phosphorus fertilizers. When it rains, some of those nutrients can wash into creeks and rivers, contributing to algae blooms and low-oxygen conditions downstream, including the Gulf of Mexico’s “dead zone.”</p>

<h2>The organic difference at Mt. Folly</h2>
<p>Mt. Folly is organic. Our fertility comes from nitrogen-fixing cover crops, compost, compost teas, and biochar. We do not apply synthetic nitrogen fertilizer.</p>
<p>We grow fertility in place using legumes such as vetch, crimson clover, and Austrian winter peas. In our ley system, when fields are in hay and pasture, the mix includes alfalfa, chicory, clovers, timothy, orchard grass, and warm-season grasses as summers get hotter.</p>

<h2>Karst water is fast water</h2>
<p>Mt. Folly sits on a limestone karst landscape. Water moves quickly through cracks, sinkholes, and cave systems. That makes groundwater protection immediate and personal.</p>

<h2>A non-negotiable line</h2>
<p>I decided to go organic after exploring the cave system beneath the south end of the farm and realizing that a contracted corn grower was applying atrazine above that cave network. On karst, “out of sight” is not “out of water.”</p>
<p>Dumping synthetic chemicals into limestone cave systems is a red line for me.</p>
HTML;

  $lm_reishi_html = <<<'HTML'
<p>Reishi mushrooms (<em>Ganoderma lucidum</em>) have been used in East Asian medicine for over two thousand years. Modern research focuses on polysaccharides (including beta-glucans), triterpenes, and antioxidant compounds.</p>

<h2>Immune support</h2>
<p>Beta-glucans in reishi can bind to receptors on immune cells, including macrophages and natural killer cells. This can support immune surveillance while helping regulate inflammatory signaling.</p>

<h2>Sleep and stress response</h2>
<p>Reishi supplementation has been associated with improved sleep quality and reduced stress response in some studies. Proposed mechanisms include effects on neurotransmitter balance and shifts in gut microbiota that influence the gut–brain axis.</p>

<h2>Oxidative stress and cellular protection</h2>
<p>Reishi contains antioxidant and anti-inflammatory compounds that may reduce oxidative stress. Reviews of human and animal studies report increases in antioxidant capacity and markers of protection against oxidative DNA injury after reishi intake.</p>

<h2>How we formulate</h2>
<p>Our reishi supplement is made from <em>Ganoderma lucidum</em> and verified for active compounds.</p>
<ul>
  <li>Supports immune function via beta-glucans and related polysaccharides</li>
  <li>Supports sleep quality and stress response</li>
  <li>Provides antioxidant support</li>
</ul>
HTML;

  $lm_cordyceps_html = <<<'HTML'
<p>Cordyceps (<em>Cordyceps militaris</em>) is studied for effects on energy metabolism and oxygen utilization. Research suggests cordyceps may support ATP production, the molecule cells use for energy.</p>

<h2>Energy and endurance</h2>
<p>By supporting ATP availability and oxygen efficiency, cordyceps is commonly used to support endurance, training adaptation, and fatigue resistance.</p>

<h2>Additional researched effects</h2>
<ul>
  <li>Immune signaling support</li>
  <li>Respiratory efficiency and oxygen utilization</li>
  <li>Antioxidant activity related to recovery</li>
</ul>

<h2>Product notes</h2>
<p>For cordyceps supplements, look for fruiting-body content (not only mycelium), verified bioactives, and clear sourcing.</p>
HTML;

  $lm_caramels_html = <<<'HTML'
<h2>Sweets that help CBD work harder</h2>
<p>CBD is fat-soluble. That means it is absorbed more effectively when consumed with dietary fats.</p>
<p>Mt. Folly CBD Caramels pair full-spectrum CBD with rich, buttery caramel made by The Ruth Hunt Candy Company, a Kentucky confectionery with more than 100 years of experience. Each caramel contains 20 mg of full-spectrum CBD extracted from USDA-certified organic Kentucky hemp.</p>
<p>Because cannabinoids dissolve in fat, the caramel’s natural lipids support better absorption compared to sugar-based gummies. Each batch is third-party tested for potency, purity, and THC compliance.</p>
<h3>Key details</h3>
<ul>
<li>20 mg full-spectrum CBD per caramel</li>
<li>USDA-certified organic Kentucky hemp</li>
<li>Fat-based delivery to support absorption</li>
<li>Third-party lab tested</li>
<li>Less than 0.3% THC</li>
<li>35 calories and 3 g sugar per caramel</li>
</ul>
HTML;

  $lm_chocolate_html = <<<'HTML'
<h2>Why CBD and chocolate work together</h2>
<p>CBD is better absorbed when paired with fats. Dark chocolate naturally contains cocoa butter and other lipids that help cannabinoids pass through digestion and into circulation.</p>
<p>Mt. Folly CBD Chocolate is made in small batches by the Ruth Hunt Candy Company using full-spectrum CBD from USDA-certified organic hemp. Each square contains 20 mg of CBD and melts slowly, allowing for gradual absorption.</p>
<p>Dark chocolate also provides flavonoids and polyphenols, compounds associated with antioxidant activity and reduced oxidative stress.</p>
<h3>About Mt. Folly CBD Chocolate</h3>
<ul>
<li>240 mg full-spectrum CBD per pouch</li>
<li>20 mg CBD per piece (12 pieces per bag)</li>
<li>USDA-certified organic hemp</li>
<li>Less than 0.3% THC</li>
<li>Third-party lab tested</li>
</ul>
HTML;


  $upsert(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'post_title' => 'The Runoff Challenge: Organic Farming and Water',
    'post_name' => 'farming-orgwater',
    'post_content' => wp_kses_post($lm_farming_orgwater_html),
  ));

  $upsert(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'post_title' => 'Reishi Mushrooms: Immune, Stress, and Antioxidant Support',
    'post_name' => 'reishi-mushrooms',
    'post_content' => wp_kses_post($lm_reishi_html),
  ));

  $upsert(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'post_title' => 'Cordyceps: Energy Metabolism, Endurance, and Recovery',
    'post_name' => 'cordyceps-mushrooms',
    'post_content' => wp_kses_post($lm_cordyceps_html),
  ));

  // Optional: update product descriptions if we can match uniquely.
  $maybe_update_product = function($title_fragment, $new_content_html) {
    $q = new WP_Query(array(
      'post_type' => 'product',
      'post_status' => 'any',
      's' => $title_fragment,
      'posts_per_page' => 5,
    ));
    if ( $q->found_posts === 1 ) {
      $p = $q->posts[0];
      wp_update_post(array(
        'ID' => $p->ID,
        'post_content' => wp_kses_post($new_content_html),
      ));
      return true;
    }
    return false;
  };

  $maybe_update_product('Caramel', $lm_caramels_html);
  $maybe_update_product('Caramels', $lm_caramels_html);
  $maybe_update_product('Chocolate', $lm_chocolate_html);
  $maybe_update_product('Chocolates', $lm_chocolate_html);

  update_option('lm_owner_content_seed_v1_done', 1, false);
}
add_action('after_switch_theme', 'lm_seed_owner_content_posts', 30 );


/* ===== Ship-safe WooCommerce CSS (scoped) ===== */
add_action('wp_enqueue_scripts', function () {
  if (!function_exists('is_woocommerce')) return;
  if (!(is_woocommerce() || is_cart() || is_checkout() || is_account_page())) return;

  $path = get_stylesheet_directory() . '/assets/woocommerce.css';
  if (!file_exists($path)) return;

  wp_enqueue_style(
    'lm-woocommerce-scoped-ship',
    get_stylesheet_directory_uri() . '/assets/woocommerce.css',
    array(),
    filemtime($path)
  );
}, 99);

/**
 * Emergency hotfix for broken Turmeric images (404s in media library).
 * Overrides the image HTML for specific product IDs.
 *
 * @param string $html Original image HTML.
 * @param WC_Product|int $product_or_id Product object or ID.
 * @return string Modified HTML.
 */
function lm_fix_turmeric_images($html, $product_or_id) {
    $product_id = is_object($product_or_id) ? $product_or_id->get_id() : (int) $product_or_id;
    
    $map = [
        166466 => [
            'url' => 'https://laurasmercantile.com/wp-content/uploads/2026/02/turmeric.avif',
            'alt' => 'Ancient Nutrition Turmeric'
        ],
        138510 => [
            'url' => 'https://laurasmercantile.com/wp-content/uploads/2026/02/turmeric-500x750.avif',
            'alt' => 'NOW Turmeric Capsules'
        ],
        139017 => [
            'url' => 'https://laurasmercantile.com/wp-content/uploads/2026/02/turmeric_cbd_oil_bundle-500x750.avif',
            'alt' => 'Turmeric & CBD Bundle 1500'
        ],
        166471 => [
            'url' => 'https://laurasmercantile.com/wp-content/uploads/2026/02/turmeric_cbd_oil_bundle-500x750.avif',
            'alt' => 'Turmeric & CBD Bundle 1500'
        ],
        166473 => [
            'url' => 'https://laurasmercantile.com/wp-content/uploads/2026/02/turmeric_cbd_oil_bundle-500x750.avif',
            'alt' => 'Turmeric & CBD Bundle 3000'
        ]
    ];

    if (isset($map[$product_id])) {
        $img = $map[$product_id];
        // Return a clean img tag that works in both grid and single product contexts.
        return sprintf(
            '<img src="%s" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt="%s" loading="lazy" />',
            esc_url($img['url']),
            esc_attr($img['alt'])
        );
    }
    return $html;
}
add_filter('woocommerce_product_get_image', 'lm_fix_turmeric_images', 20, 2);
add_filter('woocommerce_single_product_image_thumbnail_html', 'lm_fix_turmeric_images', 20, 2);

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

    // ONLY apply custom ordering if the user has explicitly selected 'Default sorting' (menu_order)
    // OR if no orderby is set and we want to experiment. 
    // BUT the user says live is better, and live is popularity.
    // So we ONLY trigger this if menu_order is requested.
    $current_orderby = $query->get('orderby');
    if ($current_orderby !== 'menu_order' && $current_orderby !== 'default') {
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
    if (!isset($sortby['menu_order'])) {
        $sortby = array('menu_order' => 'Default sorting') + $sortby;
    }
    return $sortby;
}
add_filter('woocommerce_catalog_orderby', 'lm_add_default_sorting_to_dropdown', 999999);

/**
 * REVERT DEFAULT: Set the default shop sorting to "popularity" to match live.
 */
function lm_set_popularity_as_default($orderby) {
    return 'popularity';
}
add_filter('woocommerce_default_catalog_orderby', 'lm_set_popularity_as_default', 999999);

/**
 * Ensure the main query defaults to popularity if no explicit sorting is requested.
 */
function lm_force_popularity_args($args) {
    if (!isset($_GET['orderby'])) {
        $args['orderby'] = 'popularity';
        $args['order']   = 'DESC';
    }
    return $args;
}
add_filter('woocommerce_get_catalog_ordering_args', 'lm_force_popularity_args', 999999);

/**
 * Remove pagination from the shop and category pages to show all products at once.
 */
add_filter('loop_shop_per_page', function($cols) {
    return 999;
}, 999999);

/**
 * Alternative push for pagination removal via pre_get_posts.
 */
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category() || is_product_tag())) {
        $query->set('posts_per_page', -1);
    }
}, 999999);

/**
 * Diagnostic debug feedback in footer.
 */
add_action('wp_footer', function() {
    echo "<!-- ACTIVE_THEME_SLUG: " . esc_html(get_stylesheet()) . " -->";
    echo "<!-- FILTERS_REGISTERED_CHECK: " . (has_filter('woocommerce_catalog_orderby', 'lm_add_default_sorting_to_dropdown') ? 'YES' : 'NO') . " -->";
    echo "<!-- DEFAULT_ORDERBY_CHECK: " . (has_filter('woocommerce_default_catalog_orderby', 'lm_set_popularity_as_default') ? 'YES' : 'NO') . " -->";
    global $wp_query;
    echo "<!-- IS_PRODUCT_QUERY_MAIN: " . (($wp_query->get('post_type') === 'product' || is_shop() || is_product_category()) ? 'YES' : 'NO') . " -->";
    echo "<!-- POSTS_ORDERBY_FILTER_COUNT: " . (has_filter('posts_orderby', 'lm_custom_posts_orderby') ? 'YES' : 'NO') . " -->";
}, 999999);
