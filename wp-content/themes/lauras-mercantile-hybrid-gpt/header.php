<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="lm-topbar">FREE SHIPPING ON ORDERS OVER $50</div>

<header class="lm-header">
  <div class="lm-shell">
    <div class="lm-header-inner">
      <a class="lm-brand" href="<?php echo esc_url(home_url('/')); ?>">
        <span class="lm-brand-mark" aria-hidden="true"></span>
        <span>Laura’s Mercantile</span>
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
