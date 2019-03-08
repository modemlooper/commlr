<?php
/**
 * Comm db feed schema.
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
function comm_core_install_feeds() {
	global $wpdb;
	$sql             = array();
	$charset_collate = $GLOBALS['wpdb']->get_charset_collate();
	$prefix          = $wpdb->base_prefix;

	$sql[] = "CREATE TABLE {$prefix}comm_feeds (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id bigint(20) NOT NULL,
                type varchar(75) NOT NULL,
				name text NOT NULL,
				description longtext NOT NULL,
                item_id bigint(20) NOT NULL,
                secondary_item_id bigint(20) NOT NULL,
				date_recorded datetime NOT NULL,
				KEY date_recorded (date_recorded),
                KEY user_id (user_id),
                KEY item_id (item_id),
                KEY secondary_item_id (secondary_item_id),
                KEY type (type)
            ) {$charset_collate};";

	$sql[] = "CREATE TABLE {$prefix}comm_feeds_meta (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                feed_id bigint(20) NOT NULL,
                meta_key varchar(255) DEFAULT NULL,
                meta_value longtext DEFAULT NULL,
                KEY activity_id (feed_id),
                KEY meta_key (meta_key(191))
            ) {$charset_collate};";

	dbDelta( $sql );

}
