<?php
/**
 * Admin hooks: menu registration, admin assets, save handlers.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Hooks;

use FWFE\Core\Assets;
use FWFE\Admin\Dashboard;
use FWFE\Admin\Settings;
use FWFE\Admin\Widgets_Manager;
use FWFE\Admin\System_Info;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the plugin's admin menu, pages and asset/save hooks.
 */
final class Admin {

	/**
	 * Top-level menu slug.
	 */
	const MENU_SLUG = 'free-widgets-for-elementor';

	/**
	 * Register admin hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// "Settings" link on the Plugins list row.
		add_filter( 'plugin_action_links_' . FWFE_BASENAME, array( $this, 'action_links' ) );

		// Form/action handlers (admin-post.php).
		add_action( 'admin_post_fwfe_save_settings', array( '\FWFE\Admin\Settings', 'handle_save' ) );
		add_action( 'admin_post_fwfe_clear_cache', array( '\FWFE\Admin\Settings', 'handle_clear_cache' ) );
	}

	/**
	 * Add a "Settings" action link to the plugin's row on the Plugins screen.
	 *
	 * @param array $links Existing action links.
	 * @return array
	 */
	public function action_links( $links ) {
		$settings = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( admin_url( 'admin.php?page=' . Settings::SLUG ) ),
			esc_html__( 'Settings', 'free-widgets-for-elementor' )
		);
		array_unshift( $links, $settings );
		return $links;
	}

	/**
	 * Register the top-level menu and sub-pages.
	 *
	 * @return void
	 */
	public function register_menu() {
		add_menu_page(
			esc_html__( 'Free Widgets', 'free-widgets-for-elementor' ),
			esc_html__( 'Free Widgets', 'free-widgets-for-elementor' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'render_dashboard' ),
			'dashicons-screenoptions',
			59
		);

		add_submenu_page(
			self::MENU_SLUG,
			esc_html__( 'Dashboard', 'free-widgets-for-elementor' ),
			esc_html__( 'Dashboard', 'free-widgets-for-elementor' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'render_dashboard' )
		);

		add_submenu_page(
			self::MENU_SLUG,
			esc_html__( 'Widgets', 'free-widgets-for-elementor' ),
			esc_html__( 'Widgets', 'free-widgets-for-elementor' ),
			'manage_options',
			Widgets_Manager::SLUG,
			array( $this, 'render_widgets' )
		);

		add_submenu_page(
			self::MENU_SLUG,
			esc_html__( 'Settings', 'free-widgets-for-elementor' ),
			esc_html__( 'Settings', 'free-widgets-for-elementor' ),
			'manage_options',
			Settings::SLUG,
			array( $this, 'render_settings' )
		);

		add_submenu_page(
			self::MENU_SLUG,
			esc_html__( 'System Info', 'free-widgets-for-elementor' ),
			esc_html__( 'System Info', 'free-widgets-for-elementor' ),
			'manage_options',
			System_Info::SLUG,
			array( $this, 'render_system_info' )
		);

		add_submenu_page(
			self::MENU_SLUG,
			esc_html__( 'Documentation', 'free-widgets-for-elementor' ),
			esc_html__( 'Documentation', 'free-widgets-for-elementor' ),
			'manage_options',
			'https://wordpress.org/plugins/free-widgets-for-elementor/'
		);

		add_submenu_page(
			self::MENU_SLUG,
			esc_html__( 'Support', 'free-widgets-for-elementor' ),
			esc_html__( 'Support', 'free-widgets-for-elementor' ),
			'manage_options',
			'https://wordpress.org/support/plugin/free-widgets-for-elementor/'
		);
	}

	/**
	 * Render callbacks (delegate to page classes).
	 */

	/**
	 * Render the dashboard page.
	 *
	 * @return void
	 */
	public function render_dashboard() {
		( new Dashboard() )->render();
	}

	/**
	 * Render the widgets manager page.
	 *
	 * @return void
	 */
	public function render_widgets() {
		( new Widgets_Manager() )->render();
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function render_settings() {
		( new Settings() )->render();
	}

	/**
	 * Render the system info page.
	 *
	 * @return void
	 */
	public function render_system_info() {
		( new System_Info() )->render();
	}

	/**
	 * Enqueue admin assets on the plugin's own pages only.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	public function enqueue( $hook_suffix ) {
		if ( false === strpos( (string) $hook_suffix, 'free-widgets' ) && false === strpos( (string) $hook_suffix, 'fwfe-' ) ) {
			return;
		}
		Assets::enqueue_admin();
	}
}
