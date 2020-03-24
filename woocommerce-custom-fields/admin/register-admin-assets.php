<?php
/**
 * Description: CSS and JS assets
 *
 * @package WooCommerceCustomFields/Assets
 */

/** Enqueue assets */
function wccf_admin_styles() {
	$current_post_type = get_post_type();
	$current_screen    = get_current_screen();

	if ( 'wcc-fields' === $current_post_type ) {
		wp_enqueue_script( 'wcc-fields-posttype', plugins_url( WCCF_ROOT . '/admin/assets/script.js' ), '', '1.0', true );
		wp_enqueue_style( 'wccf-admin-style', plugins_url( WCCF_ROOT . '/admin/assets/style.css' ), array(), '1.0' );
	}

	if ( 'wcc-fields_page_woocommerce-custom-fields' === $current_screen->id ) {
		wp_enqueue_script( 'wcc-fields-panel', plugins_url( WCCF_ROOT . '/admin/assets/panel.js' ), '', '1.0', true );
	}
}

add_action( 'admin_enqueue_scripts', 'wccf_admin_styles' );
