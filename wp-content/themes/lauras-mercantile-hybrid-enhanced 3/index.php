<?php
/**
 * The main template file.
 * This handles the "Native" parts of the site (Checkout, Cart, etc.)
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

    <?php 
    if (function_exists('wp_body_open')) {
        wp_body_open();
    } else {
        do_action('wp_body_open');
    }
    ?>

    <main class="<?php echo lm_is_react_page() ? '' : 'woocommerce-container'; ?>">
        <?php
        if (have_posts()) {
            // Check if we are on a React page
            if (lm_is_react_page()) {
                 echo '<div id="root"></div>';
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

    <?php if (!lm_is_react_page()) : ?>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-col" style="display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <span class="footer-logo">Laura's Mercantile</span>
                        <p class="footer-tagline">
                            Cultivating health through nature, science, and regenerative farming.
                        </p>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Shop</h4>
                    <ul class="footer-links">
                        <li><a href="/shop/sleep">Sleep</a></li>
                        <li><a href="/shop/relief">Relief</a></li>
                        <li><a href="/shop/energy">Energy</a></li>
                        <li><a href="/shop/focus">Focus</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="/contact-us">Contact Us</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="/shipping-returns">Shipping & Returns</a></li>
                        <li><a href="/store-policy">Store Policy</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:hello@laurasmercantile.com">hello@laurasmercantile.com</a></li>
                        <li><a href="tel:1-859-744-2731">+1 (859) 744-2731</a></li>
                        <li><span>Winchester, KY</span></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; <?php echo date('Y'); ?> Laura's Mercantile. All rights reserved. <br/>
                <span style="opacity: 0.6; font-size: 0.8em">Designed with care.</span>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    <?php wp_footer(); ?>
</body>
</html>
