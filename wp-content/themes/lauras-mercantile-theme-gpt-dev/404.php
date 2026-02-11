<?php
if (!defined('ABSPATH')) exit;
get_header();
?>

<main class="lm-main">
  <div class="lm-prose" style="text-align:center;">
    <h1 style="font-family: var(--lm-serif); margin:0 0 10px;">Page not found</h1>
    <p style="margin:0 0 16px; color: var(--lm-muted);">The page you’re looking for doesn’t exist (or moved).</p>
    <a class="button" href="<?php echo esc_url(home_url('/')); ?>">Back to Home</a>
  </div>
</main>

<?php get_footer(); ?>
