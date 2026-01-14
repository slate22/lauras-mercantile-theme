<?php
if (!defined('ABSPATH')) exit;
get_header();
?>

<main class="lm-main">
  <div class="lm-prose">
    <header class="lm-entry-header">
      <h1 class="lm-entry-title">Search results for: <?php echo esc_html(get_search_query()); ?></h1>
    </header>
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article class="lm-archive-item">
          <h2 class="lm-archive-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <div class="lm-archive-excerpt">
            <?php the_excerpt(); ?>
          </div>
        </article>
      <?php endwhile; ?>
      <div class="lm-archive-pagination"><?php the_posts_pagination(); ?></div>
    <?php else : ?>
      <p class="lm-muted">No results found.</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
