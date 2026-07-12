<?php
/**
 * Manual class loader (no Composer, no PSR-4 autoload).
 *
 * Loads plugin classes with explicit require_once so the plugin works without
 * any build/install step and stays easy to trace.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Loads plugin class files in dependency order.
 */
final class Loader {

	/**
	 * Bootstrap loading: core, helpers, hooks (+ admin pages in wp-admin).
	 *
	 * Widget classes are loaded later (only the enabled ones, and only after
	 * Elementor is ready) via load_widget_classes().
	 *
	 * @return void
	 */
	public static function init() {
		self::load_core_classes();
		self::load_helper_classes();
		self::load_hook_classes();

		if ( is_admin() ) {
			self::load_admin_classes();
		}
	}

	/**
	 * Require a list of class files relative to includes/.
	 *
	 * @param array $relative_paths Paths relative to the includes/ directory.
	 * @return void
	 */
	private static function require_files( array $relative_paths ) {
		foreach ( $relative_paths as $relative ) {
			$path = FWFE_DIR . 'includes/' . $relative;
			if ( is_readable( $path ) ) {
				require_once $path;
			}
		}
	}

	/**
	 * Core classes (Plugin already references these).
	 *
	 * @return void
	 */
	private static function load_core_classes() {
		self::require_files(
			array(
				'Core/Assets.php',
				'Core/Plugin.php',
			)
		);
	}

	/**
	 * Helper utility classes.
	 *
	 * @return void
	 */
	private static function load_helper_classes() {
		self::require_files(
			array(
				'Helpers/Helper.php',
				'Helpers/Icon.php',
				'Helpers/Image.php',
				'Helpers/Link.php',
			)
		);
	}

	/**
	 * Hook classes (admin/frontend/elementor wiring).
	 *
	 * @return void
	 */
	private static function load_hook_classes() {
		self::require_files(
			array(
				'Hooks/Admin.php',
				'Hooks/Frontend.php',
				'Hooks/Elementor.php',
			)
		);
	}

	/**
	 * Admin page classes (loaded only in wp-admin).
	 *
	 * @return void
	 */
	private static function load_admin_classes() {
		self::require_files(
			array(
				'Admin/Dashboard.php',
				'Admin/Widgets_Manager.php',
				'Admin/Demo_Library.php',
				'Admin/Settings.php',
				'Admin/System_Info.php',
			)
		);
	}

	/**
	 * Require the Widget.php file for a single widget slug.
	 *
	 * Must run after Elementor is loaded (widgets extend a base class).
	 *
	 * @param string $slug Widget slug (e.g. icon-box).
	 * @return string Fully-qualified class name (may not exist if file missing).
	 */
	public static function load_widget_class( $slug ) {
		// Base class extends Elementor's Widget_Base, so it must load lazily
		// (only after Elementor is ready) — and before any widget file.
		self::require_files( array( 'Base/Widget_Base.php' ) );

		$pascal = self::slug_to_pascal( $slug );
		self::require_files( array( 'Widgets/' . $pascal . '/Widget.php' ) );
		return 'FWFE\\Widgets\\' . $pascal . '\\Widget';
	}

	/**
	 * Convert a hyphenated slug to a PascalCase namespace segment.
	 *
	 * Example: flip-box -> FlipBox
	 *
	 * @param string $slug Widget slug.
	 * @return string
	 */
	public static function slug_to_pascal( $slug ) {
		return str_replace( ' ', '', ucwords( str_replace( '-', ' ', $slug ) ) );
	}
}
