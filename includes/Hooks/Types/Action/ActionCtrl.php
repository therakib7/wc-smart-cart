<?php
namespace aThemes\WCSmartCart\Hooks\Types\Action;

use aThemes\WCSmartCart\Hooks\Types\Action\Types\WC;

/**
 * WP Action hook
 *
 * @since 0.1.0
 */
class ActionCtrl {

    public function __construct() {
        new WC();
    }
}
