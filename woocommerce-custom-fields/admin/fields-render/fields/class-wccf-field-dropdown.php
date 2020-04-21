<?php
/**
 * Select class implements interface field
 *
 * @package WooCommerceCustomFields/InterfaceField
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render select element
 */
class WCCF_Field_Dropdown extends WCCF_Field {
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
		woocommerce_wp_select(
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
