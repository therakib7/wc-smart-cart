<?php

namespace aThemes\WCSmartCart\Abstracts;

use WP_Error;
use WP_REST_Controller;

/**
 * Rest Controller base class.
 *
 * @since 0.1.0
 */
abstract class RestApi extends WP_REST_Controller {


    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'wc-smart-cart/v1';

    /**
     * Endpoint base
     *
     * @var string
     */
    protected $base = '';

    /**
     * Check default permission for rest routes.
     *
     * @since 0.1.0
     *
     * @return bool
     */

    public function permission( $req ) {
        // You can access parameters from the $req object
        // $param = $req->get_param('param');

        // Implement your permission check logic here
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }

        return new WP_Error(
            'rest_forbidden',
            esc_html__( 'Sorry, you are not allowed to do that.', 'wc-smart-cart' ),
            [
                'status' => is_user_logged_in() ? 403 : 401,
            ]
        );
    }
}
