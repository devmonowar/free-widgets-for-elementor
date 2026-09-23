<?php
/**
 * Uninstall handler.
 *
 * Removes settings, review state and plugin transients. No junk left behind.
 * Multisite: loops every site on network-wide uninstall.
 *
 * @package FreeWidgetsForElementor
 */

// Exit if not called by WordPress during uninstall.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Clean one blog.
 *
 * @return void
 */
function fwfe_uninstall_blog() {
	delete_option( 'fwfe_settings' );
	delete_option( 'fwfe_review' );

	// Remove plugin transients.
	delete_transient( 'fwfe_system_info' );
	delete_transient( 'fwfe_demo_manifest' );
	delete_transient( 'fwfe_demo_manifest_failed' );
}

if ( is_multisite() ) {
	$fwfe_site_ids = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);
	foreach ( $fwfe_site_ids as $fwfe_site_id ) {
		switch_to_blog( (int) $fwfe_site_id );
		fwfe_uninstall_blog();
		restore_current_blog();
	}
} else {
	fwfe_uninstall_blog();
}
