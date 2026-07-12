<?php
/**
 * Frontend hooks: register widget assets + output global design tokens.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Hooks;

use FWFE\Core\Assets;
use FWFE\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Handles frontend-wide concerns shared by all widgets.
 */
final class Frontend {

	/**
	 * Register frontend hooks.
	 *
	 * @return void
	 */
	public function register() {
		// Register (not enqueue) widget assets; Elementor enqueues per page
		// via each widget's get_style_depends() / get_script_depends().
		add_action( 'wp_enqueue_scripts', array( '\FWFE\Core\Assets', 'register_frontend' ), 5 );
		add_action( 'wp_head', array( $this, 'print_global_design' ), 20 );
	}

	/**
	 * Output Global Design tokens as CSS custom properties.
	 *
	 * Only prints rules that have a value, so empty settings add nothing.
	 *
	 * @return void
	 */
	public function print_global_design() {
		$design = Helper::get_settings()['global_design'];

		$vars = array();
		if ( '' !== trim( (string) $design['border_radius'] ) ) {
			$vars['--fwfe-border-radius'] = $design['border_radius'];
		}
		if ( '' !== trim( (string) $design['box_shadow'] ) ) {
			$vars['--fwfe-shadow'] = $design['box_shadow'];
		}
		if ( '' !== trim( (string) $design['typography'] ) ) {
			$vars['--fwfe-font-family'] = $design['typography'];
		}

		if ( empty( $vars ) ) {
			return;
		}

		$css = ':root{';
		foreach ( $vars as $name => $value ) {
			// CSS context: strip characters that could terminate the declaration
			// or rule, or break out of the <style> element ( ; { } < > \ ).
			$value = preg_replace( '/[<>{};\\\\]/', '', (string) $value );
			$css  .= $name . ':' . $value . ';';
		}
		$css .= '}';

		echo "<style id='fwfe-global-design'>" . $css . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values sanitised for CSS context above; property names are fixed literals.
	}
}
