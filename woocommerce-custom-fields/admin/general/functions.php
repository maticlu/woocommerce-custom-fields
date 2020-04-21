<?php
/**
 * Description: General functions used by plugin
 *
 * @package woocommerce-custom-fields/general/functions
 */

/**  Change here capability who can use this plugin */
function wccf_capability() {
	return apply_filters( 'wccf_options_page_capability', 'administrator' );
}

/**  Change here capability who can use this plugin */
function wccf_forbidden_woocommerce_tabs() {
	return array(
		'pricing'                => 'General',
		'inventory_product_data' => 'Inventory',
		'shipping'               => 'Shipping',
		'related'                => 'Linked Products',
		'advanced'               => 'Advanced',
	);
}
