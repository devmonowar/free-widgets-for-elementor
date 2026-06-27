<?php
/**
 * Icon rendering helper (wraps Elementor's icon manager).
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Safe icon output for widgets.
 */
final class Icon {

	/**
	 * Render an Elementor icon control value.
	 *
	 * @param array $icon       Elementor icon array ( 'value', 'library' ).
	 * @param array $attributes Extra HTML attributes for the wrapper.
	 * @return void
	 */
	public static function render( $icon, $attributes = array() ) {
		if ( empty( $icon ) || ! class_exists( '\Elementor\Icons_Manager' ) ) {
			return;
		}

		$attrs = array_merge( array( 'aria-hidden' => 'true' ), $attributes );

		\Elementor\Icons_Manager::render_icon( $icon, $attrs );
	}

	/**
	 * Whether inline SVG support is enabled in plugin settings.
	 *
	 * @return bool
	 */
	public static function svg_enabled() {
		return (bool) Helper::get_option( 'general_settings', 'enable_svg', 0 );
	}
}
