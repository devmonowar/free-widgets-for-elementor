<?php
/**
 * Base class every plugin widget extends.
 *
 * Handles the shared category, automatic asset-handle dependencies (so CSS/JS
 * load only on pages using the widget), and small helper accessors.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Base;

use FWFE\Core\Assets;
use FWFE\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Shared functionality for all Free Widgets.
 */
abstract class Widget_Base extends \Elementor\Widget_Base {

	/**
	 * Place every widget in the plugin's category.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( \FWFE\Hooks\Elementor::CATEGORY );
	}

	/**
	 * Asset slug derived from the widget name (fwfe-flip-box -> flip-box).
	 *
	 * @return string
	 */
	protected function fwfe_slug() {
		$name = $this->get_name();
		if ( 0 === strpos( $name, 'fwfe-' ) ) {
			return substr( $name, strlen( 'fwfe-' ) );
		}
		return $name;
	}

	/**
	 * Register this widget's stylesheet only when it has one on disk.
	 *
	 * Elementor enqueues these only on pages where the widget is present.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		$slug = $this->fwfe_slug();
		if ( is_readable( FWFE_DIR . 'assets/css/widgets/' . $slug . '.css' ) ) {
			return array( Assets::handle( $slug, 'css' ) );
		}
		return array();
	}

	/**
	 * Register this widget's script (and any shared libraries it opts into)
	 * only when present. Elementor enqueues these on demand, so a widget's own
	 * JS — and, e.g., the shared carousel engine — load only where used.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		$slug = $this->fwfe_slug();
		$deps = $this->get_lib_script_depends();
		if ( is_readable( FWFE_DIR . 'assets/js/widgets/' . $slug . '.js' ) ) {
			$deps[] = Assets::handle( $slug, 'js' );
		}
		return $deps;
	}

	/**
	 * Shared library script handles this widget needs (registered in Assets).
	 * Override in a widget, e.g. `return array( Assets::lib_handle( 'carousel' ) );`.
	 *
	 * @return array
	 */
	protected function get_lib_script_depends() {
		return array();
	}

	/**
	 * Convenience accessor for a grouped plugin setting.
	 *
	 * @param string $group    Group key.
	 * @param string $key      Setting key.
	 * @param mixed  $fallback Value to return when the setting is missing.
	 * @return mixed
	 */
	protected function fwfe_setting( $group, $key, $fallback = '' ) {
		return Helper::get_option( $group, $key, $fallback );
	}

	/**
	 * Whitelist of safe heading/text tags, shared by widgets with a tag control.
	 *
	 * @return array
	 */
	protected function allowed_heading_tags() {
		return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
	}
}
