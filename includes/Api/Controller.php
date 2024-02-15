<?php

namespace aThemes\WCSmartCart\Api;

use aThemes\WCSmartCart\Api\Types\{
    Todo,
    Setting,
};

/**
 * Class Api Controller
 *
 * Controller for registering custom REST API endpoints.
 *
 * @since 0.1.0
 */
class Controller {

    /**
     * Class dir and class name mapping.
     *
     * @var array
     *
     * @since 0.1.0
     */
    protected $class_map;

    /**
     * ApiCtrl constructor.
     *
     * @since 0.1.0
     */
    public function __construct() {
        // Register custom REST API endpoints
        if ( ! class_exists( 'WP_REST_Server' ) ) {
            return;
        }

        $this->class_map = apply_filters(
            'wc_smart_cart_rest_api_class_map',
            [
                Todo::class,
                Setting::class,
            ]
        );

        // Init REST API routes.
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ], 10 );
    }

    /**
     * Register REST API routes.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function register_rest_routes(): void {
        foreach ( $this->class_map as $controller ) {
            $this->$controller = new $controller();
            $this->$controller->routes();
        }
    }
}
