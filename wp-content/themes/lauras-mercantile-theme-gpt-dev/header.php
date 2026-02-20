<!doctype html>
<!-- THEME_GPT_DEV_ACTIVE_V4 -->
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <!-- Open Graph & SEO meta -->
  <meta property="og:site_name" content="Laura's Mercantile" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="Full Spectrum CBD Oil and other Products for a long, healthy life." />
  <meta property="og:description" content="Farm-grown full spectrum CBD oil, mushrooms, and other botanical products from Mt. Folly Farm to support a long, healthy life." />
  <meta property="og:url" content="<?php echo esc_url( home_url( '/' ) ); ?>" />
  <meta property="og:image" content="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/laura-field-1200.jpg' ); ?>" />
  <meta property="og:image:width" content="1600" />
  <meta property="og:image:height" content="900" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Full Spectrum CBD Oil and other Products for a long, healthy life." />
  <meta name="twitter:description" content="Farm-grown full spectrum CBD oil, mushrooms, and botanical products from Mt. Folly Farm." />
  <meta name="twitter:image" content="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/laura-field-1200.jpg' ); ?>" />

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Laura's Mercantile",
    "url": "<?php echo esc_url( home_url( '/' ) ); ?>",
    "potentialAction": {
      "@type": "SearchAction",
      "target": "<?php echo esc_url( home_url( '/' ) ); ?>?s={search_term_string}",
      "query-input": "required name=search_term_string"
    }
  }
  </script>

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Laura's Mercantile",
    "url": "<?php echo esc_url( home_url( '/' ) ); ?>",
    "logo": "<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-final.png' ); ?>",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "1 S Main St",
      "addressLocality": "Winchester",
      "addressRegion": "KY",
      "postalCode": "40391",
      "addressCountry": "US"
    },
    "telephone": "+1-859-474-8218"
  }
  </script>

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Laura's Mercantile",
    "url": "<?php echo esc_url( home_url( '/' ) ); ?>",
    "image": "<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/laura-field-1200.jpg' ); ?>",
    "telephone": "+1-859-474-8218",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "1 S Main St",
      "addressLocality": "Winchester",
      "addressRegion": "KY",
      "postalCode": "40391",
      "addressCountry": "US"
    }
  }
  </script>

  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( function_exists('is_front_page') && is_front_page() ) : ?>
  <div class="lm-promo-banner" role="region" aria-label="Promotion">
    🍄 Take 20% off <a href="/product-category/full-spectrum-cbd-oil/" style="color: #fff; text-decoration: underline;">Full Spectrum CBD Oil</a>, <a href="/product-category/on-sale/cbd-products-and-bundles/" style="color: #fff; text-decoration: underline;">CBD Selection and Bundles Specials</a> and Functional Mushrooms. No coupon required. 🍄
  </div>
<?php endif; ?>

<div class="lm-top-bar">
  <div class="lm-shell">
    <div class="lm-top-bar-inner">
      <?php
        if (has_nav_menu('top_bar')) {
          wp_nav_menu([
            'theme_location' => 'top_bar',
            'container' => false,
            'items_wrap' => '<ul class="lm-top-bar-menu">%3$s</ul>',
            'fallback_cb' => false,
          ]);
        } else {
          echo '<ul class="lm-top-bar-menu">';
          echo '<li><a href="'.esc_url(home_url('/cbd-military-veteran-discount-program/')).'">Military Discount</a></li>';
          echo '<li><a href="'.esc_url(home_url('/becoming-a-friend-of-laura-a-loyalty-program/')).'">Loyalty Program</a></li>';
          echo '</ul>';
        }
      ?>
    </div>
  </div>
</div>



