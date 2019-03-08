<?php

/**
 * Creates commlr upload folder for user generated content.
 *
 * @since 1.0.0
 * @return void
 */
function comm_create_uploads_folder() {

	$upload_dir = wp_upload_dir();
	$dir        = $upload_dir['basedir'] . '/commlr';

	if ( ! file_exists( $dir ) ) {
		wp_mkdir_p( $dir );
	}
}

/**
 * Add commlr default settings to options.
 *
 * @since 1.0.0
 * @return void
 */
function comm_create_default_settings() {

	$settings = get_site_option( 'comm' );

	if ( ! $settings ) {

		$values = array(
			'jwt_secret' => wp_generate_password( 40, true, true ),
			'site_id'    => wp_generate_password( 10, false, false ),
		);

		update_site_option( 'comm', $values );
	}

}
