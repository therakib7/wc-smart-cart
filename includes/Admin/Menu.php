<?php

namespace aThemes\WCSmartCart\Admin;

/**
 * Admin Menu class.
 *
 * Responsible for managing admin menus.
 *
 * @since 0.1.0
 */
class Menu {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'init_menu' ] );
    }

    public function init_menu() {

        $slug          = WC_SMART_CART_SLUG;
        $menu_position = 75;
        $capability    = 'manage_options';

        add_menu_page(
            esc_html__( 'WooCommerce Smart Cart', 'wc-smart-cart' ),
            esc_html__( 'WC Smart Cart', 'wc-smart-cart' ),
            $capability,
            $slug,
            [ $this, 'render' ],
            'dashicons-cart',
            $menu_position
        );
    }

    /**
     * Render the plugin page
     *
     * @since 0.1.0
     */
    public function render() {
        require_once WC_SMART_CART_TEMPLATE_PATH . '/app.php';
    }
}
