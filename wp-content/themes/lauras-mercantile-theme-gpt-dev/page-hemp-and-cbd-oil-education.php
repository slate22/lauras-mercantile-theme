<?php
if (!defined('ABSPATH')) exit;
get_header();
?>

<main class="lm-main">
  <div class="lm-prose">
    <?php
      // Standard page header; keep intro very light to avoid legacy shortcodes.
      if (have_posts()) : while (have_posts()) : the_post();
        the_title('<header class="lm-entry-header"><h1 class="lm-entry-title">', '</h1></header>');
        // Optional short plain-text intro can be managed via the excerpt field.
        $intro = get_the_excerpt();
        if (!empty($intro)) {
          echo '<p class="lm-page-intro">' . esc_html($intro) . '</p>';
        }
      endwhile; endif;
    ?>

    <?php
      // Custom hemp & CBD education posts grid
      $paged = get_query_var('paged') ? (int) get_query_var('paged') : 1;
      if (!$paged) {
        $paged = get_query_var('page') ? (int) get_query_var('page') : 1;
      }

      $allowed_sorts = array('newest', 'oldest', 'comments');
      $current_sort  = isset($_GET['sort']) ? sanitize_key($_GET['sort']) : 'newest';
      if (!in_array($current_sort, $allowed_sorts, true)) {
        $current_sort = 'newest';
      }

      $orderby = 'date';
      $order   = 'DESC';

      if ($current_sort === 'oldest') {
        $order = 'ASC';
      } elseif ($current_sort === 'comments') {
        $orderby = 'comment_count';
        $order   = 'DESC';
      }

      $query_args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 12,
        'paged'               => $paged,
        'orderby'             => $orderby,
        'order'               => $order,
        'ignore_sticky_posts' => true,
      );

$hemp_query = new WP_Query($query_args);
    ?>

    <?php if ($hemp_query->have_posts()) : ?>
      <div class="lm-blog-sort">
        <?php
          $base_url = get_permalink();
          // $current_sort is already normalized above.
          $sort_options = array(
            'newest'   => __('Newest First', 'lauras-mercantile'),
            'oldest'   => __('Oldest First', 'lauras-mercantile'),
            'comments' => __('Most Commented', 'lauras-mercantile'),
          );
        ?>
        <?php foreach ($sort_options as $key => $label) : 
          $url = esc_url(add_query_arg('sort', $key, $base_url));
          $active_class = ($current_sort === $key) ? ' lm-blog-sort-link--active' : '';
        ?>
          <a class="lm-blog-sort-link<?php echo $active_class; ?>" href="<?php echo $url; ?>">
            <?php echo esc_html($label); ?>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="lm-blog-grid">
        <?php while ($hemp_query->have_posts()) : $hemp_query->the_post(); ?>
          <article <?php post_class('lm-blog-card'); ?>>
            <?php if (has_post_thumbnail()) : ?>
              <a class="lm-blog-card-thumb" href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium_large'); ?>
              </a>
            <?php endif; ?>

            <div class="lm-blog-card-body">
              <h2 class="lm-blog-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <p class="lm-blog-card-meta">
                <?php echo esc_html(get_the_date()); ?>
              </p>
              <div class="lm-blog-card-excerpt">
                <?php the_excerpt(); ?>
              </div>
              <a class="lm-blog-card-link" href="<?php the_permalink(); ?>">
                <?php esc_html_e('Keep Reading', 'lauras-mercantile'); ?> &rarr;
              </a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <div class="lm-archive-pagination">
        <?php
          // Temporarily swap global query so the_posts_pagination works with our custom query.
          global $wp_query;
          $prev_query = $wp_query;
          $wp_query = $hemp_query;

          the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => __('&laquo; Newer', 'lauras-mercantile'),
            'next_text' => __('Older &raquo;', 'lauras-mercantile'),
          ));

          // Restore original query.
          $wp_query = $prev_query;
          wp_reset_postdata();
        ?>
      </div>
    <?php else : ?>
      <p class="lm-muted">No education posts found yet.</p>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
