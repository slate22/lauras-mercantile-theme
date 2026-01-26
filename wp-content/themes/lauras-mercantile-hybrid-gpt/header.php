<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- PROMOTIONAL BANNER - JAVASCRIPT FALLBACK -->
<div id="promo-banner" style="display:none; position: fixed; top: 0; left: 0; right: 0; background: #28a745; color: white; padding: 20px; text-align: center; font-weight: 700; font-size: 18px; z-index: 999999; box-shadow: 0 4px 20px rgba(40, 167, 69, 0.4); font-family: Arial, sans-serif; line-height: 1.4;">
  🍄 CBD Chocolates and Caramels on sale now! No Coupon Needed. 🍄
</div>

<!-- NEWSLETTER SIGNUP FORM -->
<div class="lm-newsletter-popup" id="lm-newsletter-popup" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 1000000; max-width: 400px; width: 90%;">
  <div class="newsletter-header">
    <h3 style="margin: 0 0 15px 0; color: #333; font-size: 20px;">🎉 Get 15% Off!</h3>
    <p style="margin: 0 0 20px 0; color: #666; line-height: 1.4;">Receive a coupon code for 15% off your order, plus subscribe to our newsletter for news about hemp and CBD legal landscape, news from Mt. Folly, advice about Sleep, reducing inflammation, and brain health.</p>
  </div>
  
  <form id="lm-newsletter-form" class="newsletter-form">
    <input type="email" name="email" placeholder="Enter your email address" required style="width: 100%; padding: 12px; border: 2px solid #e1e4e8; border-radius: 6px; margin-bottom: 15px; font-size: 16px; box-sizing: border-box;">
    <button type="submit" class="newsletter-submit" style="width: 100%; background: #28a745; color: white; border: none; padding: 15px; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;">Subscribe & Get Coupon</button>
  </form>
  
  <div class="newsletter-success" style="display:none; background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; text-align: center; margin-top: 15px;">
    <strong>🎉 Success!</strong> Check your email for your 15% off coupon code!
  </div>
  
  <div class="newsletter-close" style="position: absolute; top: 10px; right: 10px; cursor: pointer; font-size: 20px; color: #999;">×</div>
</div>

<script>
// Newsletter popup functionality
document.addEventListener('DOMContentLoaded', function() {
  var popup = document.getElementById('lm-newsletter-popup');
  var form = document.getElementById('lm-newsletter-form');
  var closeBtn = document.querySelector('.newsletter-close');
  var banner = document.getElementById('promo-banner');
  var body = document.body;
  
  // Show popup on home page after 3 seconds
  if (body && (body.classList.contains('home') || 
      body.classList.contains('front-page') || 
      body.classList.contains('page-home') ||
      window.location.pathname === '/' ||
      window.location.pathname === '/index.php')) {
    
    setTimeout(function() {
      if (popup && !localStorage.getItem('lm_newsletter_subscribed')) {
        popup.style.display = 'block';
      }
    }, 3000);
  }
  
  // Close popup
  if (closeBtn) {
    closeBtn.addEventListener('click', function() {
      popup.style.display = 'none';
    });
  }
  
  // Form submission
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      var email = form.querySelector('input[type="email"]').value;
      var submitBtn = form.querySelector('.newsletter-submit');
      var successDiv = form.querySelector('.newsletter-success');
      
      // Show loading state
      submitBtn.textContent = 'Subscribing...';
      submitBtn.disabled = true;
      
      // AJAX request to WordPress
      fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          'action': 'lm_klaviyo_subscribe_newsletter',
          'email': email,
          'nonce': '<?php echo wp_create_nonce("newsletter_subscribe"); ?>'
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Mark as subscribed
          localStorage.setItem('lm_newsletter_subscribed', 'true');
          localStorage.setItem('lm_coupon_code', data.coupon_code);
          localStorage.setItem('lm_coupon_discount', data.coupon_discount);
          
          // Show success message
          form.style.display = 'none';
          successDiv.style.display = 'block';
          
          // Update any coupon displays on page
          updateCouponDisplays();
          
          // Close popup after 5 seconds
          setTimeout(function() {
            popup.style.display = 'none';
          }, 5000);
          
        } else {
          submitBtn.textContent = 'Try Again';
          submitBtn.disabled = false;
        }
      })
      .catch(error => {
        console.error('Newsletter signup error:', error);
        submitBtn.textContent = 'Try Again';
        submitBtn.disabled = false;
      });
    });
  }
  
  // Update coupon displays throughout the site
  function updateCouponDisplays() {
    var couponCode = localStorage.getItem('lm_coupon_code');
    var couponDiscount = localStorage.getItem('lm_coupon_discount');
    
    if (couponCode && couponDiscount) {
      document.querySelectorAll('.coupon-display').forEach(function(el) {
        el.textContent = couponCode + ' - ' + couponDiscount + ' OFF';
      });
    }
  }
  
  // Show banner on home page only
  if (banner && body) {
    if (body.classList.contains('home') || 
        body.classList.contains('front-page') || 
        body.classList.contains('page-home') ||
        window.location.pathname === '/' ||
        window.location.pathname === '/index.php') {
      
      banner.style.display = 'block';
      
      // Add body padding to avoid overlap
      if (body.style.paddingTop === '' || body.style.paddingTop === '0px') {
        body.style.paddingTop = '80px';
      }
    }
  }
});
</script>

<div class="lm-topbar">FREE SHIPPING ON ORDERS OVER $50</div>

<header class="lm-header">
  <div class="lm-shell">
    <div class="lm-header-inner">
      <a class="lm-brand" href="<?php echo esc_url(home_url('/')); ?>">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.png" alt="Laura's Mercantile" style="height: 180px; width: auto; display: block;" />
      </a>

      <nav class="lm-nav" aria-label="Primary" id="lm-nav">
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
              ['Our Approach', '/our-approach/'],
              ['Lab Results', '/lab-results/'],
              ['Education', '/education/'],
              ['About Laura', '/about-laura/'],
            ];
            foreach ($items as $it) {
              echo '<a href="'.esc_url(home_url($it[1])).'">'.esc_html($it[0]).'</a>';
            }
          }
        ?>
      </nav>

      <button class="lm-menu-toggle" aria-label="Toggle menu" id="lm-menu-toggle" aria-expanded="false">
        <span class="lm-menu-toggle-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </span>
        <span class="lm-menu-toggle-text">Menu</span>
      </button>

      <div class="lm-actions">
        <a class="lm-icon-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>?s=" aria-label="Search">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="1.8"/><path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </a>
        <a class="lm-icon-btn" href="<?php echo function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : esc_url(home_url('/cart/')); ?>" aria-label="Cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M6.5 7h15l-1.2 7.2a2 2 0 0 1-2 1.7H9a2 2 0 0 1-2-1.6L5.7 3.8A1.5 1.5 0 0 0 4.2 2.5H2.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.5 21a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4ZM18 21a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z" fill="currentColor"/></svg>
        </a>
      </div>
    </div>
  </div>
</header>
