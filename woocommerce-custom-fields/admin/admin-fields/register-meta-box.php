<?php
/**
 * Metaboxes that are placed inside WCCF Post Type
 *
 * @package WooCommerceCustomFields/PostTypeMetaboxes
 */

/**
 * Register metabox at the top of the screen
 */
function wccf_register_meta_box() {
	add_meta_box(
		'wccf-meta-box',
		esc_html( __( 'Woocommerce Custom Fields', 'woocommerce-custom-fields' ) ),
		'wccf_render_meta_box_html',
		WCCF_POSTTYPE,
		'advanced',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wccf_register_meta_box' );

/**
 * Metabox template
 */
function wccf_render_meta_box_html() {
	$meta_value = get_post_meta( get_the_ID(), WCCF_META_FIELD, true );
	$meta_value = ! empty( $meta_value ) ? htmlspecialchars( wp_json_encode( $meta_value ) ) : '';
	?>
<div class='admin-wccf-wrapper'>
	<div class='wccf-conditions'><?php echo wccf_field_conditions();?></div>
	<div class='wccf-global-field-wrapper'></div>
	<input id="wccf-fields" type='hidden' name='<?php echo esc_attr( WCCF_META_FIELD ); ?>' value="<?php echo esc_attr( $meta_value ); ?>"
		type="text" />
	<a class='button button-primary button-large wccf-add-field'
		href='#'><?php echo esc_attr( __( 'Add new field', 'woocommerce-custom-fields' ) ); ?></a>
		<input name='wccf_nonce' type='hidden' value='<?php echo esc_attr( wp_create_nonce( 'wccf-form-creator-nonce' ) ); ?>' />
</div> 
	<?php
}

/**
 * Metabox save function
 */
function wccf_save_meta_box() {

	/**
	 * Nonce is missing, stop with saving
	 */
	if ( ! isset( $_POST['wccf_nonce'] ) ) {
		wccf_create_notice( 'Missing nonce.' );
		return false;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['wccf_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'wccf-form-creator-nonce' ) ) {
		wccf_create_notice( 'Nonce is invalid.' );
		return false;
	}

	if ( empty( $_POST[ WCCF_META_FIELD ] ) ) {
		wccf_create_notice( 'No meta data.' );
		return false;
	}

	$meta_value       = sanitize_text_field( wp_unslash( $_POST[ WCCF_META_FIELD ] ) );
	$meta_value_final = JSON_DECODE( $meta_value );

	// Data for field creator form.
	update_post_meta( get_the_ID(), WCCF_META_FIELD, $meta_value_final );

	// Data for displaying fields inside WC Product settings.
	wccf_save_meta_box_wc_product( $meta_value_final );
}
add_action( 'save_post_' . WCCF_POSTTYPE, 'wccf_save_meta_box', 10 );

/**
 * Generate associative array for fields. We won't have to use so many loops when we want to show them on WC Product settings.
 *
 * @param array $field_configs default field config that was sent from wccf settings.
 */
function wccf_save_meta_box_wc_product( $field_configs ) {
	$wccf_meta_wc_fields = array();
	foreach ( $field_configs as $field_config ) {
		$wccf_field_config = array();
		$fields            = $field_config->fields;

		// Assign type.
		$wccf_field_config['type'] = $field_config->type;

		// Create associative array of fields.
		foreach ( $fields as $field ) {
			if ( isset( $field->options ) ) {
				$wccf_field_config['fields'][ $field->key ]['value'] = wccf_create_woo_options( $field->options );
				echo '<pre>';
				print_r( $field );
				echo '</pre>';

			} else {
				$wccf_field_config['fields'][ $field->key ]['value'] = $field->value;
			}
		}
		array_push( $wccf_meta_wc_fields, $wccf_field_config );
	}
	update_post_meta( get_the_ID(), WCCF_META_WC_FIELD, $wccf_meta_wc_fields );
}

/**
 * Converts objects to associative array
 *
 * @param array $options Array of options objects.
 */
function wccf_create_woo_options( $options ) {
	$assoc_options = array();

	foreach ( $options as $option ) {
		$assoc_options[ $option->value ] = $option->text;
	}

	return $assoc_options;
}
