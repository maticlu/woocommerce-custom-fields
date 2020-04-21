<?php
/**
 * Textarea class implements interface field
 *
 * @package WooCommerceCustomFields/InterfaceField
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render textarea element
 */
class WCCF_Field_Textarea extends WCCF_Field {
	/**
	 * Render field.
	 */
	public function render_field() {
		woocommerce_wp_textarea_input(
			array(
				'id'          => $this->field['fields']['key']['value'],
				'value'       => get_post_meta( get_the_ID(), $this->field['fields']['key']['value'], true ),
				'label'       => $this->field['fields']['title']['value'],
				'data_type'   => 'text',
				'desc_tip'    => true,
				'description' => $this->get_description(),
			)
		);
	}
}
