<?php
/**
 * Plugin Name: WooCommerce Custom Fields
 * Plugin URI: https://woocommerce-custom-fields.evaqode.com/
 * Description: Woocommerce fields
 * Version: 1.0
 * Author: Evaqode
 * Author URI: https://evaqode.com
 * Text Domain: woocommerce-custom-fields
 *
 * @package WooCommerceCustomFields
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	/** Check if WooCommerce is activated */
	function is_woocommerce_activated() {
		return class_exists( 'woocommerce' );
	}
}

add_action(
	'init',
	function () {
		if ( is_woocommerce_activated() ) {

			/**  Different constants */
			require_once 'admin/constants.php';

			/** General functions -> Helpers */
			require_once 'admin/general-functions.php';
			require_once 'admin/wccf-notices.php';

			/** Register */
			require_once 'admin/register-admin-assets.php';
			require_once 'admin/register-options-page.php';
			require_once 'admin/register-post-type.php';
			require_once 'admin/admin-fields/register-meta-box.php';
			require_once 'admin/admin-fields/product-data-tabs.php';
			require_once 'admin/wccf-conditions.php';

			/** Register fields on Woocommerce product page */
			require_once 'admin/class-wccf-fields.php';
			require_once 'admin/class-wccf-save.php';
			require_once 'admin/register-woocommerce-fields.php';

			/** Register Custom Post Type */
			wcf_register_post_type();
		}
	}
);
