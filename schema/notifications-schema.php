<?php
/**
 * Comm db notifications schema.
 *
 * @package comm
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Add notifications db tables.
 *
 * @return void
 */
function comm_core_install_notifications() {
	global $wpdb;
	$sql             = array();
	$charset_collate = $GLOBALS['wpdb']->get_charset_collate();
	$prefix          = $wpdb->base_prefix;

	$sql[] = "CREATE TABLE {$prefix}comm_notifications (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id bigint(20) NOT NULL,
                type varchar(75) NOT NULL,
                action text NOT NULL,
                status bigint(20) NOT NULL,
                item_id bigint(20) NOT NULL,
                secondary_item_id bigint(20) NOT NULL,
                date_recorded datetime NOT NULL,
                KEY date_recorded (date_recorded),
                KEY user_id (user_id),
                KEY status (status),
                KEY item_id (item_id),
                KEY secondary_item_id (secondary_item_id),
                KEY type (type)
    ) {$charset_collate};";

	dbDelta( $sql );

}
