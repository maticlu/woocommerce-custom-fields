<?php
/**
 * All WooCommerce Icons
 * Source: https://github.com/woocommerce/woocommerce-icons
 *
 * @package woocommerce-custom-fields/icon-list
 */

/**
 * List of WooCommerce Icons
 */
function get_woo_icons() {
	$icons = array(
		'icon-virtual',
		'icon-downloadable',
		'icon-grouped',
		'icon-variable',
		'icon-contract',
		'icon-expand',
		'icon-simple',
		'icon-plus',
		'icon-right',
		'icon-up',
		'icon-down',
		'icon-left',
		'icon-image',
		'icon-link',
		'icon-calendar',
		'icon-processing',
		'icon-view',
		'icon-status-processing',
		'icon-status-pending',
		'icon-status-cancelled',
		'icon-status-refunded',
		'icon-status-completed',
		'icon-status-failed',
		'icon-check',
		'icon-query',
		'icon-truck-1',
		'icon-truck-2',
		'icon-globe',
		'icon-gear',
		'icon-cart',
		'icon-card',
		'icon-stats',
		'icon-star-full',
		'icon-star-empty',
		'icon-up-down',
		'icon-reports',
		'icon-search',
		'icon-search-2',
		'icon-user2',
		'icon-windows',
		'icon-note',
		'icon-east',
		'icon-north',
		'icon-attributes',
		'icon-inventory',
		'icon-mail',
		'icon-south',
		'icon-west',
		'icon-share',
		'icon-refresh',
		'icon-navigation',
		'icon-on-hold',
		'icon-external',
		'icon-expand-2',
		'icon-contract-2',
		'icon-phone',
		'icon-user',
		'icon-status',
		'icon-user-fill',
		'icon-phone-fill',
		'icon-status-fill',
		'icon-woo',
		'icon-coupon',
		'icon-limit',
		'icon-restricted',
		'icon-edit',
		'icon-ccv',
		'icon-storefront',
	);

	return array_reduce(
		$icons,
		function( $icon_content, $icon ) {
			$icon_content .= "<span class='wccf-icon $icon'></span>";
			return $icon_content;
		},
		''
	);
}
