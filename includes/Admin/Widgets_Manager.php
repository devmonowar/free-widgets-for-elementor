<?php
/**
 * Widgets manager page: enable/disable individual widgets.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Admin;

use FWFE\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Renders the per-widget enable/disable toggles.
 */
final class Widgets_Manager {

	/**
	 * Page slug.
	 */
	const SLUG = 'fwfe-widgets';

	/**
	 * Render the widgets page.
	 *
	 * @return void
	 */
	public function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = Helper::get_settings();
		$enabled  = isset( $settings['enabled_widgets'] ) ? $settings['enabled_widgets'] : array();
		?>
		<div class="wrap fwfe-wrap">
			<h1><?php esc_html_e( 'Free Widgets — Widgets', 'free-widgets-for-elementor' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Turn individual widgets on or off. Disabled widgets are not registered with Elementor.', 'free-widgets-for-elementor' ); ?></p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="fwfe_save_settings" />
				<input type="hidden" name="fwfe_section" value="widgets" />
				<?php wp_nonce_field( 'fwfe_save_settings' ); ?>

				<table class="form-table" role="presentation">
					<tbody>
					<?php foreach ( Helper::widget_registry() as $slug => $label ) : ?>
						<tr>
							<th scope="row"><?php echo esc_html( $label ); ?></th>
							<td>
								<label class="fwfe-toggle">
									<input type="checkbox" name="fwfe_settings[enabled_widgets][<?php echo esc_attr( $slug ); ?>]" value="1" <?php checked( ! empty( $enabled[ $slug ] ) ); ?> />
									<?php esc_html_e( 'Enabled', 'free-widgets-for-elementor' ); ?>
								</label>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<?php submit_button( __( 'Save Widgets', 'free-widgets-for-elementor' ) ); ?>
			</form>
		</div>
		<?php
	}
}