<header class="lm-header">
  <div class="lm-shell">
    <div class="lm-header-inner">
      <a class="lm-brand" href="<?php echo esc_url(home_url('/')); ?>">
        <img class="lm-logo" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.png" alt="Laura's Mercantile" />
      </a>

      <nav class="lm-nav" aria-label="Primary">
        <?php
          if (has_nav_menu('primary')) {
            wp_nav_menu([
              'theme_location' => 'primary',
              'walker' => class_exists('LM_Walker_Nav_Menu') ? new LM_Walker_Nav_Menu() : null,
              'container' => false,
              // Wrap items in a UL so we can style dropdowns reliably.
              'items_wrap' => '<ul class="lm-menu">%3$s</ul>',
              'fallback_cb' => false,
            ]);
          } else {
            // Fallback to key items if no menu assigned yet.
            $items = [
              ['Shop', '/shop/'],
              ['Our Approach', 'https://laurasmercantile.com/agricultural-sustainability-efforts-of-mt-folly-farm/'],
              ['Lab Results', 'https://laurasmercantile.com/cbd-legal/'],
              ['Education', '/hemp-and-cbd-oil-education/'],
              ['About Laura', '/about-laura/'],
            ];
            foreach ($items as $it) {
              echo '<a href="'.esc_url(home_url($it[1])).'">'.esc_html($it[0]).'</a>';
            }
          }
        ?>
      </nav>

      
      <div class="lm-actions">
        <?php
          $account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/');
          $cart_url    = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
        ?>

        <button type="button" class="lm-icon-btn lm-search-toggle" aria-label="Search">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="1.8" />
            <line x1="15.5" y1="15.5" x2="20" y2="20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
          </svg>
        </button>
        <a class="lm-icon-btn" href="<?php echo esc_url( $account_url ); ?>" aria-label="My account">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="8" r="3.2" stroke="currentColor" stroke-width="1.6" />
            <path d="M6 18.2C6.9 15.8 9.2 14 12 14s5.1 1.8 6 4.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
          </svg>
        </a>
        <a class="lm-icon-btn" href="<?php echo esc_url( $cart_url ); ?>" aria-label="Cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M4 5h2l2 11h9l2-8H8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="10" cy="19" r="1.2" fill="currentColor" />
            <circle cx="17" cy="19" r="1.2" fill="currentColor" />
          </svg>
        </a>

        <button class="lm-mobile-toggle lm-icon-btn" aria-label="Open menu" aria-controls="lm-mobile-drawer" aria-expanded="false" type="button">
          <span class="lm-mobile-toggle-bars" aria-hidden="true"></span>
        </button>
      </div>
      <div class="lm-search-bar" aria-hidden="true">
    <div class="lm-search-bar-inner">
      <button type="button" class="lm-search-close" aria-label="Close search">×</button>

      <form role="search" method="get" class="lm-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label class="screen-reader-text" for="lm-search-input">Search for:</label>
        <input type="search" id="lm-search-input" class="lm-search-input" placeholder="Search products, articles, and more…" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="lm-button lm-search-submit">Search</button>
      </form>
    </div>
  </div>
</header>


<div class="lm-mobile-overlay" hidden data-lm-close="1"></div>
<aside id="lm-mobile-drawer" class="lm-mobile-drawer" aria-hidden="true">
  <div class="lm-mobile-drawer-head">
    <div class="lm-mobile-drawer-title">Menu</div>
    <button class="lm-mobile-close" type="button" aria-label="Close menu" data-lm-close="1">×</button>
  </div>

  <nav class="lm-mobile-nav" aria-label="Mobile">
    <?php
      if (has_nav_menu('primary')) {
        wp_nav_menu([
          'theme_location' => 'primary',
          'walker' => class_exists('LM_Walker_Nav_Menu') ? new LM_Walker_Nav_Menu() : null,
          'container' => false,
          'items_wrap' => '<ul class="lm-mobile-menu">%3$s</ul>',
          'fallback_cb' => false,
        ]);
      } else {
        echo '<ul class="lm-mobile-menu">';
        $items = [
          ['Home', '/'],
          ['Shop', '/shop/'],
          ['Meet Laura', '/meet-laura/'],
          ['Questions', '/questions/'],
          ['Military', '/cbd-military-veteran-discount-program/'],
          ['Loyalty', '/becoming-a-friend-of-laura-a-loyalty-program/'],
        ];
        foreach ($items as $it) {
          echo '<li><a href="'.esc_url(home_url($it[1])).'">'.esc_html($it[0]).'</a></li>';
        }
        echo '</ul>';
      }

      $account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/');
      $cart_url    = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
    ?>

    <div class="lm-mobile-quicklinks">
      <a class="lm-btn secondary" style="width:100%; text-align:center; margin-top:10px;" href="<?php echo esc_url($cart_url); ?>">Cart</a>
      <a class="lm-btn" style="width:100%; text-align:center; margin-top:10px;" href="<?php echo esc_url($account_url); ?>">Account</a>
    </div>
  </nav>
</aside>



