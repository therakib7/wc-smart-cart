<?php
/**
 * aThemes - A smart cart plugin for WooCommerce
 *
 * @package    aThemes - WCSmartCart
 * @author     aThemes <therakib7@gmail.com>
 * @link       https://athemes.com
 * @copyright  2023 aThemes
 *
 * @wordpress-plugin
 * Plugin Name:       Smart Cart for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/wc-smart-cart
 * Description:       A smart cart plugin for WooCommerce
 * Version:           0.1.0
 * Author:            aThemes
 * Author URI:        https://athemes.com
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Tested up to:      6.4
 * Text Domain:       wc-smart-cart
 * Domain Path:       /languages
 * License: GPL3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Don't allow call the file directly
 *
 * @since 0.1.0
 */
defined( 'ABSPATH' ) || exit;

/**
 * WCSmartCart Final Class
 *
 * @since 0.1.0
 *
 * @class WCSmartCart The class that holds the entire WCSmartCart plugin
 */

final class WCSmartCart {

    /**
     * Instance of self
	 *
     * @since 0.1.0
	 *
     * @var WCSmartCart
     */
    private static $instance = null;

    /**
     * Plugin version
     *
     * @since 0.1.0
	 *
     * @var string
     */
    private const VERSION = '0.1.0';

    /**
     * Plugin slug.
     *
     * @var string
     *
     * @since 0.1.0
     */
    const SLUG = 'wc-smart-cart';

    /**
     * Holds various class instances.
     *
     * @var array
     *
     * @since 0.1.0
     */
    private $container = [];

    /**
     * Minimum PHP version required
	 *
     * @since 0.1.0
	 *
     * @var string
     */
    private const MIN_PHP = '7.4';

    /**
     * Constructor for the WCSmartCart class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        require_once __DIR__ . '/vendor/autoload.php';

        $this->define_constants();

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

		add_action( 'wp_loaded', [ $this, 'flush_rewrite_rules' ] );

		$this->init_plugin();
    }

	/**
     * Initializes the WCSmartCart() class
     *
     * Checks for an existing WCSmartCart() instance
     * and if it doesn't find one, create it.
     */
    public static function init() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @since 0.1.0
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @since 0.1.0
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define all constants
	 *
     * @since 0.1.0
	 *
     * @return void
     */
    public function define_constants() {
        define( 'WC_SMART_CART_VERSION', self::VERSION );
        define( 'WC_SMART_CART_SLUG', self::SLUG );
        define( 'WC_SMART_CART_FILE', __FILE__ );
        define( 'WC_SMART_CART_DIR', __DIR__ );
        define( 'WC_SMART_CART_PATH', plugin_dir_path( WC_SMART_CART_FILE ) );
        define( 'WC_SMART_CART_URL', plugins_url( '', WC_SMART_CART_FILE ) );
        define( 'WC_SMART_CART_TEMPLATE_PATH', WC_SMART_CART_PATH . '/templates' );
        define( 'WC_SMART_CART_BUILD', WC_SMART_CART_URL . '/build' );
        define( 'WC_SMART_CART_ASSETS', WC_SMART_CART_URL . '/assets' );
    }

	/**
     * Load the plugin after all plugins are loaded.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function init_plugin() {

		do_action( 'wc_smart_cart_before_init' );

        $this->includes();
        $this->init_hooks();

		// Fires after the plugin is loaded.
        do_action( 'wc_smart_cart_init' );
    }

	/**
     * Include the required files.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function includes() {
        if ( $this->is_request( 'admin' ) ) {
            $this->container['installer'] = new aThemes\WCSmartCart\Setup\Installer();
            $this->container['admin_menu'] = new aThemes\WCSmartCart\Admin\Menu();
        }

        $this->container['assets']   = new aThemes\WCSmartCart\Assets\Manager();
        $this->container['rest_api'] = new aThemes\WCSmartCart\Api\Controller();
        $this->container['ajax'] = new aThemes\WCSmartCart\Ajax\Manager();
        $this->container['hooks'] = new aThemes\WCSmartCart\Hooks\Manager();
    }

	/**
     * Initialize the hooks.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function init_hooks() {

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );
    }

	/**
     * Initialize plugin for localization
     *
     * @since 0.1.0
     *
     * @uses load_plugin_textdomain()
	 *
	 * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'wc-smart-cart', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        // Load the React-pages translations.
        if ( is_admin() ) {
            // Load wp-script translation for wc-smart-cart-app
            wp_set_script_translations( 'wc-smart-cart-app', 'wc-smart-cart', plugin_dir_path( __FILE__ ) . 'languages/' );
        }
    }

	/**
     * Activating the plugin.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function activate() {
        // Run the installer to create necessary migrations and seeders.
    }

    /**
     * Placeholder for deactivation function.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function deactivate() {
        // Remove unnecessary data when deactivate
    }

	/**
     * Flush rewrite rules after plugin is activated.
     *
     * Nothing being added here yet.
     *
     * @since 0.1.0
	 *
	 * @return void
     */
    public function flush_rewrite_rules() {
        // fix rewrite rules
    }

    /**
     * What type of request is this?
     *
     * @param string $type admin, ajax, cron or public.
     *
     * @since 0.1.0
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin':
                return is_admin();

            case 'ajax':
                return defined( 'DOING_AJAX' );

            case 'rest':
                return defined( 'REST_REQUEST' );

            case 'cron':
                return defined( 'DOING_CRON' );

            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }
}

/**
 * Initialize the main plugin.
 *
 * @since 0.1.0
 *
 * @return WCSmartCart class
 */
function wc_smart_cart() {
    return WCSmartCart::init();
}
wc_smart_cart(); // Run WCSmartCart Plugin
