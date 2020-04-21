<?php
/**
 * Conditionally show fields.
 *
 * @package woocommerce-custom-fields/field-settings/conditions
 */

defined( 'ABSPATH' ) || exit;

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
	$meta_condition_radio = get_post_meta( get_the_ID(), WCCF_FIELDS_INCLUDE_EXCLUDE, true );
	$meta_actual_value    = ! empty( $meta_condition_radio['radio_button'] ) ? $meta_condition_radio['radio_button'] : 'none';

	/**
	 * Created array of buttons for easier assigment of checked value. We don't want to repeat ourselves.
	 */
	$conditional_radio_buttons = array(
		'none'    => 'No condition',
		'include' => 'Include categories',
		'exclude' => 'Exclude categories',
	);

	$conditional_select_values = array(
		'include' => 'Show if product contains the following categories: ',
		'exclude' => 'Exclude products which contain following categories: ',
	);

	$content = '<div class="wccf-gray-wrap">';

	foreach ( $conditional_radio_buttons as $name => $label ) {
		$checked  = $meta_actual_value === $name ? 'checked' : '';
		$content .= "<div class='wccf-vertical-space'><label for='wccf-$name'>$label</label><input id='wccf-$name' $checked type='radio' name='incex' value='$name' /></div>";
	}

	$content .= '</div>';

	foreach ( $conditional_select_values as $name => $label ) {
		$hidden  = 'hidden';
		$options = '';

		if ( $meta_actual_value === $name ) {
			$hidden = '';
			if ( ! empty( $meta_condition_radio[ $name ] ) ) {
				$option_values = $meta_condition_radio[ $name ];
				$options       = wccf_generate_product_cat_options_from_ids( $option_values );
			}
		}

		$content .= "<div data-show='wccf-$name' class='$hidden wccf-toggle-condition'>";
		$content .= "<div class='condition-label wccf-row-label wccf-vertical-space'>$label</div>";
		$content .= "<select class='wccf-select-category' name='$name" . '[]' . "' style='width:100%;' multiple='multiple'>$options</select>";
		$content .= '</div>';
	}

	return $content;
}

/**
 * Load categories
 */
function wccf_search_category() {
	if ( ! isset( $_GET['nonce'] ) ) {
		exit();
	}

	if ( ! isset( $_GET['q'] ) ) {
		$search = '';
		$number = 5;
	} else {
		$search = sanitize_text_field( wp_unslash( $_GET['q'] ) );
		$number = 0;
	}

	$nonce = sanitize_text_field( wp_unslash( $_GET['nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'wccf-form-creator-nonce' ) ) {
		exit();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'name__like' => $search,
			'number'     => $number,
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

/**
 * Generates option value HTML for provided product category array.
 *
 * @param array $categories Array of product categories.
 */
function wccf_generate_product_cat_options_from_ids( $categories ) {
	if ( ! count( $categories ) ) {
		return '';
	}
	$product_categories = get_terms(
		array(
			'hide_empty' => false,
			'taxonomy'   => 'product_cat',
			'include'    => $categories,
		)
	);

	if ( empty( $product_categories ) ) {
		return '';
	};

	return array_reduce(
		$product_categories,
		function( $options, $product_category ) {
			$options .= "<option selected='selected' value='{$product_category->term_id}'>{$product_category->name}</option>";
			return $options;
		},
		''
	);
}
