<?php
/**
 * Elementor integration: widget category + widget registration.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Hooks;

use FWFE\Core\Loader;
use FWFE\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the plugin's category and enabled widgets with Elementor.
 */
final class Elementor {

	/**
	 * Category slug used to group the plugin's widgets.
	 */
	const CATEGORY = 'fwfe';

	/**
	 * Register Elementor hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( '\FWFE\Core\Assets', 'enqueue_editor' ) );
	}

	/**
	 * Add the "Free Widgets" category to the Elementor panel.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager.
	 * @return void
	 */
	public function register_category( $elements_manager ) {
		$args = array(
			'title' => esc_html__( 'Free Widgets', 'free-widgets-for-elementor' ),
			'icon'  => 'fa fa-plug',
		);

		// Add normally (this is also the fallback if the reorder below fails).
		$elements_manager->add_category( self::CATEGORY, $args );

		// Elementor appends new categories to the bottom of the panel; move ours
		// to the top. Done defensively so a future Elementor change can't fatal.
		try {
			$categories = $elements_manager->get_categories();
			if ( isset( $categories[ self::CATEGORY ] ) ) {
				$ours = array( self::CATEGORY => $categories[ self::CATEGORY ] );
				unset( $categories[ self::CATEGORY ] );
				$reordered = array_merge( $ours, $categories );

				$ref = new \ReflectionObject( $elements_manager );
				if ( $ref->hasProperty( 'categories' ) ) {
					$prop = $ref->getProperty( 'categories' );
					$prop->setAccessible( true );
					$prop->setValue( $elements_manager, $reordered );
				}
			}
		} catch ( \Throwable $e ) {
			// Leave the category where add_category() placed it.
			unset( $e );
		}
	}

	/**
	 * Register each enabled widget with Elementor.
	 *
	 * Loads only enabled widget classes (manual require via Loader), now that
	 * Elementor's base widget class is available.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 * @return void
	 */
	public function register_widgets( $widgets_manager ) {
		foreach ( array_keys( Helper::widget_registry() ) as $slug ) {
			if ( ! Helper::is_widget_enabled( $slug ) ) {
				continue;
			}

			$class = Loader::load_widget_class( $slug );

			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}
}
