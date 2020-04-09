<?php

function wccf_field_panels() {
	$panels            = wccf_forbidden_woocommerce_tabs();
	$wccf_panels       = get_option( 'wccf_panels' );
	$wccf_panels_assoc = array();

	foreach ( $wccf_panels as $wccf_panel ) {
		$wccf_panels_assoc[ $wccf_panel['key'] ] = $wccf_panel['value'];
	}

	$panels_final = array_merge($panels, $wccf_panels_assoc);

	$panel_meta = get_post_meta( get_the_ID(), 'wccf_fields_panel', true );

	$select  = '<select name="wccf_fields_panel">';
	$select .= '<option>' . __( 'Select Option', 'woocommerce-custom-fields' ) . '</option>';
	foreach ( $panels_final as $index => $panel ) {
		$selected = '';

		if ( $panel_meta === $index ) {
			$selected = 'selected';
		}

		$select .= "<option $selected value='$index'>$panel</option>";
	}

	$select .= '</select>';

	return $select;
}
