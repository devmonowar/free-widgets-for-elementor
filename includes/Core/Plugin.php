<?php
/**
 * Main plugin orchestrator (singleton).
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Core;

use FWFE\Helpers\Helper;
use FWFE\Hooks\Admin;
use FWFE\Hooks\Frontend;
use FWFE\Hooks\Elementor as Elementor_Hooks;

defined( 'ABSPATH' ) || exit;

/**
 * Boots the plugin and wires up the hook layer.
 */
final class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor (use instance()).
	 */
	private function __construct() {}

	/**
	 * Run the plugin: register the hook layer.
	 *
	 * @return void
	 */
	public function run() {
		// Admin UI always loads (dashboard, settings, notices).
		if ( is_admin() ) {
			( new Admin() )->register();
		}

		// Everything below requires a compatible Elementor.
		if ( ! $this->is_elementor_compatible() ) {
			add_action( 'admin_notices', array( $this, 'elementor_missing_notice' ) );
			return;
		}

		( new Frontend() )->register();
		( new Elementor_Hooks() )->register();
	}

	/**
	 * Whether Elementor is loaded and meets the minimum version.
	 *
	 * @return bool
	 */
	private function is_elementor_compatible() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return false;
		}
		if ( defined( 'ELEMENTOR_VERSION' ) && ! version_compare( ELEMENTOR_VERSION, FWFE_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Admin notice shown when Elementor is missing or outdated.
	 *
	 * @return void
	 */
	public function elementor_missing_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$message = sprintf(
			/* translators: 1: plugin name, 2: required Elementor version. */
			esc_html__( '%1$s requires Elementor (version %2$s or higher) to be installed and activated.', 'free-widgets-for-elementor' ),
			'<strong>' . esc_html__( 'Free Widgets For Elementor', 'free-widgets-for-elementor' ) . '</strong>',
			esc_html( FWFE_MINIMUM_ELEMENTOR_VERSION )
		);

		printf( '<div class="notice notice-warning"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Activation: seed default settings without clobbering existing ones.
	 *
	 * @return void
	 */
	public static function activate() {
		$existing = get_option( Helper::OPTION_KEY, null );

		if ( ! is_array( $existing ) ) {
			$defaults                   = Helper::default_settings();
			$defaults['install_time']   = time();
			$defaults['plugin_version'] = FWFE_VERSION;
			add_option( Helper::OPTION_KEY, $defaults );
		} else {
			$existing['plugin_version'] = FWFE_VERSION;
			update_option( Helper::OPTION_KEY, $existing );
		}
	}

	/**
	 * Deactivation: clear cache/transients only. No data deletion.
	 *
	 * @return void
	 */
	public static function deactivate() {
		self::clear_cache();
	}

	/**
	 * Delete the plugin's transients.
	 *
	 * @return void
	 */
	public static function clear_cache() {
		delete_transient( 'fwfe_system_info' );
	}
}
