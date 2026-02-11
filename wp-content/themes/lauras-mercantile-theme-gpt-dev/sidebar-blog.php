<?php
/**
 * Blog sidebar template.
 */
?>

<aside class="lm-blog-sidebar" aria-label="<?php esc_attr_e( 'Blog sidebar', 'lauras-mercantile' ); ?>">

  <?php
    // Conversion CTA (theme-level, content-only; safe fallback if context unknown)
    $cta = function_exists('lm_get_blog_sidebar_cta') ? lm_get_blog_sidebar_cta() : null;
    if ( is_array($cta) && !empty($cta['title']) && !empty($cta['button_url']) ) :
  ?>
    <section class="widget lm-cta-widget" aria-label="<?php echo esc_attr($cta['aria'] ?? 'Recommended next step'); ?>">
      <div class="lm-cta-widget__inner">
        <h3 class="widget-title lm-cta-widget__title"><?php echo esc_html($cta['title']); ?></h3>
        <?php if (!empty($cta['body'])) : ?>
          <p class="lm-cta-widget__body"><?php echo esc_html($cta['body']); ?></p>
        <?php endif; ?>
        <p class="lm-cta-widget__actions">
          <a class="lm-cta-widget__button" href="<?php echo esc_url($cta['button_url']); ?>">
            <?php echo esc_html($cta['button_text'] ?? 'Shop now'); ?>
          </a>
        </p>
      </div>
    </section>
  <?php endif; ?>
  <?php if ( is_active_sidebar( 'lm-blog-sidebar' ) ) : ?>
    <?php dynamic_sidebar( 'lm-blog-sidebar' ); ?>
  <?php else : ?>
    <section class="widget widget_search">
      <?php get_search_form(); ?>
    </section>

    <section class="widget widget_categories">
      <h3 class="widget-title"><?php esc_html_e( 'Categories', 'lauras-mercantile' ); ?></h3>
      <ul>
        <?php
        wp_list_categories( array(
          'title_li' => '',
        ) );
        ?>
      </ul>
    </section>

    <section class="widget widget_recent_entries">
      <h3 class="widget-title"><?php esc_html_e( 'Recent Articles', 'lauras-mercantile' ); ?></h3>
      <ul>
        <?php
        $recent = new WP_Query( array(
          'posts_per_page'      => 5,
          'ignore_sticky_posts' => true,
        ) );
        while ( $recent->have_posts() ) :
          $recent->the_post();
          ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; wp_reset_postdata(); ?>
      </ul>
    </section>

    <section class="widget widget_popular">
      <h3 class="widget-title"><?php esc_html_e( 'Popular Articles', 'lauras-mercantile' ); ?></h3>
      <ul>
        <?php
        $popular = new WP_Query( array(
          'posts_per_page'      => 5,
          'orderby'             => 'comment_count',
          'order'               => 'DESC',
          'ignore_sticky_posts' => true,
        ) );
        while ( $popular->have_posts() ) :
          $popular->the_post();
          ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; wp_reset_postdata(); ?>
      </ul>
    </section>
  <?php endif; ?>
</aside>
