<?php
/**
 * Description: General functions used by plugin
 *
 * @package WooCommerceCustomFields/GeneralFunctions
 */

/**  Change here capability who can use this plugin */
function wccf_capability() {
	return apply_filters( 'wccf_options_page_capability', 'administrator' );
}

/**  Change here capability who can use this plugin */
function wccf_forbidden_woocommerce_tabs() {
	return array( 'general', 'inventory', 'shipping', 'linked_products', 'attribute', 'variations', 'advanced' );
}
