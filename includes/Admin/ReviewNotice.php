<?php
/**
 * A polite "enjoying this plugin?" review request.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Admin;

use FWFE\Helpers\Helper;
use FWFE\Hooks\Admin as Admin_Hooks;

defined( 'ABSPATH' ) || exit;

/**
 * Review prompts, confined to the plugin's own admin screens:
 *
 * - A small, always-visible rating link in the admin footer.
 * - A notice that first appears after ~two weeks of real use, and returns
 *   once a month until the user actually rates the plugin.
 * - Once "Rate it" is clicked, every prompt (notice and footer) disappears
 *   for good.
 */
final class ReviewNotice {

	const OPTION     = 'fwfe_review';
	const REVIEW_URL = 'https://wordpress.org/support/plugin/free-widgets-for-elementor/reviews/#new-post';

	/**
	 * Days of use before the notice first appears.
	 */
	const WAIT_DAYS = 15;

	/**
	 * Days between repeat appearances (once a month).
	 */
	const SNOOZE_DAYS = 30;

	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_init', array( $this, 'start_clock' ) );
		add_action( 'admin_init', array( $this, 'handle_actions' ) );
		add_action( 'admin_notices', array( $this, 'maybe_render' ) );
		add_filter( 'admin_footer_text', array( $this, 'footer_text' ) );
	}

	/**
	 * A small, permanent rating link in the admin footer — only on this
	 * plugin's own screens, and only until the user has rated.
	 *
	 * @param string $text Default footer text.
	 * @return string
	 */
	public function footer_text( $text ) {
		if ( ! $this->on_own_screen() ) {
			return $text;
		}

		$state = (array) get_option( self::OPTION, array() );
		if ( ! empty( $state['rated'] ) ) {
			return $text;
		}

		$link = '<a href="' . esc_url( self::REVIEW_URL ) . '" target="_blank" rel="noopener noreferrer">';

		return sprintf(
			/* translators: 1: opening link tag to the WordPress.org review form, 2: closing link tag. */
			esc_html__( 'Enjoying Free Widgets? Leave us a %1$s&#9733;&#9733;&#9733;&#9733;&#9733; review%2$s — it keeps development going.', 'free-widgets-for-elementor' ),
			$link,
			'</a>'
		);
	}

	/**
	 * Record when the plugin was first seen in the admin, so existing installs
	 * also wait a full period after updating to a version with this notice.
	 * Prefers the plugin's own install_time when it exists.
	 *
	 * @return void
	 */
	public function start_clock() {
		$state = get_option( self::OPTION );
		if ( is_array( $state ) && ! empty( $state['since'] ) ) {
			return;
		}

		$settings = Helper::get_settings();
		$since    = ! empty( $settings['install_time'] ) ? (int) $settings['install_time'] : time();

		update_option( self::OPTION, array( 'since' => $since ), false );
	}

	/**
	 * Process the notice's action links.
	 *
	 * @return void
	 */
	public function handle_actions() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- the nonce is checked immediately below, once we know this is our request.
		if ( empty( $_GET['fwfe_review'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'fwfe_review_notice' );

		$state  = (array) get_option( self::OPTION, array() );
		$action = sanitize_key( wp_unslash( $_GET['fwfe_review'] ) );

		if ( 'rated' === $action ) {
			$state['rated'] = true;
		} elseif ( 'rate' === $action ) {
			// Opened the review form: ask for confirmation on the next visit.
			$state['asked'] = true;
			unset( $state['snooze_until'] );
		} else {
			$state['snooze_until'] = time() + self::SNOOZE_DAYS * DAY_IN_SECONDS;
		}
		update_option( self::OPTION, $state, false );

		wp_safe_redirect( remove_query_arg( array( 'fwfe_review', '_wpnonce' ) ) );
		exit;
	}

	/**
	 * Render the notice when every polite condition is met.
	 *
	 * @return void
	 */
	public function maybe_render() {
		if ( ! current_user_can( 'manage_options' ) || ! $this->should_show() ) {
			return;
		}

		$state = (array) get_option( self::OPTION, array() );
		$later = wp_nonce_url( add_query_arg( 'fwfe_review', 'later' ), 'fwfe_review_notice' );
		$rate  = wp_nonce_url( add_query_arg( 'fwfe_review', 'rate' ), 'fwfe_review_notice' );
		$rated = wp_nonce_url( add_query_arg( 'fwfe_review', 'rated' ), 'fwfe_review_notice' );

		if ( empty( $state['asked'] ) ) {
			$message = '<strong>' . esc_html__( 'Enjoying Free Widgets?', 'free-widgets-for-elementor' ) . '</strong> '
				. esc_html__( 'A quick 5-star review helps other people find the plugin and keeps development going. Thank you!', 'free-widgets-for-elementor' );
		} else {
			$message = '<strong>' . esc_html__( 'Did you get a chance to leave that review?', 'free-widgets-for-elementor' ) . '</strong> '
				. esc_html__( 'If you did — thank you! Confirm below and we will never ask again.', 'free-widgets-for-elementor' );
		}
		?>
		<div class="notice notice-info" style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
			<p style="margin:0;"><?php echo wp_kses( $message, array( 'strong' => array() ) ); ?></p>
			<p style="margin:0;white-space:nowrap;">
				<?php if ( ! empty( $state['asked'] ) ) : ?>
					<a class="button" href="<?php echo esc_url( $rated ); ?>" style="margin-right:6px;">
						<?php esc_html_e( 'Yes, I left a review', 'free-widgets-for-elementor' ); ?>
					</a>
				<?php endif; ?>
				<a class="button" href="<?php echo esc_url( $later ); ?>" style="margin-right:6px;">
					<?php esc_html_e( 'Maybe later', 'free-widgets-for-elementor' ); ?>
				</a>
				<a class="button button-primary" href="<?php echo esc_url( self::REVIEW_URL ); ?>" target="_blank" rel="noopener noreferrer" onclick="window.location='<?php echo esc_js( $rate ); ?>';return true;">
					<?php esc_html_e( 'Rate it ★★★★★', 'free-widgets-for-elementor' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * All the polite conditions in one place.
	 *
	 * @return bool
	 */
	private function should_show() {
		if ( ! $this->on_own_screen() ) {
			return false;
		}

		$state = (array) get_option( self::OPTION, array() );
		if ( ! empty( $state['rated'] ) ) {
			return false;
		}
		if ( ! empty( $state['snooze_until'] ) && time() < (int) $state['snooze_until'] ) {
			return false;
		}
		if ( empty( $state['since'] ) || time() < (int) $state['since'] + self::WAIT_DAYS * DAY_IN_SECONDS ) {
			return false;
		}

		// Only ask people who actually use the plugin: somebody changed a
		// setting away from the defaults (toggled a widget, enabled SVG, or
		// touched the global design).
		return $this->settings_touched();
	}

	/**
	 * Whether the stored settings differ from a fresh install's defaults.
	 *
	 * @return bool
	 */
	private function settings_touched() {
		$settings = Helper::get_settings();

		if ( ! empty( $settings['general_settings']['enable_svg'] ) ) {
			return true;
		}

		foreach ( (array) ( $settings['global_design'] ?? array() ) as $value ) {
			if ( '' !== $value ) {
				return true;
			}
		}

		foreach ( (array) ( $settings['enabled_widgets'] ?? array() ) as $enabled ) {
			if ( empty( $enabled ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Whether the current admin screen belongs to this plugin — the top-level
	 * page or one of its sub-pages, which all hang off the same menu slug.
	 *
	 * @return bool
	 */
	private function on_own_screen() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || ! isset( $screen->id ) ) {
			return false;
		}

		return 'toplevel_page_' . Admin_Hooks::MENU_SLUG === $screen->id
			|| 0 === strpos( $screen->id, Admin_Hooks::MENU_SLUG . '_page_' );
	}
}
