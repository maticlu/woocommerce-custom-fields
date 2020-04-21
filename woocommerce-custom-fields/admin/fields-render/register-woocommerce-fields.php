<?php
/**
 * Show Fields on Product Admin page
 *
 * @package woocommerce-custom-fields/fields-render/register-woocommerce-fields
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render Fields
 */
function wccf_register_wc_fields() {
	if ( ! isset( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return false;
	}
	$post_id = sanitize_text_field( wp_unslash( $_GET['post'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	if ( get_post_type( $post_id ) === 'product' ) {
		new WCCF_Fields( $post_id );
	}
}
add_action( 'admin_init', 'wccf_register_wc_fields' );


/**
 * Process Fields
 *
 * @param int $post_id Post ID of the current product.
 */
function wccf_save_wc_fields( $post_id ) {
	new WCCF_Fields_Save( $post_id );
}
add_action( 'woocommerce_process_product_meta', 'wccf_save_wc_fields' );
