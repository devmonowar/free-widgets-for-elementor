<?php
/**
 * Image rendering helper for Elementor media controls.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Responsive, escaped image output.
 */
final class Image {

	/**
	 * Render an image from an Elementor media control value.
	 *
	 * @param array  $image Elementor image array ( 'id', 'url' ).
	 * @param string $size  Registered image size for attachments.
	 * @param string $alt   Optional alt text fallback (for URL-only images).
	 * @return void
	 */
	public static function render( $image, $size = 'full', $alt = '' ) {
		if ( ! empty( $image['id'] ) ) {
			echo wp_get_attachment_image(
				(int) $image['id'],
				$size,
				false,
				array( 'class' => 'fwfe-image__img' )
			);
			return;
		}

		if ( empty( $image['url'] ) ) {
			return;
		}

		printf(
			'<img class="fwfe-image__img" src="%1$s" alt="%2$s" loading="lazy" />',
			esc_url( $image['url'] ),
			esc_attr( $alt )
		);
	}
}
