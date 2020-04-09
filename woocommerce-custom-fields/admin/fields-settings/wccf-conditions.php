<?php
/**
 * Conditionally show fields.
 *
 * @package woocommerce-custom-fields/field-settings/conditions
 */

/**
 * Main condition function.
 */
function wccf_field_conditions() {
	$content  = "<div class='wcc-postbox-border wccf-space-wrapper wccf-wrapper'>";
	$content .= '<h3>' . __( 'Conditions', 'woocommerce-custom-fields' ) . '</h3>';
	$content .= '<div class="wcc-conditions">';
	$content .= wccf_field_category_conditions();
	$content .= '</div>';
	$content .= '</div>';

	return $content;
}

/**
 * Category conditions
 */
function wccf_field_category_conditions() {

	// return '<select class="wccf-select-category" style="width:100%;"><option>Shirt</option></select>';
}

/**
 * Load categories
 */

function wccf_search_category() {
	if ( ! isset( $_GET['nonce'] ) ) {
		exit();
	}

	if ( ! isset( $_GET['q'] ) ) {
		return array( 'result' => array() );
	}

	$nonce  = sanitize_text_field( wp_unslash( $_GET['nonce'] ) );
	$search = sanitize_text_field( wp_unslash( $_GET['q'] ) );

	if ( ! wp_verify_nonce( $nonce, 'wccf-form-creator-nonce' ) ) {
		exit();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'name__like' => $search,
		)
	);

	$terms_format = array_map(
		function( $term ) {
			return array(
				'id'   => $term->term_id,
				'text' => $term->name,
			);
		},
		$terms
	);

	echo wp_json_encode( array( 'results' => $terms_format ) );
	exit();
}
 add_action( 'wp_ajax_wccf_search_category', 'wccf_search_category' );
