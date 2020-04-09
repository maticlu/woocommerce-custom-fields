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
			require_once 'admin/general/constants.php';
			require_once 'admin/general/functions.php';
			require_once 'admin/general/register-admin-assets.php';
			require_once 'admin/general/register-post-type.php';

			require_once 'admin/fields-settings/wccf-notices.php';
			require_once 'admin/fields-settings/wccf-conditions.php';
			require_once 'admin/fields-settings/wccf-panels.php';
			require_once 'admin/fields-settings/register-meta-box.php';

			require_once 'admin/fields-render/class-wccf-fields-save.php';
			require_once 'admin/fields-render/class-wccf-fields.php';
			require_once 'admin/fields-render/register-woocommerce-fields.php';

			require_once 'admin/tabs-render/product-data-tabs.php';
			require_once 'admin/tabs-render/register-panel-options.php';

			// /** Register fields on Woocommerce product page */

			// /** Register Custom Post Type */
			wcf_register_post_type();
		}
	}
);
