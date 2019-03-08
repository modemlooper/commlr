<?php
/**
 * Comm db follow schema.
 *
 * @package comm
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Add follow db tables.
 *
 * @return void
 */
function comm_core_install_follow() {
	global $wpdb;
	$sql             = array();
	$charset_collate = $GLOBALS['wpdb']->get_charset_collate();
	$prefix          = $wpdb->base_prefix;

	$sql[] = "CREATE TABLE {$prefix}comm_follow (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id bigint(20) NOT NULL,
                follower_id bigint(20) NOT NULL,
                date_recorded datetime NOT NULL,
                KEY date_recorded (date_recorded),
                KEY user_id (user_id),
                KEY follower_id (follower_id)
    ) {$charset_collate};";

	dbDelta( $sql );

}
