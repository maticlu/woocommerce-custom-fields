<?php
/**
 * Woocommerce custom fields main field render class
 *
 * @package WooCommerceCustomFields/WCCF_Fields
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
	 */
	private function __construct() {
		$this->fields = self::get_fields();
		$this->render_fields();
	}
	/**
	 * Render fields on the right panels
	 */
	private function render_fields() {
		foreach ( $this->fields as $field_panel => $fields ) {
			add_action( 'woocommerce_product_options_' . $field_panel, array( $this, 'render_' . $field_panel ), 10 );
		}
	}
	/**
	 * Renders pricing panel
	 */
	public function render_pricing() {
		$field_groups = $this->fields['pricing'];
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
		woocommerce_wp_text_input(
			array(
				'id'        => $field['fields']['key']['value'],
				'value'     => get_post_meta( get_the_ID(), $field['fields']['key']['value'], true ),
				'label'     => $field['fields']['title']['value'],
				'data_type' => 'text',
			)
		);
	}
	/**
	 * Fields : Textarea
	 *
	 * @param array $field  Textbox data.
	 */
	private function render_field_textarea( $field ) {
		woocommerce_wp_textarea_input(
			array(
				'id'        => $field['fields']['key']['value'],
				'value'     => get_post_meta( get_the_ID(), $field['fields']['key']['value'], true ),
				'label'     => $field['fields']['title']['value'],
				'data_type' => 'text',
			)
		);
	}
	/**
	 * Fields : Textarea
	 *
	 * @param array $field  Radio button data.
	 */
	private function render_field_radiobutton( $field ) {
		woocommerce_wp_radio(
			array(
				'id'        => $field['fields']['key']['value'],
				'value'     => get_post_meta( get_the_ID(), $field['fields']['key']['value'], true ),
				'label'     => $field['fields']['title']['value'],
				'data_type' => 'text',
			)
		);
	}
	/**
	 * Get all fields from all Woocommerce Custom Field posts
	 */
	public static function get_fields() {

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
			$panel        = 'pricing';
			if ( empty( $fields[ $panel ] ) ) {
				$fields[ $panel ] = array();
			}

			array_push( $fields[ $panel ], $field_config );
		}

		wp_reset_postdata();

		return $fields;
	}
}
/**
 * Helper function, dumps content on popup
 *
 * @param any $content Anything.
 */
function print_admin( $content ) {
	echo '<pre style="position:fixed;left:0;top:0;width:100%;height:80vh;z-index:1000000000;overflow:scroll;background:white;">';
	print_r( $content );
	echo '</pre>';
}
