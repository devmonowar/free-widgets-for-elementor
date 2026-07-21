<?php
/**
 * General helpers: settings access, widget registry, defaults.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Static helpers shared across the plugin.
 */
final class Helper {

	/**
	 * The single option key that stores all plugin settings.
	 */
	const OPTION_KEY = 'fwfe_settings';

	/**
	 * Master list of widgets: slug => label.
	 *
	 * Slug maps to includes/Widgets/<PascalSlug>/Widget.php.
	 *
	 * @return array
	 */
	public static function widget_registry() {
		return array(
			'pricing-table'     => __( 'Pricing Table', 'free-widgets-for-elementor' ),
			'flip-box'          => __( 'Flip Box', 'free-widgets-for-elementor' ),
			'team'              => __( 'Team', 'free-widgets-for-elementor' ),
			'cta'               => __( 'Call To Action', 'free-widgets-for-elementor' ),
			'countdown'         => __( 'Countdown Timer', 'free-widgets-for-elementor' ),
			'post-grid'         => __( 'Post Grid', 'free-widgets-for-elementor' ),
			'logo-carousel'     => __( 'Logo Carousel', 'free-widgets-for-elementor' ),
			'table-of-contents' => __( 'Table of Contents', 'free-widgets-for-elementor' ),
		);
	}

	/**
	 * Default settings stored under OPTION_KEY.
	 *
	 * @return array
	 */
	public static function default_settings() {
		$enabled = array();
		foreach ( array_keys( self::widget_registry() ) as $slug ) {
			$enabled[ $slug ] = 1;
		}

		return array(
			'enabled_widgets'      => $enabled,
			'general_settings'     => array(
				'enable_svg'       => 0,
				'load_fontawesome' => 0,
			),
			'performance_settings' => array(
				'conditional_assets' => 1,
			),
			'developer_settings'   => array(
				'debug_mode' => 0,
			),
			'global_design'        => array(
				'border_radius' => '',
				'box_shadow'    => '',
				'typography'    => '',
			),
			'plugin_version'       => defined( 'FWFE_VERSION' ) ? FWFE_VERSION : '',
			'install_time'         => 0,
		);
	}

	/**
	 * Get settings merged over defaults.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$stored = get_option( self::OPTION_KEY, array() );
		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		$defaults = self::default_settings();
		$out      = $defaults;

		foreach ( $stored as $key => $value ) {
			if ( isset( $defaults[ $key ] ) && is_array( $defaults[ $key ] ) && is_array( $value ) ) {
				$out[ $key ] = array_merge( $defaults[ $key ], $value );
			} else {
				$out[ $key ] = $value;
			}
		}

		// Keep only enable flags for widgets that exist in the registry, so
		// counts and lookups always reflect the real widget set.
		if ( isset( $out['enabled_widgets'] ) && is_array( $out['enabled_widgets'] ) ) {
			$out['enabled_widgets'] = array_intersect_key( $out['enabled_widgets'], self::widget_registry() );
		}

		return $out;
	}

	/**
	 * Whether a widget slug is enabled.
	 *
	 * @param string $slug Widget slug.
	 * @return bool
	 */
	public static function is_widget_enabled( $slug ) {
		$settings = self::get_settings();
		return ! empty( $settings['enabled_widgets'][ $slug ] );
	}

	/**
	 * Enable one or more widgets (only slugs that exist in the registry).
	 *
	 * Used when importing a demo so the widgets it relies on are available in
	 * Elementor. Returns the slugs that were newly enabled.
	 *
	 * @param array $slugs Widget slugs to enable.
	 * @return array Slugs that were actually turned on (were off before).
	 */
	public static function enable_widgets( array $slugs ) {
		$registry = self::widget_registry();

		// Read the raw stored option so other settings keys are left intact.
		$stored = get_option( self::OPTION_KEY, array() );
		if ( ! is_array( $stored ) ) {
			$stored = array();
		}
		if ( ! isset( $stored['enabled_widgets'] ) || ! is_array( $stored['enabled_widgets'] ) ) {
			$stored['enabled_widgets'] = array();
		}

		$turned_on = array();
		foreach ( $slugs as $slug ) {
			$slug = sanitize_key( $slug );
			if ( ! isset( $registry[ $slug ] ) ) {
				continue;
			}
			if ( empty( $stored['enabled_widgets'][ $slug ] ) ) {
				$stored['enabled_widgets'][ $slug ] = 1;
				$turned_on[]                        = $slug;
			}
		}

		if ( $turned_on ) {
			update_option( self::OPTION_KEY, $stored );
		}

		return $turned_on;
	}

	/**
	 * Read a single grouped setting.
	 *
	 * @param string $group    Group key (e.g. general_settings).
	 * @param string $key      Setting key.
	 * @param mixed  $fallback Value to return when the setting is missing.
	 * @return mixed
	 */
	public static function get_option( $group, $key, $fallback = '' ) {
		$settings = self::get_settings();
		if ( isset( $settings[ $group ][ $key ] ) ) {
			return $settings[ $group ][ $key ];
		}
		return $fallback;
	}
}
