<?php
/**
 * Comm db feed members schema.
 *
 * @package comm
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Add feed and feed meta db tables.
 *
 * @return void
 */
function comm_core_install_feed_members() {
	global $wpdb;
	$sql             = array();
	$charset_collate = $GLOBALS['wpdb']->get_charset_collate();
	$prefix          = $wpdb->base_prefix;

	$sql[] = "CREATE TABLE {$prefix}comm_feed_members (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                feed_id bigint(20) NOT NULL,
                user_id bigint(20) NOT NULL,
				date_recorded datetime NOT NULL,
                KEY feed_id (feed_id),
                KEY user_id (user_id),
				KEY date_recorded (date_recorded)
            ) {$charset_collate};";

	dbDelta( $sql );

}
