<?php
/**
 * Dashboard landing page.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Admin;

use FWFE\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Renders the plugin dashboard overview.
 */
final class Dashboard {

	/**
	 * Render the dashboard.
	 *
	 * @return void
	 */
	public function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$registry = Helper::widget_registry();
		$settings = Helper::get_settings();
		$enabled  = isset( $settings['enabled_widgets'] ) ? array_filter( $settings['enabled_widgets'] ) : array();
		?>
		<div class="wrap fwfe-wrap fwfe-dashboard">
			<h1><?php esc_html_e( 'Free Widgets For Elementor', 'free-widgets-for-elementor' ); ?></h1>
			<p class="fwfe-tagline"><?php esc_html_e( '100% free, performance-first Elementor widgets. No upsells, ever.', 'free-widgets-for-elementor' ); ?></p>

			<div class="fwfe-cards">
				<div class="fwfe-card">
					<h2><?php echo esc_html( number_format_i18n( count( $enabled ) ) . ' / ' . number_format_i18n( count( $registry ) ) ); ?></h2>
					<p><?php esc_html_e( 'Widgets enabled', 'free-widgets-for-elementor' ); ?></p>
					<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=' . Widgets_Manager::SLUG ) ); ?>"><?php esc_html_e( 'Manage Widgets', 'free-widgets-for-elementor' ); ?></a>
				</div>
				<div class="fwfe-card">
					<h2><?php esc_html_e( 'Settings', 'free-widgets-for-elementor' ); ?></h2>
					<p><?php esc_html_e( 'General, performance, developer & global design.', 'free-widgets-for-elementor' ); ?></p>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=' . Settings::SLUG ) ); ?>"><?php esc_html_e( 'Open Settings', 'free-widgets-for-elementor' ); ?></a>
				</div>
				<div class="fwfe-card">
					<h2><?php esc_html_e( 'System Info', 'free-widgets-for-elementor' ); ?></h2>
					<p><?php esc_html_e( 'Environment details for troubleshooting.', 'free-widgets-for-elementor' ); ?></p>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=' . System_Info::SLUG ) ); ?>"><?php esc_html_e( 'View System Info', 'free-widgets-for-elementor' ); ?></a>
				</div>
			</div>

			<h2><?php esc_html_e( 'Available widgets', 'free-widgets-for-elementor' ); ?></h2>
			<ul class="fwfe-widget-list">
				<?php foreach ( $registry as $slug => $label ) : ?>
					<li>
						<span class="fwfe-dot <?php echo ! empty( $enabled[ $slug ] ) ? 'is-on' : 'is-off'; ?>"></span>
						<?php echo esc_html( $label ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
}
