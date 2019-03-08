<?php
/**
 * Comm db hashtags schema.
 *
 * @package comm
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Add hashtag tables.
 *
 * @return void
 */
function comm_core_install_hashtags() {
	global $wpdb;
	$sql             = array();
	$charset_collate = $GLOBALS['wpdb']->get_charset_collate();
	$prefix          = $wpdb->base_prefix;

	$sql[] = "CREATE TABLE {$prefix}comm_hashtags (
		hashtag_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	    hashtag varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
	    PRIMARY KEY (hashtag_id),
	    KEY hashtag (hashtag(191))
    ) {$charset_collate};";

	$sql[] = "CREATE TABLE {$prefix}comm_hashtag_relationships (
		activity_id bigint(20) unsigned NOT NULL DEFAULT '0',
	    hashtag_id bigint(20) unsigned NOT NULL DEFAULT '0',
	    PRIMARY KEY (activity_id,hashtag_id),
	    KEY hashtag_id (hashtag_id)
    ) {$charset_collate};";

	dbDelta( $sql );

}
