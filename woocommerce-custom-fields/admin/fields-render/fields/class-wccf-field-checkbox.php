<?php
/**
 * Checkbox class implements interface field
 *
 * @package WooCommerceCustomFields/InterfaceField
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render checkbox element
 */
class WCCF_Field_Checkbox extends WCCF_Field {
	/**
	 * Render field.
	 */
	public function render_field() {
		woocommerce_wp_checkbox(
			array(
				'id'          => $this->field['fields']['key']['value'],
				'label'       => $this->field['fields']['title']['value'],
				'value'       => get_post_meta( get_the_ID(), $this->field['fields']['key']['value'], true ),
				'data_type'   => 'text',
				'desc_tip'    => true,
				'description' => $this->get_description(),
			)
		);
	}
}
