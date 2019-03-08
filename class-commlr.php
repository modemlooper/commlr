<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Main Commlr Class.
 *
 * Hello, moto.
 *
 * @since 2.0.0
 */
class Commlr {


	/** Singleton *************************************************************/

	/**
	 * Main Commlr Instance.
	 *
	 * Insures that only one instance of Commlr exists in memory at any
	 * one time. Also prevents needing to define globals all over the place.
	 *
	 * @since 2.0.0
	 *
	 * @static object $instance
	 * @see Commlr()
	 *
	 * @return Commlr|null The one true Commlr.
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication.
		static $instance = null;

		// Only run these methods if they haven't been run previously.
		if ( null === $instance ) {
			$instance = new Commlr();
			$instance->constants();
			// $instance->setup_globals();
			$instance->includes();
			$instance->actions();
		}

		// Always return the instance.
		return $instance;

		// Help me Obiwan, you're my only hope.
	}


	/**
	 * A dummy constructor to prevent Commlr from being loaded more than once.
	 *
	 * @since 2.0.0
	 * @see Commlr::instance()
	 * @see Commlr()
	 */
	private function __construct() {
		/* Do nothing here */
	}

	/**
	 * Bootstrap constants.
	 *
	 * @since 2.0.0
	 */
	private function constants() {
		// Path and URL.
		if ( ! defined( 'COMM_PLUGIN_DIR' ) ) {
			define( 'COMM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		if ( ! defined( 'COMM_PLUGIN_URL' ) ) {
			define( 'COMM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
	}

	/**
	 * Include required files.
	 *
	 * @since 2.0.0
	 */
	private function includes() {

		// Vendor includes.
		require COMM_DIR . 'vendor/cmb2/init.php';
		require COMM_DIR . 'vendor/firebase/php-jwt/vendor/autoload.php';
	}

	/**
	 *  Load on action hook.
	 *
	 * @since 2.0.0
	 */
	private function actions() {

		add_action( 'init', array( $this, 'load' ) );
		add_action( 'rest_api_init', array( $this, 'load_api' ) );
	}

	public function load() {
		require COMM_DIR . 'admin/admin.php';
	}

	public function load_api() {
		require COMM_DIR . 'api/api-loader.php';
	}

}

function comm_change_rest_prefix() {
	return 'data';
}
add_filter( 'rest_url_prefix', 'comm_change_rest_prefix' );

function comm_redirect_non_admin_user() {
	if ( is_user_logged_in() ) {
		if ( ! defined( 'DOING_AJAX' ) && ! current_user_can( 'administrator' ) ) {
			wp_safe_redirect( site_url() );
			exit;
		}
	}
}
add_action( 'admin_init', 'comm_redirect_non_admin_user' );
