<?php
/**
 * The main template file.
 * This handles the "Native" parts of the site (Checkout, Cart, etc.)
 */
if (!defined('ABSPATH')) exit;
get_header();
?>

    <main class="<?php echo lm_is_react_page() ? '' : 'woocommerce-container'; ?>">
        <?php
        if (have_posts()) {
            // Check if we are on a React page
            if (lm_is_react_page()) {
                 echo '<div id="lm-react-root"></div>';
            } 
            // Check if we are on a WooCommerce page (Shop, Product, Category, Tag)
            elseif (function_exists('is_woocommerce') && is_woocommerce()) {
                woocommerce_content();
            }
            // Standard WordPress Post/Page (Fallback for Cart/Checkout if not caught above, though WC usually handles them via shortcodes in the_content)
            else {
                while (have_posts()) {
                    the_post();
                    the_content();
                }
            }
        }
        ?>
    </main>

<?php get_footer(); ?>
