
<footer class="lm-footer">
  <!-- Newsletter Section -->
  <section class="lm-footer-newsletter">
    <div class="lm-shell lm-footer-newsletter-inner">
      <div class="lm-footer-newsletter-copy">
        <h2>From the Farm to Your Inbox</h2>
        <p>Sign up for wellness tips, exclusive farm updates, and 15% off your first order.</p>
      </div>
      <form class="lm-footer-newsletter-form" action="#" method="post">
        <label class="screen-reader-text" for="lm-footer-email">Email address</label>
        <input id="lm-footer-email" type="email" name="email" placeholder="email@example.com" required />
        <button type="submit" class="lm-btn--accent">Get the Discount</button>
      </form>
    </div>
  </section>

  <!-- Main Footer Content -->
  <section class="lm-footer-main">
    <div class="lm-shell lm-footer-grid">
      <!-- Column 1: Brand & Bio -->
      <div class="lm-footer-col lm-footer-brand">
        <div class="lm-footer-logo-wrap">
          <h3 class="lm-footer-brand-name">Laura’s Mercantile</h3>
        </div>
        <p class="lm-footer-bio">Restoring the earth and healing bodies with regenerative hemp farming in the heart of Winchester, Kentucky.</p>
        <div class="lm-footer-social">
          <a href="#" aria-label="Facebook" class="lm-social-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
          </a>
          <a href="#" aria-label="Instagram" class="lm-social-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
          </a>
          <a href="#" aria-label="Twitter" class="lm-social-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
          </a>
        </div>
      </div>

      <!-- Column 2: Information -->
      <div class="lm-footer-col">
        <h4 class="lm-footer-heading">Information</h4>
        <ul class="lm-footer-menu">
           <li><a href="/shop">Shop All</a></li>
           <li><a href="/lab-results">Lab Results</a></li>
           <li><a href="/education-center">Education</a></li>
           <li><a href="/blog">Wellness Blog</a></li>
           <li><a href="/wholesale">Wholesale</a></li>
        </ul>
      </div>

      <!-- Column 3: Company -->
      <div class="lm-footer-col">
        <h4 class="lm-footer-heading">Company</h4>
        <ul class="lm-footer-menu">
           <li><a href="/our-approach">Our Approach</a></li>
           <li><a href="/meet-laura">Meet Laura</a></li>
           <li><a href="/contact">Contact Us</a></li>
           <li><a href="/faq">FAQs</a></li>
           <li><a href="/press">Press</a></li>
           <li><a href="/shipping-policy">Shipping</a></li>
           <li><a href="/refund-policy">Refunds</a></li>
           <li><a href="/payment-policy">Payment Policy</a></li>
           <li><a href="/privacy-policy">Privacy Policy</a></li>
        </ul>
      </div>

      <!-- Column 4: Contact -->
      <div class="lm-footer-col lm-footer-contact">
        <h4 class="lm-footer-heading">Visit Us</h4>
        <p>
          Mt. Folly Farm<br/>
          Winchester, KY 40391
        </p>
        <p class="lm-footer-contact-links">
          <a href="mailto:support@laurasmercantile.com">support@laurasmercantile.com</a>
        </p>
      </div>
    </div>
  </section>

  <!-- Bottom Bar -->
  <section class="lm-footer-bottom">
    <div class="lm-shell lm-footer-bottom-inner">
      <p class="lm-copyright">&copy; <?php echo date('Y'); ?> Laura’s Mercantile. All rights reserved.</p>
      <ul class="lm-footer-meta-menu">
          <li><a href="/terms-of-service">Terms of Service</a></li>
      </ul>
    </div>
  </section>
</footer>

<?php wp_footer(); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('lm-menu-toggle');
  const nav = document.getElementById('lm-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function() {
      const isOpen = nav.classList.toggle('is-open');
      document.body.classList.toggle('menu-open', isOpen);
      toggle.setAttribute('aria-expanded', isOpen);
      
      // Toggle icon
      if (isOpen) {
        toggle.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12" /></svg>';
      } else {
        toggle.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16" /></svg>';
      }
    });
  }
});
</script>
</body>
</html>
