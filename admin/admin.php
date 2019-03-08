<?php

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function commlr_register_main_options_metabox() {

	/**
	 * Registers main options page menu item and form.
	 */
	$main_options = new_cmb2_box(
		array(
			'id'           => 'commlr_main_options_page',
			'title'        => esc_html__( 'comm|r', 'commlr' ),
			'object_types' => array( 'options-page' ),
			'option_key'   => 'commlr_main_options',
			'icon_url'     => 'dashicons-smartphone',
			'parent_slug'  => 'options-general.php',
			'capability'   => 'manage_options',
		)
	);

	$main_options->add_field(
		array(
			'name'        => esc_html__( 'Premium Subscription', 'commlr' ),
			'id'          => 'license',
			'after_field' => 'commlr_activate_button',
			'type'        => 'text',
		)
	);

}
add_action( 'cmb2_admin_init', 'commlr_register_main_options_metabox' );


/**
 * Premium subscription button.
 *
 * @since 2.0.0
 * @return string
 */
function commlr_activate_button() {
	?>
	<div id="submit-activate" class="button button-primary" style="margin-left:10px;" role="button"><?php esc_html_e( 'Activate', 'commlr' ); ?></div>
	<p><?php esc_html_e( 'Enter a subscription license to activate premium features.', 'commlr' ); ?></p>
	<p><?php esc_html_e( 'Get a subscription. ', 'commlr' ); ?><a target="_blank" href="https://getcommlr.com">getcommlr.com</a></p>

	<script type="text/javascript" >
		jQuery(document).ready(function($) {

			jQuery('#submit-activate').on( 'click', function() {

				var data = {
					'action': 'activate_action',
					'key': 1234
				};

				jQuery.post('https://getcommlr.com/wp-json/license/v1', data, function(response) {
					alert('Got this from the server: ' + response);
				});

			});
		});
	</script>
	<?php

}
