<?php
/**
 * Plugin Name: comm|r
 * Plugin URI:  https://commlr.com/
 * Description: comm|r api
 * Author:      comm|r
 * Author URI:  https://commlr.com/
 * Version:     1.0.0
 * Text Domain: commlr
 * Domain Path: /comm-languages/
 * License:     GPLv2 or later (license.txt)
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

define( 'APIKEY', '$2y$10$VvphRMZ3Pa64x1o41J9nNureTKadSha9ANQM9I29aRQAZ/0iCHVUK' );

if ( ! defined( 'COMM_VERSION' ) ) {
	define( 'COMM_VERSION', '1.0.0' );
}

if ( ! defined( 'COMM_URL' ) ) {
	define( 'COMM_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'COMM_DIR' ) ) {
	define( 'COMM_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Load BuddyMobile instance.
 *
 * @return void
 */
function commlr() {

	require dirname( __FILE__ ) . '/class-commlr.php';

	$GLOBALS['bdm'] = Commlr::instance();
}
add_action( 'plugins_loaded', 'commlr' );


/**
 * Plugin activation sequence
 *
 * @since 1.0.0
 * @return void
 */
function comm_activate() {

	require_once COMM_DIR . 'core/activate-functions.php';
	require_once COMM_DIR . 'schema/comm-schema.php';

	comm_create_default_settings();
	comm_create_uploads_folder();
	comm_install();

}
register_activation_hook( __FILE__, 'comm_activate' );

/**
 * Plugin deactivation sequence
 *
 * @since 1.0.0
 * @return void
 */
function comm_deactivate() {

}
register_deactivation_hook( __FILE__, 'comm_deactivate' );

add_action(
	'rest_api_init',
	function() {

		// if ( ! isset( $_SERVER['QUERY_STRING'] ) ) { return; }
		// parse_str( $_SERVER['QUERY_STRING'], $params );
		// if ( APIKEY !== $params['api-key'] ) { return; }
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		add_filter(
			'rest_pre_serve_request',
			function( $value ) {

				header( 'Access-Control-Allow-Origin: *' );
				header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
				header( 'Access-Control-Allow-Credentials: true' );

				return $value;

			}
		);
	},
	15
);
