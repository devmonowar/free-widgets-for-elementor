<?php
/**
 * System Info page: read-only environment details.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Renders a read-only table of environment information.
 */
final class System_Info {

	/**
	 * Page slug.
	 */
	const SLUG = 'fwfe-system-info';

	/**
	 * Render the system info table.
	 *
	 * @return void
	 */
	public function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $wp_version;

		$rows = array(
			__( 'Plugin version', 'free-widgets-for-elementor' )    => FWFE_VERSION,
			__( 'WordPress version', 'free-widgets-for-elementor' ) => $wp_version,
			__( 'Elementor version', 'free-widgets-for-elementor' ) => defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : __( 'Not active', 'free-widgets-for-elementor' ),
			__( 'PHP version', 'free-widgets-for-elementor' )       => PHP_VERSION,
			__( 'Memory limit', 'free-widgets-for-elementor' )      => (string) ini_get( 'memory_limit' ),
			__( 'Max input vars', 'free-widgets-for-elementor' )    => (string) ini_get( 'max_input_vars' ),
			__( 'Server software', 'free-widgets-for-elementor' )   => isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '',
			__( 'WP debug mode', 'free-widgets-for-elementor' )     => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? __( 'On', 'free-widgets-for-elementor' ) : __( 'Off', 'free-widgets-for-elementor' ),
		);
		?>
		<div class="wrap fwfe-wrap">
			<h1><?php esc_html_e( 'Free Widgets — System Info', 'free-widgets-for-elementor' ); ?></h1>
			<table class="widefat striped" style="max-width:640px">
				<tbody>
				<?php foreach ( $rows as $label => $value ) : ?>
					<tr>
						<td><strong><?php echo esc_html( $label ); ?></strong></td>
						<td><?php echo esc_html( $value ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
