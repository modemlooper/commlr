<?php
/**
 * Comm db activity schema.
 *
 * @package comm
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Add activity and activity meta db tables.
 *
 * @return void
 */
function comm_core_install_activity() {
	global $wpdb;
	$sql             = array();
	$charset_collate = $GLOBALS['wpdb']->get_charset_collate();
	$prefix          = $wpdb->base_prefix;

	$sql[] = "CREATE TABLE {$prefix}comm_activity (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id bigint(20) NOT NULL,
                type varchar(75) NOT NULL,
                action text NOT NULL,
                content longtext NOT NULL,
				secondary_content longtext NOT NULL,
                item_id bigint(20) NOT NULL,
                secondary_item_id bigint(20) NOT NULL,
                date_recorded datetime NOT NULL,
                is_spam tinyint(1) NOT NULL DEFAULT 0,
				has_replies tinyint(1) NOT NULL DEFAULT 0,
                KEY date_recorded (date_recorded),
                KEY user_id (user_id),
                KEY item_id (item_id),
                KEY secondary_item_id (secondary_item_id),
                KEY type (type),
                KEY is_spam (is_spam),
				KEY has_replies (has_replies)
            ) {$charset_collate};";

	$sql[] = "CREATE TABLE {$prefix}comm_activity_meta (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                activity_id bigint(20) NOT NULL,
                meta_key varchar(255) DEFAULT NULL,
                meta_value longtext DEFAULT NULL,
                KEY activity_id (activity_id),
                KEY meta_key (meta_key(191))
            ) {$charset_collate};";

	dbDelta( $sql );

}
