<?php
/**
 * Plugin Name:       Free Widgets For Elementor
 * Plugin URI:        https://devmonowar.github.io/free-widgets-for-elementor/
 * Description:       A lightweight, 100% free collection of essential Elementor widgets. Performance-first, accessible, and built with clean code. No upsells, ever.
 * Version:           2.0.6
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * Author:            Monowar Hossain
 * Author URI:        https://devmonowar.github.io/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       free-widgets-for-elementor
 * Domain Path:       /languages
 *
 * @package FreeWidgetsForElementor
 */

defined( 'ABSPATH' ) || exit;

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/
define( 'FWFE_FILE', __FILE__ );
define( 'FWFE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FWFE_URL', plugin_dir_url( __FILE__ ) );
define( 'FWFE_BASENAME', plugin_basename( __FILE__ ) );

// The `Version:` header above is the single source of truth — read it
// dynamically rather than repeating the number here, so a release only ever
// changes one line.
define( 'FWFE_VERSION', get_file_data( __FILE__, array( 'Version' => 'Version' ) )['Version'] );

// Minimum supported Elementor version (concept, not scattered hardcodes).
define( 'FWFE_MINIMUM_ELEMENTOR_VERSION', '3.5.0' );

// Demo Library manifest (hosted on GitHub Pages). Once released this URL is a
// frozen public API — never change it. Fetched on the Demo Library screen and
// cached for 6 hours; disclosed as an external service in readme.txt.
define( 'FWFE_DEMO_MANIFEST_URL', 'https://devmonowar.github.io/wp-plugin-demo-library/free-widgets-for-elementor/demo-library.json' );

/*
|--------------------------------------------------------------------------
| Manual class loading (no Composer / no PSR-4)
|--------------------------------------------------------------------------
*/
require_once FWFE_DIR . 'includes/Core/Loader.php';
\FWFE\Core\Loader::init();

/*
|--------------------------------------------------------------------------
| Activation / Deactivation
|--------------------------------------------------------------------------
*/
register_activation_hook(
	__FILE__,
	array( '\FWFE\Core\Plugin', 'activate' )
);

register_deactivation_hook(
	__FILE__,
	array( '\FWFE\Core\Plugin', 'deactivate' )
);

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/
add_action(
	'plugins_loaded',
	static function () {
		\FWFE\Core\Plugin::instance()->run();
	}
);
