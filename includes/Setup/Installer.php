<?php

namespace aThemes\WCSmartCart\Setup;

use aThemes\WCSmartCart\Helpers\Keys;

/**
 * Class Installer.
 *
 * Install necessary database tables and options for the plugin.
 *
 * @since 0.1.0
 */
class Installer {


    public function __construct() {
        add_action( 'admin_init', [ $this, 'run' ] );
        add_action( 'admin_init', [ $this, 'check_wc_plugin' ] );
    }

    /**
     * Run the installer.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function run(): void {
        $version = get_option( Keys::VERSION, '0.0.0' );
        if ( version_compare( $version, WC_SMART_CART_VERSION, '<' ) ) {
            // Update the installed version.
            $this->add_version();

            $this->run_migrations();
            $this->run_seeders();
        }
    }

    /**
     * Add time and version.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function add_version(): void {
        $installed = get_option( Keys::INSTALLED_AT );

        if ( ! $installed ) {
            update_option( Keys::INSTALLED_AT, current_datetime()->format( 'Y-m-d H:i:s' ) );
        }

        update_option( Keys::VERSION, WC_SMART_CART_VERSION );
    }

    /**
     * Create necessary database tables.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function run_migrations() {
        // Run the database migrations.
    }

    /**
     * Seed necessary database data.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function run_seeders() {
        // Run the database seeders.
    }

    /**
     * Check WooCommerce plugin is Activated
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function check_wc_plugin() {
        // Get the list of active plugins for the current site
        $site_plugins = (array) get_option( 'active_plugins' );

        // Get the network-activated plugins
        $network_activated_plugins = (array) get_site_option( 'active_sitewide_plugins' );

        // Combine the lists of active plugins from the current site and network-activated plugins
        $all_active_plugins = array_unique( array_merge( $site_plugins, array_keys( $network_activated_plugins ) ) );

        if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', $all_active_plugins ), true ) ) {
            add_action(
                'admin_notices', function () {

					$class = 'notice notice-error';
					$dependency_plugin = esc_html__( 'WooCommerce', 'wc-smart-cart' );
					$current_plugin = esc_html__( 'Smart Cart for WooCommerce', 'wc-smart-cart' );
					$slug = 'woocommerce';
					$message = '';
					$url = '';
					$button_text = '';

					if ( ! $this->is_plugin_installed( sprintf( '%1$s/%1$s.php', $slug ) ) && current_user_can( 'install_plugins' ) ) {
						$message = esc_html__( 'requires WooCommerce plugin, Which is currently NOT INSTALLED.', 'wc-smart-cart' );
						$url = wp_nonce_url(
                            self_admin_url( sprintf( 'update.php?action=install-plugin&plugin=%s', $slug ) ),
                            sprintf( 'install-plugin_%s', $slug )
						);
						$button_text = esc_html__( 'Install', 'wc-smart-cart' );
					}

					if ( $this->is_plugin_installed( sprintf( '%1$s/%1$s.php', $slug ) ) && ! is_plugin_active( sprintf( '%1$s/%1$s.php', $slug ) ) && current_user_can( 'activate_plugins' ) ) {
						$message = esc_html__( 'requires WooCommerce plugin, Which is currently NOT RUNNING.', 'wc-smart-cart' );
						$url = wp_nonce_url(
                            admin_url( sprintf( 'plugins.php?action=activate&plugin=%1$s/%1$s.php', $slug ) ),
                            sprintf( 'activate-plugin_%1$s/%1$s.php', $slug )
						);
						$button_text = esc_html__( 'Activate', 'wc-smart-cart' );
					}

                    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( ! isset( $_GET['action'] ) || sanitize_text_field( wp_unslash( $_GET['action'] ) ) !== 'install-plugin' ) {
						printf(
                            '
                        <div class="%1$s">
                            <p><strong>%6$s</strong> %3$s</p>
                            <p><a class="button button-primary" href="%4$s">%5$s %2$s</a></p>
                        </div>',
                            $class,
                            $dependency_plugin,
                            $message,
                            $url,
                            $button_text,
                            $current_plugin
						);
					}
				}
            );
        }
    }

    /**
     * Helper function to check if a plugin is installed
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function is_plugin_installed( $plugin ) {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        return array_key_exists( $plugin, $all_plugins );
    }
}
