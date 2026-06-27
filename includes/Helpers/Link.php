<?php
/**
 * Link attribute helper for Elementor URL controls.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Builds escaped, safe anchor attributes from an Elementor URL control.
 */
final class Link {

	/**
	 * Build an escaped attribute string for an anchor tag.
	 *
	 * @param array $url Elementor URL array ( 'url', 'is_external', 'nofollow' ).
	 * @return string Space-prefixed attribute string, or empty string.
	 */
	public static function attributes( $url ) {
		if ( empty( $url['url'] ) ) {
			return '';
		}

		$attrs = array( 'href' => esc_url( $url['url'] ) );

		$rel = array();
		if ( ! empty( $url['is_external'] ) ) {
			$attrs['target'] = '_blank';
			$rel[]           = 'noopener';
		}
		if ( ! empty( $url['nofollow'] ) ) {
			$rel[] = 'nofollow';
		}
		if ( ! empty( $rel ) ) {
			$attrs['rel'] = implode( ' ', array_unique( $rel ) );
		}

		$out = '';
		foreach ( $attrs as $name => $value ) {
			$out .= ' ' . $name . '="' . esc_attr( $value ) . '"';
		}

		return $out;
	}
}
