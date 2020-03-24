<?php
/**
 * Basic WCCF post type created for users.
 *
 * @package WooCommerceCustomFields/PostTypeWCCField
 */

/**
 * Main Post Type function called from INIT.
 */
function wcf_register_post_type() {
	register_post_type(
		WCCF_POSTTYPE,
		array(
			'labels'      => array(
				'name'          => esc_html( __( 'Woocommerce Custom Fields', 'woocommerce-custom-fields' ) ),
				'singular_name' => esc_html( __( 'Woocommerce Custom Field', 'woocommerce-custom-fields' ) ),
				'add_new_item'  => esc_html( __( 'Add new Woocommerce Custom Field', 'woocommerce-custom-fields' ) ),
			),
			'public'      => false,
			'has_archive' => false,
			'show_ui'     => true,
			'supports'    => array( 'title' ),
		)
	);
}
// TODO - icon design.
