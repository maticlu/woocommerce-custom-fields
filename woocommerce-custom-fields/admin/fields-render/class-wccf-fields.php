<?php
/**
 * Woocommerce custom fields main field render class
 *
 * @package woocommerce-custom-fields/fields-render/wccf-fields
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main WCCF class which renders all fields created in admin
 */
class WCCF_Fields {

	/**
	 * All fields rendered in Woocommerce admin
	 *
	 * @var fields
	 */
	private $fields = array();

	/**
	 * Constructor
	 *
	 * @param number $post_id Current product id.
	 */
	public function __construct( $post_id ) {
		$this->fields = self::get_fields( $post_id );
		$this->render_fields();
	}
	/**
	 * Render fields on the right panels
	 */
	private function render_fields() {
		foreach ( $this->fields as $field_panel => $field_groups ) {
			add_action(
				'woocommerce_product_options_' . $field_panel,
				function () use ( $field_groups, $field_panel ) {
					$this->render( $field_groups, $field_panel );
				},
				10
			);
		}
	}
	/**
	 * Current panel render
	 *
	 * @param array $field_groups Group of fields.
	 */
	private function render( $field_groups ) {
		foreach ( $field_groups as $field_group ) {
			foreach ( $field_group as $field ) {
				$function = 'render_field_' . $field['type'];
				if ( method_exists( $this, $function ) ) {
					$this->$function( $field );
				}
			}
		}
	}
	/**
	 * Fields : Textbox
	 *
	 * @param array $field  Textbox data.
	 */
	private function render_field_textbox( $field ) {
		$input = new WCCF_Field_Textbox( $field );
		$input->render();

	}
	/**
	 * Fields : Textarea
	 *
	 * @param array $field  Textbox data.
	 */
	private function render_field_textarea( $field ) {
		$input = new WCCF_Field_Textarea( $field );
		$input->render();
	}
	/**
	 * Fields : Radio buttons
	 *
	 * @param array $field  Radio button data.
	 */
	private function render_field_radiobutton( $field ) {
		$input = new WCCF_Field_Radio( $field );
		$input->render();
	}
	/**
	 * Fields : Radio buttons
	 *
	 * @param array $field  Radio button data.
	 */
	private function render_field_dropdown( $field ) {
		$input = new WCCF_Field_Dropdown( $field );
		$input->render();
	}
	/**
	 * Fields : Checkboxes
	 *
	 * @param array $field  Radio button data.
	 */
	private function render_field_checkbox( $field ) {
		$input = new WCCF_Field_Checkbox( $field );
		$input->render();
	}
	/**
	 * Get all fields from all Woocommerce Custom Field posts
	 *
	 * @param number $product_id Current product id.
	 */
	public static function get_fields( $product_id ) {
		$fields = array();
		$args   = array(
			'post_type'      => WCCF_POSTTYPE,
			'posts_per_page' => -1,
		);

		$query = new WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return false;
		}

		while ( $query->have_posts() ) {
			$query->the_post();
			$field_config = get_post_meta( get_the_ID(), WCCF_META_WC_FIELD, true );
			$panel        = get_post_meta( get_the_ID(), WCCF_FIELDS_PANEL, true );
			$conditions   = get_post_meta( get_the_ID(), WCCF_FIELDS_INCLUDE_EXCLUDE, true );
			if ( empty( $fields[ $panel ] ) ) {
				$fields[ $panel ] = array();
			}

			if ( self::fieldgroup_passed_condition_check( $conditions, $product_id ) ) {
				array_push( $fields[ $panel ], $field_config );
			};
		}

		wp_reset_postdata();

		return $fields;
	}

	/**
	 * Checks if fields can be rendered for current product.
	 *
	 * @param array  $conditions Array of include, exclude conditions.
	 * @param number $product_id Current product id.
	 */
	private static function fieldgroup_passed_condition_check( $conditions, $product_id ) {
		if ( empty( $conditions['radio_button'] ) ) {
			return true;
		}

		if ( 'none' === $conditions['radio_button'] ) {
			return true;
		}

		$condition_categories = $conditions[ $conditions['radio_button'] ];

		if ( empty( $condition_categories ) ) {
			return true;
		}

		$product_categories = wp_get_post_terms( $product_id, 'product_cat' );

		$product_category_ids = array_map(
			function( $product_category ) {
				return $product_category->term_id;
			},
			$product_categories
		);

		$intersect = array_intersect( $condition_categories, $product_category_ids );

		if ( 'exclude' === $conditions['radio_button'] && ! count( $intersect ) ) {
			return true;
		}

		if ( 'include' === $conditions['radio_button'] && count( $intersect ) ) {
			return true;
		}

		return false;
	}
}
