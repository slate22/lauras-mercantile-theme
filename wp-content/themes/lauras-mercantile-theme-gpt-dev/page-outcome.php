<?php
/**
 * Outcome landing page template
 *
 * Rendered via /outcomes/<slug>/ using rewrite rules (see functions.php).
 */

get_header();

$outcome = get_query_var('lm_outcome');

// Outcome configuration: copy, product categories, and hero imagery.
$map = array(
  'sleep-better' => array(
    'title' => 'Sleep Better',
    'kicker' => 'Shop by Outcome',
    'subtitle' => 'A calmer evening routine — and better nights, consistently.',
    'lede' => 'Start with a format that fits your routine (gummies, oil, or sweets). We’ve grouped Laura’s favorites so you can find the right starting point fast.',
    // Outcome hero art: conversion-forward illustration (premium, consistent, no photo-stretch issues).
    'hero_image' => get_stylesheet_directory_uri() . '/assets/images/outcome-sleep-hero-illus.jpg',
    'benefits' => array(
      'Relax into your evening routine',
      'Full-spectrum, thoughtfully sourced',
      'Clear labels — nothing hidden',
    ),
    'trust' => array('U.S.-grown hemp', 'Third-party tested', 'Clearly labeled'),
    'primary_cta' => array('label' => 'Shop Sleep Support', 'url' => home_url('/outcomes/sleep-better/#products')),
    'blocks' => array(
      array(
        'heading' => 'Gummies',
        'desc' => 'Easy, travel-friendly servings — a customer favorite for evening calm.',
        'category' => 'full-spectrum-gummies',
      ),
      array(
        'heading' => 'CBD Oils & Extracts',
        'desc' => 'Flexible dosing for a steady, consistent nightly routine.',
        'category' => 'full-spectrum-cbd-oil',
      ),
      array(
        'heading' => 'Caramels & Sweets',
        'desc' => 'A gentle way to wind down — great for those who prefer sweets over gummies.',
        'category' => 'cbd-sweets',
      ),
    ),
    'how_to' => array(
      array('title' => 'Start low, go steady', 'text' => 'Begin with a small serving for a few nights. Increase gradually until you find your sweet spot.'),
      array('title' => 'Pair with your routine', 'text' => 'Take products at a consistent time and support results with your existing wind-down habits.'),
      array('title' => 'Choose a format you’ll stick with', 'text' => 'Gummies for simplicity, oils for flexibility, sweets for a softer option.'),
    ),
    'faq' => array(
      array('q' => 'When should I take sleep support?', 'a' => 'Most people start 30–60 minutes before bed. If you’re sensitive, start earlier and adjust over a few nights.'),
      array('q' => 'Should I choose gummies or oil?', 'a' => 'Gummies are simple and pre-measured. Oils offer flexible dosing and are great for a consistent nightly routine.'),
      array('q' => 'What if I’m new to CBD?', 'a' => 'Start with the smallest suggested serving and increase gradually. Consistency matters more than a big first dose.'),
    ),
    // Sales-letter modules: persuasive but factual, designed to reduce choice anxiety.
    'letter' => array(
      'problem' => 'If your nights are restless, your whole day pays the price — focus, mood, and recovery all start with sleep. The goal isn’t “more products.” It’s one routine you’ll actually stick with.',
      'promise' => 'Pick one format below and start small. These are Laura’s most-loved ways to build an evening ritual: simple, consistent, and clearly labeled.',
      'plan' => array(
        array('title' => 'Nights 1–3', 'text' => 'Start with the smallest suggested serving. Take it at the same time each night (30–60 minutes before bed is common).'),
        array('title' => 'Nights 4–7', 'text' => 'If you want more support, increase gradually. Keep everything else the same — consistency is the multiplier.'),
        array('title' => 'After 1 week', 'text' => 'Stay with the format that feels easiest. If you’re still deciding, gummies = simplest, oil = most flexible.'),
      ),
      'reassurance' => array(
        'Third-party tested and clearly labeled',
        'Full-spectrum options from U.S.-grown hemp',
        'Choose the format you’ll use consistently',
      ),
    ),
  ),

  'move-without-pain' => array(
    'title' => 'Move Without Pain',
    'kicker' => 'Shop by Outcome',
    'subtitle' => 'Targeted support for daily aches, recovery, and getting back to what you love.',
    'lede' => 'Choose topical relief where you feel it most, and pair with a daily oil routine for broader support. These are the formats customers reach for first.',
    'hero_image' => get_stylesheet_directory_uri() . '/assets/images/outcome-move-hero-illus.jpg',
    'benefits' => array(
      'Targeted relief where you need it',
      'Full-spectrum options for daily support',
      'Third-party tested, clearly labeled',
    ),
    'trust' => array('Targeted topicals', 'Full-spectrum support', 'Third-party tested'),
    'primary_cta' => array('label' => 'Shop Relief & Recovery', 'url' => home_url('/outcomes/move-without-pain/#products')),
    'blocks' => array(
      array(
        'heading' => 'Topicals',
        'desc' => 'Apply directly where you need support most — a go-to for daily aches.',
        'category' => 'full-spectrum-cbd-topicals',
      ),
      array(
        'heading' => 'CBD Oils & Extracts',
        'desc' => 'Build a steady foundation with a daily routine (morning or evening).',
        'category' => 'full-spectrum-cbd-oil',
      ),
      array(
        'heading' => 'Bundles',
        'desc' => 'Pair formats together and save — great for building a simple routine fast.',
        'category' => 'cbd-products-and-bundles',
      ),
    ),
    'how_to' => array(
      array('title' => 'Start with topical placement', 'text' => 'Apply to the area you feel it most. Use consistently and reapply as needed.'),
      array('title' => 'Add a daily oil routine', 'text' => 'If you want broader support, pair topical use with a consistent oil/extract routine.'),
      array('title' => 'Keep it simple', 'text' => 'Pick one product you’ll actually use daily, then build from there if needed.'),
    ),
    'faq' => array(
      array('q' => 'Do topicals work better than gummies?', 'a' => 'Topicals are great for targeted support where you apply them. Gummies/oils can fit better for daily, whole-body routines.'),
      array('q' => 'How often can I use a topical?', 'a' => 'Follow the label instructions. Many customers apply 1–3 times per day depending on their routine.'),
      array('q' => 'What should I start with?', 'a' => 'If you know where you feel it most, start with a topical. If you want routine-based support, start with an oil.'),
    ),
    'letter' => array(
      'problem' => 'When movement hurts, it’s easy to stop doing the things that keep you strong. The fastest path to confidence is targeted support you’ll actually use — plus a simple daily routine.',
      'promise' => 'Start with where you feel it most (topicals), then add a daily oil routine if you want broader support. Pick one format today — you can build from there.',
      'plan' => array(
        array('title' => 'Day 1', 'text' => 'Apply topical to the area you feel it most. Use it in a repeatable moment (after shower, post-walk, before bed).'),
        array('title' => 'Days 2–5', 'text' => 'Stay consistent. If you want routine-based support, add a daily oil/extract at the same time each day.'),
        array('title' => 'After 1 week', 'text' => 'Keep what’s working. If you’re ready, bundle a second format to simplify your routine.'),
      ),
      'reassurance' => array(
        'Targeted relief where you apply it',
        'Clearly labeled, third-party tested',
        'Simple routines convert to real consistency',
      ),
    ),
  ),

  'brain-health' => array(
    'title' => 'Keep Your Brain Healthy',
    'kicker' => 'Shop by Outcome',
    'subtitle' => 'Daily wellness support for clarity, resilience, and staying sharp.',
    'lede' => 'We’ve curated a clear starting point for focus and brain-friendly routines — choose what’s easiest to take daily, then stay consistent.',
    'hero_image' => get_stylesheet_directory_uri() . '/assets/images/outcome-brain-hero-illus.jpg',
    'benefits' => array(
      'Support clarity and resilience',
      'Thoughtful ingredients you can trust',
      'Made for daily consistency',
    ),
    'trust' => array('Functional ingredients', 'Clearly labeled', 'Third-party tested'),
    'primary_cta' => array('label' => 'Shop Brain & Focus', 'url' => home_url('/outcomes/brain-health/#products')),
    'blocks' => array(
      array(
        'heading' => 'Functional Mushrooms',
        'desc' => 'Customer favorites for focus routines and daily consistency.',
        'category' => 'functional-mushrooms',
      ),
      array(
        'heading' => 'Herbs & Supplements',
        'desc' => 'Support your routine with thoughtfully chosen botanicals.',
        'category' => 'herbal-supplements',
      ),
      array(
        'heading' => 'CBD Oils & Extracts',
        'desc' => 'A steady daily foundation — flexible dosing and simple habit-building.',
        'category' => 'full-spectrum-cbd-oil',
      ),
    ),
    'how_to' => array(
      array('title' => 'Choose a daily anchor', 'text' => 'Pick one product you’ll take at the same time each day (coffee, breakfast, or evening wind-down).'),
      array('title' => 'Support your habits', 'text' => 'Pair products with hydration, protein, and a consistent sleep schedule when possible.'),
      array('title' => 'Stay consistent', 'text' => 'Small daily choices add up. Give your routine time before switching formats.'),
    ),
    'faq' => array(
      array('q' => 'What’s a good “daily starter” choice?', 'a' => 'If you’re building a simple routine, start with one product you’ll take consistently — oils and capsules are common anchors.'),
      array('q' => 'Can I combine mushrooms and CBD?', 'a' => 'Many customers do. Start one product first, then add another after a few days so you can gauge what feels best.'),
      array('q' => 'How soon should I notice results?', 'a' => 'Some people feel effects quickly, but routines are often about consistency over days to weeks. Start low and evaluate gradually.'),
    ),
    'letter' => array(
      'problem' => 'Brain-friendly routines don’t need to be complicated — they need to be consistent. The best product is the one you’ll actually take every day.',
      'promise' => 'Choose a daily “anchor” below (capsules, botanicals, or oil). We’ve curated two best-loved picks per format so you can decide quickly and move on with your day.',
      'plan' => array(
        array('title' => 'Days 1–3', 'text' => 'Pick one anchor and take it at the same time daily (coffee, breakfast, or afternoon reset).'),
        array('title' => 'Days 4–7', 'text' => 'Evaluate gently. Don’t add multiple new products at once — keep it clear and steady.'),
        array('title' => 'After 1 week', 'text' => 'If you want more support, add a second format (e.g., mushroom + oil) after you’ve established the anchor.'),
      ),
      'reassurance' => array(
        'Thoughtfully chosen functional ingredients',
        'Clearly labeled and easy to stick with',
        'Start simple — build only if needed',
      ),
    ),
  ),
);

