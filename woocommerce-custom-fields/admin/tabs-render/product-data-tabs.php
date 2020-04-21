<?php
/**
 * Extend WooCommerce Product data tabs
 *
 * @package WooCommerceCustomFields/ProductDataTabs
 */

/**
 * Adding custom panels to Woocommerce menus
 *
 * @param array $woocommerce_product_data_tabs WC data tabs.
 */
function wccf_product_data_tabs( $woocommerce_product_data_tabs ) {
	$tabs = get_option( WCCF_PANELS );
	foreach ( $tabs as $tab ) {
		$tab_id = wccf_tab_id( $tab['key'] );
		$icon   = wccf_get_icon_from_classes( $tab );
		$woocommerce_product_data_tabs[ sanitize_title( $tab['key'] ) ] = array(
			'label'    => $tab['value'],
			'target'   => $tab_id,
			'class'    => array( 'wccf_tab', $icon ),
			'priority' => 1000,
		);
	};
	return $woocommerce_product_data_tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'wccf_product_data_tabs', 10, 1 );

/**
 * Add Custom Panels
 */
function wccf_add_custom_panels() {
	$tabs        = get_option( WCCF_PANELS );
	$tab_content = '';
	foreach ( $tabs as $tab ) {
		$tab_key      = sanitize_title( $tab['key'] );
		$tab_id       = wccf_tab_id( $tab['key'] );
		$tab_content .= '<div id="' . $tab_id . '" class="panel woocommerce_options_panel">';
		ob_start();
		do_action( 'woocommerce_product_options_' . $tab_key );
		$tab_content .= ob_get_clean();
		$tab_content .= '</div>';
	}
	echo $tab_content; // phpcs:ignore WordPress.Security.EscapeOutput
}
add_action( 'woocommerce_product_data_panels', 'wccf_add_custom_panels', 10 );

/**
 * Generate TAB ID
 *
 * @param string $tabname Key od the tab used for attribute ID.
 */
function wccf_tab_id( $tabname ) {
	return 'wccf_' . sanitize_title( $tabname );
}

/**
 * Get icon from Classes. Database stores classes, this functions gets icon class.
 *
 * @param array $tab All tab information stored in array.
 */
function wccf_get_icon_from_classes( $tab ) {
	if ( empty( $tab['icon'] ) ) {
		return '';
	}

	preg_match( '/icon-.*\s/', $tab['icon'], $icon );

	return count( $icon ) ? $icon[0] : '';
}
