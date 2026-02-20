<?php
/**
 * WooCommerce Template
 * This template handles all WooCommerce pages (Shop, Single Product, etc.)
 * it ensures we don't use the blog-focused singular.php for products.
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<main id="main" class="lm-main">
    <div class="lm-shell">
        <div class="woocommerce">
            <?php woocommerce_content(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
