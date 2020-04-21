<?php
/**
 * Metaboxes that are placed inside WCCF Post Type
 *
 * @package woocommerce-custom-fields/fields-settings/meta-boxes
 */

defined( 'ABSPATH' ) || exit;

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
	$fields_meta_value = get_post_meta( get_the_ID(), WCCF_META_FIELD, true );
	$fields_meta_value = ! empty( $fields_meta_value ) ? htmlspecialchars( wp_json_encode( $fields_meta_value ) ) : '';
	?>
<div class='admin-wccf-wrapper'>
	<div class='wccf-panels'><?php echo wccf_field_panels(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<div class='wccf-conditions'><?php echo wccf_field_conditions(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<div class='wccf-global-field-wrapper'>
	<div class="wccf-message wcc-postbox-border wccf-vertical-space"><?php esc_attr_e( 'Click Add new field button to create new Woocommerce field configuration.', 'woocommerce-custom-fields' ); ?></div>
	</div>
	<input id="wccf-fields" type='hidden' name='<?php echo esc_attr( WCCF_META_FIELD ); ?>' value="<?php echo esc_attr( $fields_meta_value ); ?>"
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

	/**
	 * Missing Panel data.
	 */
	if ( ! isset( $_POST['wccf_fields_panel'] ) ) {
		wccf_create_notice( 'Missing panel data.' );
		return;
	}

	/**
	 * Missing incex -> include exclude Value
	 */
	if ( ! isset( $_POST['incex'] ) ) {
		wccf_create_notice( 'Missing Condition Value' );
		return false;
	}

	$incex = wccf_field_get_include_exclude( sanitize_text_field( wp_unslash( $_POST['incex'] ) ) );
	$nonce = sanitize_text_field( wp_unslash( $_POST['wccf_nonce'] ) );
	$panel = sanitize_text_field( wp_unslash( $_POST['wccf_fields_panel'] ) );

	if ( ! wp_verify_nonce( $nonce, 'wccf-form-creator-nonce' ) ) {
		wccf_create_notice( 'Nonce is invalid.' );
		return false;
	}

	if ( empty( $_POST[ WCCF_META_FIELD ] ) ) {
		wccf_create_notice( 'No meta data.' );
		return false;
	}

	$meta_value = sanitize_text_field( wp_unslash( $_POST[ WCCF_META_FIELD ] ) );

	$meta_value_final = JSON_DECODE( $meta_value );

	// Data for field creator form.
	update_post_meta( get_the_ID(), WCCF_META_FIELD, $meta_value_final );

	// Save Fields panel value.
	update_post_meta( get_the_ID(), 'wccf_fields_panel', $panel );

	// Save include exclude radio button value.
	update_post_meta( get_the_ID(), 'wccf_include_exclude', $incex );

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

/**
 * Generate Array of include, exclude rules for current field group
 *
 * @param string $current_rdb_include_exclude Sanitized include exclude radio button value.
 */
function wccf_field_get_include_exclude( $current_rdb_include_exclude ) {
	/**
	 * Possible $current_rdb_include_exclude Values
	 */
	$incex_values         = array( 'include', 'exclude' );
	$include_exclude_meta = array(
		'radio_button' => 'none',
		'include'      => array(),
		'exclude'      => array(),
	);

	if ( ! in_array( $current_rdb_include_exclude, $incex_values, true ) ) {
		return $include_exclude_meta;
	}

	if ( ! isset( $_POST[ $current_rdb_include_exclude ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return $include_exclude_meta;
	}

	$select_values                                        = array_map( 'sanitize_text_field', wp_unslash( $_POST[ $current_rdb_include_exclude ] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$include_exclude_meta['radio_button']                 = $current_rdb_include_exclude;
	$include_exclude_meta[ $current_rdb_include_exclude ] = $select_values;

	return $include_exclude_meta;
}
