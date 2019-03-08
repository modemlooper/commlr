<?php
/**
 * COMM REST: COMM_REST_Auth_Endpoint class
 *
 * @package commlr
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

use \Firebase\JWT\JWT;

/**
 * Auth endpoints.
 *
 * @since 1.0.0
 */
class COMM_REST_Auth_Endpoint extends WP_REST_Controller {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->namespace = 'commlr/v1';
		$this->rest_base = 'auth';
	}

	/**
	 * Register the component routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( $this, 'user_auth' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/validate',
			array(
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( $this, 'validate_auth' ),
				),
			)
		);

	}

	/**
	 * Retrieve auth.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response user response data.
	 */
	public function user_auth( $request ) {

		global $wpdb;

		$username = $request['username'];
		$password = $request['password'];

		$user = wp_authenticate( $username, $password );

		error_log(print_r($user,true));

		if ( is_wp_error( $user ) ) {
			return false;
		}

		$user = array(
			'user_id'       => $user->data->ID,
			'user_login'    => $user->data->user_login,
			'user_nicename' => $user->data->user_nicename,
			'display_name'  => $user->data->display_name,
			'url'           => site_url(),
		);

		// Create a JSON Web token for the user.
		$token          = $this->create_jwt( $user['user_id'] );
		$user['tokens'] = $token;

		$response = rest_ensure_response( $user );

		/**
		 * Fires after a user is fetched via the REST API.
		 *
		 * @since 1.0.0
		 *
		 * @param object            $user Fetched user data.
		 * @param WP_REST_Response $response   The response data.
		 * @param WP_REST_Request  $request    The request sent to the API.
		 */
		do_action( 'rest_auth_get_user', $user, $response, $request );

		return $response;
	}

	/**
	 * Check if a given request has access to activity items.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return bool
	 */
	public function get_user_permissions_check( $request ) {
		return true;
	}

	/**
	 * Create a JSON Web token for the user.
	 *
	 * @param  integer $user_id logged in user id.
	 * @return string
	 */
	private function create_jwt( $user_id = 0 ) {


		if ( 0 !== $user_id && JWT_KEY ) {

			$issued_at  = time();
			$not_before = $issued_at;
			$expire     = $issued_at + ( DAY_IN_SECONDS * 365 );

			$token = array(
				'iss'  => get_bloginfo( 'url' ),
				'iat'  => $issued_at,
				'nbf'  => $not_before,
				'exp'  => $expire,
				'data' => array(
					'user' => array(
						'id' => $user_id,
					),
				),
			);

			$jwt  = JWT::encode( $token, JWT_KEY );
			$hash = wp_hash_password( $jwt . JWT_KEY );

			$tokens = array(
				'token' => $jwt,
				'key'   => $hash,
			);

			return $tokens;

		}

		return false;

	}

	/**
	 * Validate auth for the user.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return array
	 */
	public function validate_auth( $request ) {

		$headers = $_SERVER;

		$params = $request->get_params();

		if ( ! isset( $params['key'] ) ) {
			return new WP_Error(
				'comm_no_auth_key',
				__( 'Authorization key not found.', 'commlr' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( ! isset( $headers['HTTP_AUTHORIZATION'] ) ) {
			return new WP_Error(
				'comm_no_auth_header',
				__( 'Authorization header not found.', 'commlr' ),
				array(
					'status' => 403,
				)
			);
		}

		list( $token ) = sscanf( $headers['HTTP_AUTHORIZATION'], 'Bearer %s' );

		if ( ! $token ) {
			return new WP_Error(
				'comm_bad_auth_header',
				__( 'Authorization header malformed.', 'commlr' ),
				array(
					'status' => 403,
				)
			);
		}

		$token_validation = $this->validate_jwt( $token, JWT_KEY, stripslashes( $params['key'] ) );

		return rest_ensure_response( $token_validation );

	}

	/**
	 * Validate JSON Web token for the user.
	 *
	 * @param WP_REST_Request $headers Full request headers.
	 * @return string
	 */
	public function validate_jwt( $token = null, $secret_key = null, $key = null ) {

		if ( ! $secret_key ) {
			return new WP_Error(
				'comm_auth_bad_config',
				__( 'commlr JWT is not configurated properly, please contact the admin', 'commlr' ),
				array(
					'status' => 403,
				)
			);
		}

		require_once ABSPATH . WPINC . '/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
		$valid_key = $wp_hasher->CheckPassword( $token . $secret_key, $key );

		if ( ! $valid_key ) {
			return new WP_Error(
				'comm_bad_auth_key',
				__( 'Authorization token invalid.', 'commlr' ),
				array(
					'status' => 403,
				)
			);
		}

		try {

			$token = JWT::decode( $token, $secret_key, array( 'HS256' ) );

			if ( get_bloginfo( 'url' ) !== $token->iss ) {
				/** The iss do not match, return error */
				return new WP_Error(
					'comm_auth_bad_iss',
					__( 'The iss do not match with this server', 'commlr' ),
					array(
						'status' => 403,
					)
				);
			}

			/** So far so good, validate the user id in the token */
			if ( ! isset( $token->data->user->id ) ) {
				/** No user id in the token, abort!! */
				return new WP_Error(
					'comm_auth_bad_request',
					__( 'User ID not found in the token', 'commlr' ),
					array(
						'status' => 403,
					)
				);
			}

			/** If the output is true return an answer to the request to show it */
			return array(
				'code' => 'comm_auth_valid_token',
				'data' => array(
					'status' => 200,
				),
			);

		} catch ( Exception $e ) {
			/** Something is wrong trying to decode the token, send back the error */
			return new WP_Error(
				'comm_auth_invalid_token',
				$e->getMessage(),
				array(
					'status' => 403,
				)
			);
		}
	}

}
