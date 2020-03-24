<?php

function wccf_wc_option_group( $input_content ) {
	$content  = '<div class="options_group">';
	$content .= $input_content;
	$content .= '</div>';

	return $content;
}


function wccf_register_wc_fields() {
	if ( ! isset( $_GET['post'] ) ) {
		return false;
	}
	$post_id = $_GET['post'];
	if ( get_post_type( $post_id ) == 'product' ) {
		new WCCF_Fields();
	}
}

add_action( 'admin_init', 'wccf_register_wc_fields' );

function wccf_save_wc_fields( $post_id ) {
	new WCCF_Save( $post_id );
}
add_action( 'woocommerce_process_product_meta', 'wccf_save_wc_fields' );
