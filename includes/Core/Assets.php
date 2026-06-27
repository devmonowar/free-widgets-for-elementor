<?php
/**
 * Asset registration. No build step: plain CSS/JS straight from assets/.
 *
 * Cache-busting uses the plugin version (or time() while WP_DEBUG is on, so
 * edits show up instantly during development).
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Core;

use FWFE\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Registers (not enqueues) per-widget assets so Elementor can load them on
 * demand via each widget's get_style_depends() / get_script_depends().
 */
final class Assets {

	/**
	 * Shared frontend base style handle (CSS variables + resets). Widget styles
	 * depend on it, so it loads only on pages that actually use a widget.
	 */
	const BASE_STYLE = 'fwfe-frontend';

	/**
	 * Build a widget asset handle.
	 *
	 * @param string $slug Widget slug.
	 * @param string $type 'css' or 'js'.
	 * @return string
	 */
	public static function handle( $slug, $type ) {
		return 'fwfe-' . $slug . '-' . $type;
	}

	/**
	 * Version string for cache-busting.
	 *
	 * @return string
	 */
	public static function version() {
		return ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? (string) time() : FWFE_VERSION;
	}

	/**
	 * Register the shared base style and every widget's CSS/JS.
	 *
	 * Hooked on wp_enqueue_scripts (front end + Elementor preview). Registration
	 * only — Elementor enqueues per-widget through *_depends().
	 *
	 * @return void
	 */
	public static function register_frontend() {
		$version = self::version();

		// Shared base: CSS variables, resets, helper classes.
		if ( is_readable( FWFE_DIR . 'assets/css/frontend.css' ) ) {
			wp_register_style(
				self::BASE_STYLE,
				FWFE_URL . 'assets/css/frontend.css',
				array(),
				$version
			);
		}

		foreach ( array_keys( Helper::widget_registry() ) as $slug ) {
			$css = 'assets/css/widgets/' . $slug . '.css';
			$js  = 'assets/js/widgets/' . $slug . '.js';

			if ( is_readable( FWFE_DIR . $css ) ) {
				wp_register_style(
					self::handle( $slug, 'css' ),
					FWFE_URL . $css,
					array( self::BASE_STYLE ),
					$version
				);
			}

			if ( is_readable( FWFE_DIR . $js ) ) {
				wp_register_script(
					self::handle( $slug, 'js' ),
					FWFE_URL . $js,
					array(),
					$version,
					true
				);
			}
		}
	}

	/**
	 * Enqueue the admin stylesheet/script on the plugin's own admin pages.
	 *
	 * @return void
	 */
	public static function enqueue_admin() {
		$version = self::version();

		if ( is_readable( FWFE_DIR . 'assets/css/admin.css' ) ) {
			wp_enqueue_style( 'fwfe-admin', FWFE_URL . 'assets/css/admin.css', array(), $version );
		}
		if ( is_readable( FWFE_DIR . 'assets/js/admin.js' ) ) {
			wp_enqueue_script( 'fwfe-admin', FWFE_URL . 'assets/js/admin.js', array(), $version, true );
		}
	}

	/**
	 * Enqueue editor-only styles inside the Elementor editor.
	 *
	 * @return void
	 */
	public static function enqueue_editor() {
		if ( is_readable( FWFE_DIR . 'assets/css/editor.css' ) ) {
			wp_enqueue_style( 'fwfe-editor', FWFE_URL . 'assets/css/editor.css', array(), self::version() );
		}
	}
}
