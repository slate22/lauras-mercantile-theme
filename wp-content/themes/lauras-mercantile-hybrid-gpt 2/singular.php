<?php
if (!defined('ABSPATH')) exit;
get_header();
?>

<main class="lm-main">
  <div class="lm-prose">
    <?php
      while (have_posts()) : the_post();
        if (!function_exists('is_woocommerce') || !is_woocommerce()) {
            the_title('<header class="lm-entry-header"><h1 class="lm-entry-title">', '</h1></header>');
        }
        echo '<div class="entry-content">';
        the_content();
        echo '</div>'; 
      endwhile;
    ?>
  </div>
</main>

<?php get_footer(); ?>
