<?php
namespace aThemes\WCSmartCart\Ajax\Types;

/**
 * WCCart class.
 *
 * Responsible for managing the cart content
 *
 * @since 0.1.0
 */
class WCCart {

	public function __construct() {
        add_action( 'wp_ajax_wc_smart_cart_content', [ $this, 'get_cart_content' ] );
        add_action( 'wp_ajax_nopriv_wc_smart_cart_content', [ $this, 'get_cart_content' ] );
	}

    /**
     * Get wc cart content
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function get_cart_content() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), '_wc_smart_cart_nonce' ) ) {
            wp_die( 'Unauthorized request.' );
        }

        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        // Get cart contents
        ob_start();
        require_once WC_SMART_CART_TEMPLATE_PATH . '/smart-cart.php';
        $mini_cart = ob_get_clean();

        // Return cart content
        wp_send_json_success( $mini_cart );
            wp_send_json_success( $mini_cart );
            wp_die();
    }
}
