
<footer class="lm-footer">
  <div class="lm-shell">
    <div class="lm-footer-grid">
      <div class="lm-footer-brand">
        <div class="lm-footer-title">Laura’s Mercantile</div>
        <p class="lm-footer-text">Plant-powered wellness from the farm. Sustainably sourced, clearly labeled, third-party tested.</p>
        <div class="lm-footer-small">© <?php echo esc_html(date('Y')); ?> Laura’s Mercantile</div>
      </div>

      <div class="lm-footer-col">
        <div class="lm-footer-heading">Explore</div>
        <?php
          if (has_nav_menu('primary')) {
            wp_nav_menu([
              'theme_location' => 'primary',
              'container' => 'nav',
              'menu_class' => 'lm-footer-menu',
              'depth' => 1,
              'fallback_cb' => false,
            ]);
          }
        ?>
      </div>

      <div class="lm-footer-col">
        <div class="lm-footer-heading">Help</div>
        <?php if (has_nav_menu('footer')) {
          wp_nav_menu([
            'theme_location' => 'footer',
            'container' => 'nav',
            'menu_class' => 'lm-footer-menu',
            'fallback_cb' => false,
          ]);
        } ?>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
