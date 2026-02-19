<?php
/**
 * WooCommerce Template
 * Handles the layout for Shop, Product, Category, and Tag pages.
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<main class="lm-main">
    <div class="lm-shell">
        <div class="woocommerce-container" style="padding: 40px 0;">
            <?php woocommerce_content(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
