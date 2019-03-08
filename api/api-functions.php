<?php

function comm_upload_image( $user_id, $item_id, $image ) {

	$upload_dir   = wp_upload_dir();
	$user_dirname = $upload_dir['basedir'] . '/buddymobile/' . $user_id . '/feed';

	if ( ! file_exists( $user_dirname ) ) {
		wp_mkdir_p( $user_dirname );
	}

	$filename = $item_id . '.png';

	$image = wp_get_image_editor( $image['tmp_name'] );
	if ( ! is_wp_error( $image ) ) {
		$image->resize( 600, 600, false );
		$image->save( trailingslashit( $user_dirname ) . $filename );
	}

}
