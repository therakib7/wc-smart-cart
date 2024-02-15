<?php

namespace aThemes\WCSmartCart\Ajax;

use aThemes\WCSmartCart\Ajax\Types\{
    WCCart
};

/**
 * Class Ajax Manager
 *
 * Manager for registering ajax request.
 *
 * @since 0.1.0
 */
class Manager {

    /**
     * Ajax Manager constructor.
     *
     * @since 0.1.0
     */
    public function __construct() {
        new WCCart();
    }
}
