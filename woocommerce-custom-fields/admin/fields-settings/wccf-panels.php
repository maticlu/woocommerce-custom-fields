<?php
/**
 * Notices for field settings page
 *
 * @package woocommerce-custom-fields/field-settings/panels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Panel selector
 */
function wccf_field_panels() {
	$panels            = wccf_forbidden_woocommerce_tabs();
	$wccf_panels       = get_option( WCCF_PANELS, array() );
	$wccf_panels_assoc = array();

	if ( is_array( $wccf_panels ) ) {
		foreach ( $wccf_panels as $wccf_panel ) {
			$wccf_panels_assoc[ $wccf_panel['key'] ] = $wccf_panel['value'];
		}
	}

	$panels_final = array_merge( $panels, $wccf_panels_assoc );

	$panel_meta = get_post_meta( get_the_ID(), WCCF_FIELDS_PANEL, true );

	$select  = '<select name="' . WCCF_FIELDS_PANEL . '">';
	$select .= '<option>' . __( 'Select Option', 'woocommerce-custom-fields' ) . '</option>';
	foreach ( $panels_final as $index => $panel ) {
		$selected = '';

		if ( $panel_meta === $index ) {
			$selected = 'selected';
		}

		$select .= "<option $selected value='$index'>" . esc_attr( $panel ) . '</option>';
	}

	$select .= '</select>';

	return $select;
}