$data = isset($map[$outcome]) ? $map[$outcome] : null;

if (!$data) {
  status_header(404);
  nocache_headers();
  echo '<main class="lm-shell" style="padding:64px 0"><h1>Page not found</h1><p>This outcome is not available.</p></main>';
  get_footer();
  exit;
}

$title = $data['title'];
$kicker = $data['kicker'];
$subtitle = $data['subtitle'];
$lede = $data['lede'];
$hero_image = $data['hero_image'];
$benefits = (array) $data['benefits'];
$trust = (array) $data['trust'];
$primary_cta = $data['primary_cta'];
$blocks = (array) $data['blocks'];
$how_to = (array) $data['how_to'];
$faq = (array) $data['faq'];
$letter = isset($data['letter']) ? (array) $data['letter'] : array();

?>

<?php
  // Outcome-specific modifier for scoped accent theming (does not affect global layout/typography).
  $outcome_mod = 'lm-outcome--' . preg_replace('/[^a-z0-9\-]/', '', strtolower($outcome));
?>

<main class="lm-shell lm-outcome-page <?php echo esc_attr($outcome_mod); ?>" role="main">
  <section class="lm-outcome-hero" aria-label="Outcome overview">
    <div>
      <p class="lm-outcome-kicker"><?php echo esc_html($kicker); ?></p>
      <h1><?php echo esc_html($title); ?></h1>
      <p class="lm-outcome-sub"><?php echo esc_html($subtitle); ?></p>
      <p class="lm-outcome-lede"><?php echo esc_html($lede); ?></p>

      <div class="lm-outcome-actions">
        <a class="lm-btn--accent" href="#products">Shop recommended</a>
        <a class="lm-btn--ghost" href="<?php echo esc_url(home_url('/shop/')); ?>">Browse all products</a>
      </div>

      <div class="lm-outcome-trust" aria-label="Trust indicators">
        <?php foreach ($trust as $pill) : ?>
          <span class="lm-outcome-pill"><?php echo esc_html($pill); ?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="lm-outcome-hero-art" style="background-image:url('<?php echo esc_url($hero_image); ?>')" aria-hidden="true"></div>
  </section>

  <?php if (!empty($letter)) :
    $lp = isset($letter['problem']) ? $letter['problem'] : '';
    $lpr = isset($letter['promise']) ? $letter['promise'] : '';
    $lplan = isset($letter['plan']) ? (array) $letter['plan'] : array();
    $lre = isset($letter['reassurance']) ? (array) $letter['reassurance'] : array();
  ?>
  <section class="lm-outcome-letter" aria-label="Outcome guidance">
    <div class="lm-outcome-letter-grid">
      <div>
        <h2 class="lm-outcome-letter-title">A simple way to get results</h2>
        <?php if ($lp) : ?><p class="lm-outcome-letter-lede"><?php echo esc_html($lp); ?></p><?php endif; ?>
        <?php if ($lpr) : ?><p class="lm-outcome-letter-promise"><?php echo esc_html($lpr); ?></p><?php endif; ?>

        <?php if (!empty($lplan)) : ?>
          <div class="lm-outcome-plan" aria-label="Suggested plan">
            <h3>Simple 7‑day plan</h3>
            <ol>
              <?php foreach ($lplan as $step) :
                $st = isset($step['title']) ? $step['title'] : '';
                $sx = isset($step['text']) ? $step['text'] : '';
              ?>
                <li>
                  <div class="lm-outcome-plan-head"><?php echo esc_html($st); ?></div>
                  <div class="lm-outcome-plan-text"><?php echo esc_html($sx); ?></div>
                </li>
              <?php endforeach; ?>
            </ol>
          </div>
        <?php endif; ?>
      </div>

      <aside class="lm-outcome-letter-aside" aria-label="Why customers feel safe choosing Laura">
        <h3>Why customers feel confident</h3>
        <?php if (!empty($lre)) : ?>
          <ul class="lm-outcome-checks">
            <?php foreach ($lre as $item) : ?>
              <li><?php echo esc_html($item); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <div class="lm-outcome-letter-cta">
          <a class="lm-btn--accent" href="#products">Shop recommended</a>
          <div class="lm-outcome-letter-note">No pressure — start with one format you’ll use consistently.</div>
        </div>
      </aside>
    </div>
  </section>
  <?php endif; ?>

  <section class="lm-outcome-products" id="products" aria-label="Recommended products">
    <h2>Recommended for <?php echo esc_html($title); ?></h2>
    <p class="lm-outcome-sub">A curated starting point — choose a format that fits your routine.</p>

    <div class="lm-outcome-helper" aria-label="Quick guide">
      <h3>Quick guide</h3>
      <ul>
        <?php
          // Keep this short and actionable — the deeper guidance lives below.
          $q = array_slice($how_to, 0, 3);
          foreach ($q as $item) {
            $qt = isset($item['title']) ? $item['title'] : '';
            $qx = isset($item['text']) ? $item['text'] : '';
            echo '<li><strong>' . esc_html($qt) . ':</strong> ' . esc_html($qx) . '</li>';
          }
        ?>
      </ul>
    </div>

    <nav class="lm-outcome-jump" aria-label="Jump to a format">
      <?php foreach ($blocks as $b) :
        $h = isset($b['heading']) ? $b['heading'] : '';
        $id = 'format-' . sanitize_title($h);
      ?>
        <a class="lm-outcome-jump-pill" href="#<?php echo esc_attr($id); ?>"><?php echo esc_html($h); ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="lm-outcome-grid-2">
      <?php foreach ($blocks as $block) :
        $heading = isset($block['heading']) ? $block['heading'] : '';
        $desc = isset($block['desc']) ? $block['desc'] : '';
        $cat_slug = isset($block['category']) ? $block['category'] : '';
        $cat_url = home_url('/product-category/' . $cat_slug . '/');
      ?>
        <?php $format_id = 'format-' . sanitize_title($heading); ?>
        <div class="lm-outcome-product-block" id="<?php echo esc_attr($format_id); ?>">
          <div style="display:flex; align-items: baseline; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
            <h3><?php echo esc_html($heading); ?></h3>
            <a class="lm-outcome-block-link" href="<?php echo esc_url($cat_url); ?>">Shop <?php echo esc_html($heading); ?> →</a>
          </div>
          <p><?php echo esc_html($desc); ?></p>

          <div class="lm-outcome-recs" aria-label="Curated products">
            <?php
              if (function_exists('wc_get_products')) {
                $products = wc_get_products(array(
                  'status' => 'publish',
                  'limit' => 6,
                  'category' => array($cat_slug),
                  'orderby' => 'popularity',
                  'order' => 'DESC',
                  'return' => 'objects',
                ));

                // Exclude canine/dog CBD items from curated modules.
                // This does not modify product data—only which items are featured.
                $products = array_values(array_filter($products, function($p) {
                  if (!is_object($p) || !method_exists($p, 'get_name')) { return false; }
                  $n = strtolower($p->get_name());
                  return !(strpos($n, 'canine') !== false || strpos($n, 'dog') !== false || strpos($n, 'pet') !== false);
                }));

                if (!empty($products)) {
                  echo '<div class="lm-rec-grid">';
                  $idx = 0;
                  foreach ($products as $product) {
                    $pid = $product->get_id();
                    $permalink = get_permalink($pid);
                    $name = $product->get_name();
                    $img_id = $product->get_image_id();
                    $img_html = $img_id ? wp_get_attachment_image($img_id, 'woocommerce_thumbnail', false, array('loading' => 'lazy', 'decoding' => 'async')) : '';
                    $badge = ($idx === 0) ? 'Most popular' : 'Also popular';
                    $rating = method_exists($product, 'get_average_rating') ? (float) $product->get_average_rating() : 0;
                    $rating_count = method_exists($product, 'get_rating_count') ? (int) $product->get_rating_count() : 0;

                    echo '<a class="lm-rec-card" href="' . esc_url($permalink) . '">';
                      echo '<div class="lm-rec-media">' . ($img_html ?: '<div class="lm-rec-placeholder" aria-hidden="true">🌿</div>') . '</div>';
                      echo '<div class="lm-rec-body">';
                        echo '<div class="lm-rec-meta">';
                          echo '<span class="lm-rec-badge">' . esc_html($badge) . '</span>';
                          if ($rating_count > 0 && $rating > 0) {
                            echo '<span class="lm-rec-rating" aria-label="Rated ' . esc_attr(number_format($rating, 1)) . ' out of 5">';
                              echo '<span class="lm-rec-star" aria-hidden="true">★</span>';
                              echo '<span class="lm-rec-rating-val">' . esc_html(number_format($rating, 1)) . '</span>';
                              echo '<span class="lm-rec-rating-count">(' . esc_html($rating_count) . ')</span>';
                            echo '</span>';
                          }
                        echo '</div>';
                        echo '<div class="lm-rec-title">' . esc_html($name) . '</div>';
                        echo '<div class="lm-rec-cta">View product →</div>';
                      echo '</div>';
                    echo '</a>';

                    $idx++;
                  }
                  echo '</div>';
                } else {
                  echo '<p class="lm-outcome-empty">No curated products are available right now.</p>';
                }
              } else {
                echo '<p class="lm-outcome-empty">Products are currently unavailable.</p>';
              }
            ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p style="margin-top:16px; font-size:12px; color: rgba(0,0,0,0.55);">
      *These statements have not been evaluated by the FDA. Products are not intended to diagnose, treat, cure, or prevent any disease.
    </p>
  </section>

  <section class="lm-outcome-why" aria-label="How to choose">
    <h2>How to choose</h2>
    <div class="lm-card-grid">
      <?php foreach ($how_to as $item) : ?>
        <div class="lm-card">
          <h3><?php echo esc_html($item['title']); ?></h3>
          <p><?php echo esc_html($item['text']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="lm-outcome-side" style="margin-top:18px;">
      <h2>What you’ll love</h2>
      <ul class="lm-outcome-checks">
        <?php foreach ($benefits as $b) : ?>
          <li><?php echo esc_html($b); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <section class="lm-outcome-faq" aria-label="Outcome FAQ">
    <h2 style="margin:0 0 6px;">Questions</h2>
    <?php foreach ($faq as $row) : ?>
      <details class="lm-faq-item">
        <summary><?php echo esc_html($row['q']); ?></summary>
        <div class="lm-faq-body"><p><?php echo esc_html($row['a']); ?></p></div>
      </details>
    <?php endforeach; ?>
  </section>
</main>

<?php get_footer(); ?>
