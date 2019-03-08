<?php
/**
 * Comm Schema.
 *
 * @package comm
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


global $comm_db_version;
$comm_db_version = '9000';

/**
 * Include db schema files
 *
 * @return void
 */
function comm_install() {
	global $comm_db_version;

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	require dirname( __FILE__ ) . '/activity-schema.php';
	comm_core_install_activity();

	require dirname( __FILE__ ) . '/hashtags-schema.php';
	comm_core_install_hashtags();

	require dirname( __FILE__ ) . '/notifications-schema.php';
	comm_core_install_notifications();

	require dirname( __FILE__ ) . '/follow-schema.php';
	comm_core_install_follow();

	require dirname( __FILE__ ) . '/block-schema.php';
	comm_core_install_block();

	require dirname( __FILE__ ) . '/feeds-schema.php';
	comm_core_install_feeds();

	require dirname( __FILE__ ) . '/feed-members-schema.php';
	comm_core_install_feed_members();

	// Set current db version.
	add_option( '_comm_db_version', $comm_db_version );

}
// comm_install();

/**
 * Check if table already exists
 *
 * @param  string $table table name.
 * @return boolean
 */
function comm_if_table_exists( $table = '' ) {
	global $wpdb;

	$able = $wpdb->base_prefix . $table;

	$old_suppress = $wpdb->suppress_errors();

	$table_exists = (bool) $wpdb->get_results( "DESCRIBE {$table};" );

	$wpdb->suppress_errors( $old_suppress );

	if ( true === $table_exists ) {
		return true;
	}

	return false;

}
