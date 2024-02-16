<?php

namespace aThemes\WCSmartCart\Assets;

use aThemes\WCSmartCart\Helpers\Keys;

/**
 * Asset Manager class.
 *
 * Responsible for managing all of the assets (CSS, JS, Images, Locales).
 * 
 * @since 0.1.0
 */
class Manager
{

    /**
     * Constructor.
     *
     * @since 0.1.0
     */
    public function __construct()
    {
        add_action('init', [$this, 'register_all_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_footer', [$this, 'cart_item_container']);
    }

    /**
     * Register all scripts and styles.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function register_all_scripts()
    {
        $this->register_styles($this->get_styles());
        $this->register_scripts($this->get_scripts());
    }

    /**
     * Get all styles.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function get_styles(): array
    {
        return [
            'wc-smart-cart-app' => [
                'src'     => WC_SMART_CART_BUILD . '/index.css',
                'version' => WC_SMART_CART_VERSION,
                'deps'    => [],
            ],
            'wc-smart-cart' => [
                'src'     => WC_SMART_CART_BUILD . '/wc-smart-cart.css',
                'version' => WC_SMART_CART_VERSION,
                'deps'    => [],
            ],
        ];
    }

    /**
     * Get all scripts.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function get_scripts(): array
    {
        $app_dependency = require_once WC_SMART_CART_DIR . '/build/index.asset.php';

        $smart_cart_dependency = require_once WC_SMART_CART_DIR . '/build/wc-smart-cart.asset.php';
        $smart_cart_dependency['dependencies'][] = 'jquery';

        return [
            'wc-smart-cart-app' => [
                'src'       => WC_SMART_CART_BUILD . '/index.js',
                'version'   => $app_dependency['version'],
                'deps'      => $app_dependency['dependencies'],
                'in_footer' => true,
            ],
            'wc-smart-cart' => [
                'src'       => WC_SMART_CART_BUILD . '/wc-smart-cart.js',
                'version'   => $smart_cart_dependency['version'],
                'deps'      => $smart_cart_dependency['dependencies'],
                'in_footer' => true,
            ],
        ];
    }

    /**
     * Register styles.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function register_styles(array $styles)
    {
        foreach ($styles as $handle => $style) {
            wp_register_style($handle, $style['src'], $style['deps'], $style['version']);
        }
    }

    /**
     * Register scripts.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function register_scripts(array $scripts)
    {
        foreach ($scripts as $handle => $script) {
            wp_register_script($handle, $script['src'], $script['deps'], $script['version'], $script['in_footer']);
        }
    }

    /**
     * Enqueue admin styles and scripts.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueue_admin_assets()
    {
        // Check if we are on the admin page and page=wc-smart-cart.

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!is_admin() || !isset($_GET['page']) || sanitize_text_field(wp_unslash($_GET['page'])) !== 'wc-smart-cart') {
            return;
        }

        wp_enqueue_style('wc-smart-cart-app');
        wp_enqueue_script('wc-smart-cart-app');
    }

    /**
     * Enqueue frontend styles and scripts.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function enqueue_assets()
    {

        // get close time from settings
        $settings = [];
        $option = get_option(Keys::SETTINGS);
        if ($option) {
            $settings = $option;
        } else {
            $settings['close_after'] = 3;
            $settings['display_condition'] = ['all'];
        }

        $display_condition = $settings['display_condition'];

        $enqueue_scripts = in_array('all', $display_condition);

        if ( !$enqueue_scripts ) {
            $is_shop = is_shop();
            $is_product = is_product();
            $is_product_category = is_product_category() || is_tax('product_cat');
            $is_product_tag = is_product_tag() || is_tax('product_tag');
            $is_tax = is_tax();
            $is_cart = is_cart();

            $conditions = [
                'shop' => $is_shop,
                'product-single' => $is_product,
                'product-categories' => $is_product_category,
                'product-archive' => $is_product_category || $is_product_tag || $is_tax,
                'product-tags' => $is_product_tag,
                'product-attributes' => $is_tax,
                'cart' => $is_cart
            ];

            foreach ($display_condition as $condition) {
                if (isset($conditions[$condition]) && $conditions[$condition]) {
                    $enqueue_scripts = true;
                    break;
                }
            }
        }

        // Enqueue the script if it matches the display condition
        if ( $enqueue_scripts ) {

            wp_enqueue_style('wc-smart-cart');
            wp_enqueue_script('wc-smart-cart');

            // Localize the script with the AJAX URL
            $nonce = wp_create_nonce('_wc_smart_cart_nonce');

            $close_after = (int) apply_filters('wc_smart_cart_close_after', $settings['close_after']);
            $close_after *= 1000; // convert to miliseconds

            wp_localize_script(
                'wc-smart-cart',
                'wc_smart_cart_params',
                [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce'    => $nonce,
                    'close_after' => $close_after
                ]
            );
        }
    }

    /**
     * Cart item container
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function cart_item_container()
    {
        echo '<div id="wc-smart-cart-container"></div>';
    }
}
