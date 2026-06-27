<?php
/**
 * Uninstall handler.
 *
 * Removes the single settings option and plugin transients. No junk left behind.
 *
 * @package FreeWidgetsForElementor
 */

// Exit if not called by WordPress during uninstall.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'fwfe_settings' );

// Remove plugin transients.
delete_transient( 'fwfe_system_info' );
