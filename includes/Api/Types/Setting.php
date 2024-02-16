<?php

namespace aThemes\WCSmartCart\Api\Types;

use aThemes\WCSmartCart\Abstracts\RestApi;
use aThemes\WCSmartCart\Helpers\Keys;

/**
 * API Setting class.
 *
 * @since 0.1.0
 */

class Setting extends RestApi {

    /**
     * Route base.
     *
     * @var string
     *
     * @since 0.1.0
     */
    protected $base = 'settings';

    /**
     * Register all routes related with api.
     *
     * @since 0.1.0
     *
     * @return void
     */

    public function routes() {
        register_rest_route(
            $this->namespace, '/' . $this->base,
            [
                'methods' => 'GET',
                'callback' => [ $this, 'get' ],
                'permission_callback' => [ $this, 'permission' ],
            ]
        );

        register_rest_route(
            $this->namespace, '/' . $this->base,
            [
                'methods' => 'POST',
                'callback' => [ $this, 'create' ],
                'permission_callback' => [ $this, 'permission' ],
            ]
        );
    }

    /**
     * Get settings data based on the provided tab.
     *
     * @since 0.1.0
     *
     * @param \WP_REST_Request $req Request object.
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get( $req ) {
        $param = $req->get_params();
        $wp_err = new \WP_Error();

        if ( $wp_err->get_error_messages() ) {
            return new \WP_REST_Response(
                [
					'success'  => false,
					'data' => $wp_err->get_error_messages(),
                ], 200
            );
        } else {
            $settings = [];
            $option = get_option( Keys::SETTINGS );

            if ( $option ) {
                $settings = $option;
            } else {
                $settings['layout'] = 'one';
                $settings['position'] = 'top';
                $settings['close_after'] = '3';
                $settings['display_condition'] = [];
            }

            return new \WP_REST_Response(
                [
					'success'  => true,
					'data' => [ 'form' => $settings ],
                ], 200
            );
        }
    }

    /**
     * Update settings data based on the provided tab.
     *
     * @since 0.1.0
     *
     * @param \WP_REST_Request $req Request object.
     *
     * @return WP_Error|WP_REST_Response
     */
    public function create( $req ) {
        $param = $req->get_params();
        $wp_err = new \WP_Error();

        if ( isset( $param['close_after'] ) && empty( $param['close_after'] ) ) {
            $wp_err->add(
                'field',
                esc_html__( 'Close after is missing', 'wc-smart-cart' )
            );
        }

        if ( isset( $param['display_condition'] ) && empty( $param['display_condition'] ) ) {
            $wp_err->add(
                'field',
                esc_html__( 'Display condition is missing', 'wc-smart-cart' )
            );
        }

        if ( $wp_err->get_error_messages() ) {
            return new \WP_REST_Response(
                [
					'success'  => false,
					'data' => $wp_err->get_error_messages(),
                ], 200
            );
        } else {
            $settings = [];

            $settings['layout'] = isset( $param['layout'] )
                ? $this->input_sanitize( $param['layout'] )
                : 'one';
            $settings['position'] = isset( $param['position'] )
                ? $this->input_sanitize( $param['position'] )
                : 'one';
            $settings['close_after'] = isset( $param['close_after'] )
                ? $this->input_sanitize( $param['close_after'] )
                : 'one';
            $settings['display_condition'] = isset( $param['display_condition'] )
                ? $this->input_sanitize( $param['display_condition'], 'map' )
                : 'one';

            update_option( Keys::SETTINGS, $settings );

            return new \WP_REST_Response(
                [
					'success'  => true,
                ], 200
            );
        }
    }
}
