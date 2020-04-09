<?php
/**
 * Abstract class for fields rendered on WooCommerce
 *
 * @package WooCommerceCustomFields/InterfaceField
 */

defined( 'ABSPATH' ) || exit;

interface WCCF_Field {
	/**
	 * Validate field parameters
	 */
	public function validate();
	/**
	 * Render field
	 */
	public function render();
}
