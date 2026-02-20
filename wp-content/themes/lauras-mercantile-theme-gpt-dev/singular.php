<?php
if (!defined('ABSPATH')) exit;
get_header();
?>

<main class="lm-main">
  <?php if (is_single() || is_page('hemp-and-cbd-oil-education')) : ?>
    <div class="lm-shell lm-blog-layout">
      <div class="lm-blog-main">
        <?php
          while (have_posts()) : the_post();
            if (lm_should_show_theme_title()) {
              the_title('<header class="lm-entry-header"><h1 class="lm-entry-title">', '</h1></header>');
            }
            echo '<div class="entry-content">';
            if (is_page('hemp-and-cbd-oil-education')) {
              $content = get_the_content();
              $content = apply_filters('the_content', $content);
              echo do_shortcode($content);
            } else {
              the_content();
            }
            echo '</div>'; 
          endwhile;
        ?>
      </div>
      <?php get_sidebar('blog'); ?>
    </div>
  <?php else : ?>
    <div class="lm-prose">
      <?php
        while (have_posts()) : the_post();
          if (lm_should_show_theme_title()) {
            the_title('<header class="lm-entry-header"><h1 class="lm-entry-title">', '</h1></header>');
          }
          echo '<div class="entry-content">';
          if (is_page('hemp-and-cbd-oil-education')) {
              $content = get_the_content();
              $content = apply_filters('the_content', $content);
              echo do_shortcode($content);
            } else {
              the_content();
            }
          echo '</div>'; 
        endwhile;
      ?>
    </div>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
