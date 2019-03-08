<?php
/**
 * Comm REST: common functions.
 *
 * @package comm|r
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_REST_Controller' ) ) {
	return;
}

require_once dirname( __FILE__ ) . '/api-functions.php';

require_once dirname( __FILE__ ) . '/class-auth.php';
$controller = new COMM_REST_Auth_Endpoint();
$controller->register_routes();



