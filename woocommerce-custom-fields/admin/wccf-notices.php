<?php
/**
 * Metaboxes that are placed inside WCCF Post Type
 *
 * @package WooCommerceCustomFields/Notices
 */

/**
 * Create backend notice while saving WCCF field
 *
 * @param string $error Error message.
 */
function wccf_create_notice( string $error ) {

	add_filter(
		'redirect_post_location',
		function ( $location ) use ( $error ) {
			return add_query_arg( 'wccf-error', $error, $location );
		}
	);
}

/**
 * It renders notice if exists
 */
function wccf_error_messages() {
	if ( isset( $_GET['wccf-error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$error = wp_unslash( $_GET['wccf-error'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, phpcs:ignore WordPress.Security.NonceVerification
		?>
			<div class='error'>
				<p>
					<?php echo esc_attr( $error ); ?>
				</p>
			</div>
		<?php
	}
}
add_action( 'admin_notices', 'wccf_error_messages' );

/**
 * Validate WCCF nonce
 */
