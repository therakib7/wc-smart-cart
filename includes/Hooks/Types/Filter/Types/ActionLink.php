<?php
namespace aThemes\WCSmartCart\Hooks\Types\Filter\Types;

class ActionLink {

    public function __construct() {
        add_filter( 'plugin_action_links_' . plugin_basename( WC_SMART_CART_FILE ), [ $this, 'links' ] );
    }

    /**
	 * Assist links.
	 *
	 * @since 0.1.0
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public function links( $links ) {
		$links[] = '<a target="_blank" href="' . esc_url( 'https://athemes.com/wc-smart-cart/docs' ) . '">' . esc_html__( 'Documentation', 'wc-smart-cart' ) . '</a>';

		if ( array_key_exists( 'deactivate', $links ) ) {
            $links['deactivate'] = str_replace( '<a', '<a class="wc-smart-cart-deactivate-link"', $links['deactivate'] );
        }
		return $links;
	}
}
