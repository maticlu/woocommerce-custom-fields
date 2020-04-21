<?php
/**
 *  Radio class implements interface field
 *
 * @package WooCommerceCustomFields/InterfaceField
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render radio element
 */
class WCCF_Field_Radio extends WCCF_Field {
	/**
	 * Validate if field is OK.
	 */
	public function validate() {
		return $this->validate_title_value() && $this->validate_options();
	}
	/**
	 * Render field.
	 */
	public function render_field() {
		woocommerce_wp_radio(
			array(
				'id'          => $this->field['fields']['key']['value'],
				'label'       => $this->field['fields']['title']['value'],
				'value'       => get_post_meta( get_the_ID(), $this->field['fields']['key']['value'], true ),
				'data_type'   => 'text',
				'options'     => $this->field['fields']['options']['value'],
				'desc_tip'    => true,
				'description' => $this->get_description(),
			)
		);
	}
}
