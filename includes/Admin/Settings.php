<?php
/**
 * Settings page (tabbed) + save controller for the single fwfe_settings option.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Admin;

use FWFE\Core\Plugin;
use FWFE\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Renders the Settings page and handles all section saves.
 */
final class Settings {

	/**
	 * Page slug.
	 */
	const SLUG = 'fwfe-settings';

	/**
	 * Tabs: key => label.
	 *
	 * @return array
	 */
	private static function tabs() {
		return array(
			'general'       => __( 'General', 'free-widgets-for-elementor' ),
			'performance'   => __( 'Performance', 'free-widgets-for-elementor' ),
			'developer'     => __( 'Developer', 'free-widgets-for-elementor' ),
			'global_design' => __( 'Global Design', 'free-widgets-for-elementor' ),
		);
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$tabs = self::tabs();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only tab selector.
		$active = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
		if ( ! isset( $tabs[ $active ] ) ) {
			$active = 'general';
		}

		$settings   = Helper::get_settings();
		$action_url = admin_url( 'admin-post.php' );
		?>
		<div class="wrap fwfe-wrap">
			<h1><?php esc_html_e( 'Free Widgets — Settings', 'free-widgets-for-elementor' ); ?></h1>
			<?php self::notice(); ?>

			<h2 class="nav-tab-wrapper">
				<?php foreach ( $tabs as $key => $label ) : ?>
					<a href="
					<?php
					echo esc_url(
						add_query_arg(
							array(
								'page' => self::SLUG,
								'tab'  => $key,
							),
							admin_url( 'admin.php' )
						)
					);
					?>
								"
						class="nav-tab <?php echo $active === $key ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html( $label ); ?>
					</a>
				<?php endforeach; ?>
			</h2>

			<form method="post" action="<?php echo esc_url( $action_url ); ?>">
				<input type="hidden" name="action" value="fwfe_save_settings" />
				<input type="hidden" name="fwfe_section" value="<?php echo esc_attr( $active ); ?>" />
				<?php wp_nonce_field( 'fwfe_save_settings' ); ?>

				<?php
				if ( 'general' === $active ) {
					self::tab_general( $settings );
				} elseif ( 'performance' === $active ) {
					self::tab_performance( $settings );
				} elseif ( 'developer' === $active ) {
					self::tab_developer( $settings );
				} else {
					self::tab_global_design( $settings );
				}
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * General tab fields.
	 *
	 * @param array $s Settings.
	 * @return void
	 */
	private static function tab_general( $s ) {
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable SVG', 'free-widgets-for-elementor' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fwfe_settings[general_settings][enable_svg]" value="1" <?php checked( ! empty( $s['general_settings']['enable_svg'] ) ); ?> />
						<?php esc_html_e( 'Allow inline SVG icons in widgets.', 'free-widgets-for-elementor' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Load Font Awesome', 'free-widgets-for-elementor' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fwfe_settings[general_settings][load_fontawesome]" value="1" <?php checked( ! empty( $s['general_settings']['load_fontawesome'] ) ); ?> />
						<?php esc_html_e( 'Enqueue Font Awesome (off by default for performance).', 'free-widgets-for-elementor' ); ?>
					</label>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Performance tab fields.
	 *
	 * @param array $s Settings.
	 * @return void
	 */
	private static function tab_performance( $s ) {
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Conditional Asset Loading', 'free-widgets-for-elementor' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fwfe_settings[performance_settings][conditional_assets]" value="1" <?php checked( ! empty( $s['performance_settings']['conditional_assets'] ) ); ?> />
						<?php esc_html_e( 'Load a widget\'s CSS/JS only on pages that use it (recommended).', 'free-widgets-for-elementor' ); ?>
					</label>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Developer tab fields.
	 *
	 * @param array $s Settings.
	 * @return void
	 */
	private static function tab_developer( $s ) {
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Debug Mode', 'free-widgets-for-elementor' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fwfe_settings[developer_settings][debug_mode]" value="1" <?php checked( ! empty( $s['developer_settings']['debug_mode'] ) ); ?> />
						<?php esc_html_e( 'Enable extra debugging output for development.', 'free-widgets-for-elementor' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Clear Cache', 'free-widgets-for-elementor' ); ?></th>
				<td>
					<a class="button" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'action', 'fwfe_clear_cache', admin_url( 'admin-post.php' ) ), 'fwfe_clear_cache' ) ); ?>">
						<?php esc_html_e( 'Clear plugin cache', 'free-widgets-for-elementor' ); ?>
					</a>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Global Design tab fields.
	 *
	 * @param array $s Settings.
	 * @return void
	 */
	private static function tab_global_design( $s ) {
		?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="fwfe-border-radius"><?php esc_html_e( 'Default Border Radius', 'free-widgets-for-elementor' ); ?></label></th>
				<td><input type="text" id="fwfe-border-radius" class="regular-text" name="fwfe_settings[global_design][border_radius]" value="<?php echo esc_attr( $s['global_design']['border_radius'] ); ?>" placeholder="e.g. 8px" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="fwfe-box-shadow"><?php esc_html_e( 'Default Box Shadow', 'free-widgets-for-elementor' ); ?></label></th>
				<td><input type="text" id="fwfe-box-shadow" class="regular-text" name="fwfe_settings[global_design][box_shadow]" value="<?php echo esc_attr( $s['global_design']['box_shadow'] ); ?>" placeholder="e.g. 0 2px 8px rgba(0,0,0,.1)" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="fwfe-typography"><?php esc_html_e( 'Default Font Family', 'free-widgets-for-elementor' ); ?></label></th>
				<td><input type="text" id="fwfe-typography" class="regular-text" name="fwfe_settings[global_design][typography]" value="<?php echo esc_attr( $s['global_design']['typography'] ); ?>" placeholder="e.g. Inter, sans-serif" /></td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render a success notice based on the redirect query arg.
	 *
	 * @return void
	 */
	private static function notice() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display flag set by our own redirect.
		$notice = isset( $_GET['fwfe_notice'] ) ? sanitize_key( wp_unslash( $_GET['fwfe_notice'] ) ) : '';
		if ( 'saved' === $notice ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'free-widgets-for-elementor' ) . '</p></div>';
		} elseif ( 'cache_cleared' === $notice ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Cache cleared.', 'free-widgets-for-elementor' ) . '</p></div>';
		}
	}

	/**
	 * Handle a section save (admin-post).
	 *
	 * @return void
	 */
	public static function handle_save() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'free-widgets-for-elementor' ) );
		}
		check_admin_referer( 'fwfe_save_settings' );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$section = isset( $_POST['fwfe_section'] ) ? sanitize_key( wp_unslash( $_POST['fwfe_section'] ) ) : '';
		// Nonce verified above; the whole array is sanitized via map_deep() on the next line.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$post_settings = isset( $_POST['fwfe_settings'] ) && is_array( $_POST['fwfe_settings'] ) ? wp_unslash( $_POST['fwfe_settings'] ) : array();
		$raw           = map_deep( $post_settings, 'sanitize_text_field' );

		$settings = Helper::get_settings();

		switch ( $section ) {
			case 'general':
				$settings['general_settings']['enable_svg']       = empty( $raw['general_settings']['enable_svg'] ) ? 0 : 1;
				$settings['general_settings']['load_fontawesome'] = empty( $raw['general_settings']['load_fontawesome'] ) ? 0 : 1;
				break;
			case 'performance':
				$settings['performance_settings']['conditional_assets'] = empty( $raw['performance_settings']['conditional_assets'] ) ? 0 : 1;
				break;
			case 'developer':
				$settings['developer_settings']['debug_mode'] = empty( $raw['developer_settings']['debug_mode'] ) ? 0 : 1;
				break;
			case 'global_design':
				$settings['global_design']['border_radius'] = isset( $raw['global_design']['border_radius'] ) ? sanitize_text_field( $raw['global_design']['border_radius'] ) : '';
				$settings['global_design']['box_shadow']    = isset( $raw['global_design']['box_shadow'] ) ? sanitize_text_field( $raw['global_design']['box_shadow'] ) : '';
				$settings['global_design']['typography']    = isset( $raw['global_design']['typography'] ) ? sanitize_text_field( $raw['global_design']['typography'] ) : '';
				break;
			case 'widgets':
				$enabled = array();
				foreach ( array_keys( Helper::widget_registry() ) as $slug ) {
					$enabled[ $slug ] = empty( $raw['enabled_widgets'][ $slug ] ) ? 0 : 1;
				}
				$settings['enabled_widgets'] = $enabled;
				break;
		}

		update_option( Helper::OPTION_KEY, $settings );
		self::redirect_back();
	}

	/**
	 * Handle the Clear Cache action (admin-post).
	 *
	 * @return void
	 */
	public static function handle_clear_cache() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'free-widgets-for-elementor' ) );
		}
		check_admin_referer( 'fwfe_clear_cache' );

		Plugin::clear_cache();
		self::redirect_back( 'cache_cleared' );
	}

	/**
	 * Redirect back to the referring admin page with a notice flag.
	 *
	 * @param string $notice Notice key.
	 * @return void
	 */
	private static function redirect_back( $notice = 'saved' ) {
		$referer = wp_get_referer();
		if ( ! $referer ) {
			$referer = admin_url( 'admin.php?page=' . self::SLUG );
		}
		wp_safe_redirect( add_query_arg( 'fwfe_notice', $notice, remove_query_arg( 'fwfe_notice', $referer ) ) );
		exit;
	}
}
