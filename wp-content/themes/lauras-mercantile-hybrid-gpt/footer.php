
<footer class="lm-footer">
  <!-- Newsletter band -->
  <section class="lm-footer-newsletter">
    <div class="lm-container lm-footer-newsletter-inner">
      <div class="lm-footer-newsletter-copy">
        <h2>Join the Mercantile Community</h2>
        <p>Subscribe for wellness tips, exclusive offers, and farm updates.</p>
      </div>
      <form class="lm-footer-newsletter-form" action="#" method="post">
        <label class="screen-reader-text" for="lm-footer-email">Email address</label>
        <input id="lm-footer-email" type="email" name="email" placeholder="Enter your email address" required />
        <button type="submit" class="lm-btn--accent">Subscribe</button>
      </form>
    </div>
  </section>

  <!-- Main footer columns -->
  <section class="lm-footer-main">
    <div class="lm-container lm-footer-grid">
      <div class="lm-footer-col lm-footer-brand">
        <h3>Laura’s Mercantile</h3>
        <p>Restoring the earth and healing bodies with regenerative hemp farming in Winchester, Kentucky.</p>
        <!-- social links here -->
      </div>

      <div class="lm-footer-col">
        <h3>Information</h3>
        <?php
        wp_nav_menu([
          'theme_location' => 'footer', // Using 'footer' as that is what is registered in functions.php
          'container'      => false,
          'menu_class'     => 'lm-footer-menu lm-footer-menu--cols',
          'fallback_cb'    => false,
        ]);
        ?>
      </div>

      <div class="lm-footer-col lm-footer-company-contact">
        <div class="lm-footer-company">
          <h3>Company</h3>
           <!-- Placeholder for company menu if needed, using primary for now or empty -->
           <ul class="lm-footer-menu">
             <li><a href="/our-approach">Our Approach</a></li>
             <li><a href="/meet-laura">Meet Laura</a></li>
           </ul>
        </div>
        <div class="lm-footer-contact">
          <h3>Contact</h3>
          <p>
            <a href="mailto:support@laurasmercantile.com">support@laurasmercantile.com</a><br/>
            Mt. Folly Farm<br/>
            Winchester, KY 40391
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="lm-footer-bottom">
    <div class="lm-container lm-footer-bottom-inner">
      <p>&copy; <?php echo date('Y'); ?> Laura’s Mercantile. All rights reserved.</p>
      <ul class="lm-footer-meta-menu">
          <li><a href="/privacy-policy">Privacy Policy</a></li>
          <li><a href="/terms-of-service">Terms of Service</a></li>
      </ul>
    </div>
  </section>
</footer>

<?php wp_footer(); ?>
</body>
</html>