<script>
document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.querySelector('.lm-search-toggle');
  var closeBtn = document.querySelector('.lm-search-close');
  var body = document.body;
  function closeSearch() {
    body.classList.remove('lm-search-open');
  }
  if (toggle) {
    toggle.addEventListener('click', function () {
      body.classList.toggle('lm-search-open');
      var input = document.getElementById('lm-search-input');
      if (body.classList.contains('lm-search-open') && input) {
        setTimeout(function(){ input.focus(); }, 10);
      }
    });
  }
  if (closeBtn) {
    closeBtn.addEventListener('click', function () {
      closeSearch();
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' || e.key === 'Esc') {
      closeSearch();
    }
  });

  // Handle Mobile Menu Toggle
  var mobileToggle = document.querySelector('.lm-mobile-toggle');
  var drawer = document.getElementById('lm-mobile-drawer');
  var overlay = document.querySelector('.lm-mobile-overlay');
  var closeElements = document.querySelectorAll('[data-lm-close]');

  function toggleMenu(forceClose) {
    var isOpen = body.classList.contains('lm-mobile-open');
    if (forceClose || isOpen) {
      body.classList.remove('lm-mobile-open');
      mobileToggle.setAttribute('aria-expanded', 'false');
      drawer.setAttribute('aria-hidden', 'true');
    } else {
      body.classList.add('lm-mobile-open');
      mobileToggle.setAttribute('aria-expanded', 'true');
      drawer.setAttribute('aria-hidden', 'false');
    }
  }

  if (mobileToggle) {
    mobileToggle.addEventListener('click', function(){ toggleMenu(); });
  }

  closeElements.forEach(function(el) {
    el.addEventListener('click', function(){ toggleMenu(true); });
  });

  // Normalize any "Our Approach" links to sustainability URL
  var approachUrl = 'https://laurasmercantile.com/agricultural-sustainability-efforts-of-mt-folly-farm/';
  
  // 1. Map by href
  var linksByHref = document.querySelectorAll('a[href="/our-approach"], a[href$="/our-approach/"]');
  linksByHref.forEach(function(a) { a.setAttribute('href', approachUrl); });
  
  // 2. Map by text content
  var allLinks = document.querySelectorAll('a');
  allLinks.forEach(function(link) {
    var text = (link.textContent || '').trim();
    if (text === 'Our Approach' || text === 'Sustainability') {
      link.setAttribute('href', approachUrl);
    }
  });

  // Fix "Shop by Outcome" links so they always land on real outcome pages.
  function normalizeOutcomeLinks(root) {
    var outcomeMap = {
      'sleep better': '/outcomes/sleep-better/',
      'move without pain': '/outcomes/move-without-pain/',
      'keep your brain healthy': '/outcomes/brain-health/',
      'brain health': '/outcomes/brain-health/',
      'sleep': '/outcomes/sleep-better/',
      'pain': '/outcomes/move-without-pain/'
    };

    // Restrict to primary nav/mega menus to avoid unintended overrides elsewhere.
    (root || document).querySelectorAll('.lm-menu a, .lm-menu .sub-menu a').forEach(function(link){
      var label = (link.textContent || '').replace(/\s+/g,' ').trim().toLowerCase();
      var href = (link.getAttribute('href') || '').trim();

      // Normalize by label when we can.
      if (label && outcomeMap[label]) {
        link.setAttribute('href', outcomeMap[label]);
        return;
      }

      // Normalize legacy "search" menu URLs: /?s=pain, ?s=sleep, etc.
      if (href && /[?&]s=/.test(href)) {
        try {
          // Works for absolute and relative URLs.
          var u = new URL(href, window.location.origin);
          var term = (u.searchParams.get('s') || '').replace(/\s+/g,' ').trim().toLowerCase();
          if (term === 'sleep' || term === 'sleep better') link.setAttribute('href', '/outcomes/sleep-better/');
          if (term === 'pain' || term === 'move without pain' || term === 'move-without-pain') link.setAttribute('href', '/outcomes/move-without-pain/');
          if (term === 'brain' || term === 'brain health' || term === 'keep your brain healthy') link.setAttribute('href', '/outcomes/brain-health/');
        } catch (e) {}
      }
    });
  }

  normalizeOutcomeLinks(document);
  if (window.MutationObserver) {
    var obs = new MutationObserver(function(muts){
      var shouldRun = false;
      muts.forEach(function(m){
        if (m.addedNodes && m.addedNodes.length) shouldRun = true;
      });
      if (shouldRun) normalizeOutcomeLinks(document);
    });
    obs.observe(document.body, { childList: true, subtree: true });
  }

  // Smooth scroll for on-page anchor links
  var anchorLinks = document.querySelectorAll('a[href^="#"]:not([href="#"])');
  anchorLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
      var targetId = this.getAttribute('href').slice(1);
      var target = document.getElementById(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
});</script>
