<?php
/**
 * Notices for field settings page
 *
 * @package woocommerce-custom-fields/tabs-render/register-panel-options
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create settings page for managing Woocommerce settings panels
 */
function wccf_add_custom_fields() {
	$options_page_name = apply_filters( 'wcf_options_page_name', 'Add Panels' );
	add_submenu_page( 'edit.php?post_type=wcc-fields', 'Woocommerce Custom Fields', $options_page_name, wccf_capability(), 'woocommerce-custom-fields', 'wccf_render_settings', 100000 );
}
add_action( 'admin_menu', 'wccf_add_custom_fields' );

/**
 * Template for settings page
 */
function wccf_render_settings() {   ?>
	<div class="wrap">
		<?php echo settings_errors(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 
		<div id="icon-options-general"></div>
		<h1><?php echo esc_html( __( 'Woocommerce Custom Fields', 'woocommerce-custom-fields' ) ) . ' - ' . esc_html( __( 'create Woocommerce panels', 'woocommerce-custom-fields' ) ); ?>
		</h1>
		<form method="post" action="options.php">
			<?php
				settings_fields( 'wccf_panels_header' );
				do_settings_sections( 'woocommerce-custom-fields' );
				submit_button();
			?>
		</form>
	</div>	
		<?php
}

/**
 * Fields for settings page
 */
function wccf_display_panel_fields() {
	add_settings_section( 'wccf_panel_section', '', '', 'woocommerce-custom-fields' );
	add_settings_section( 'wccf_panel_section_button', '', 'wccf_add_new_panel', 'woocommerce-custom-fields' );

	add_settings_field( 'wccf_panels', 'Add new Woocommerce panels', 'wccf_panels', 'woocommerce-custom-fields', 'wccf_panel_section' );

	register_setting( 'wccf_panels_header', WCCF_PANELS, array( 'sanitize_callback' => 'wccf_fields_panels_sanitize' ) );
}
/**
 * Sanitize fields, when saving Add panels section
 *
 * @param array $fields All fields created on Add panels section.
 */
function wccf_fields_panels_sanitize( $fields ) {
	$old_fields = get_option( WCCF_PANELS );

	foreach ( $fields as $field ) {
		foreach ( $field as $value ) {
			if ( empty( $value ) ) {
				add_settings_error( WCCF_PANELS, 'a', 'One of the fields was empty!', 'error' );
				return $old_fields;
			}
		}
	}

	return $fields;
}

/**
 * Button for settings page
 */
function wccf_add_new_panel() {
	echo '<a class="wccf-add-new-panel" href="#">Add New Panel</a>';
}

/**
 * Render panel fields from database.
 */
function wccf_panels() {
	$panels = get_option( WCCF_PANELS );
	$panels = is_array( $panels ) ? $panels : array();

	?>
	<div class='wccf-panels-outer-wrapper'>
	<p style="display:none;" class="wccf-no-panels"><?php echo esc_attr_e( 'Add new panel', 'woocommerce-custom-fields' ); ?></p>
	<?php

	if ( count( $panels ) > 0 ) {
		foreach ( $panels as $index => $panel ) {
			wccf_panel_template( $index, $panel );
		}
	} else {
		?>
		<p class="wccf-no-panels"><?php echo esc_attr_e( 'Add new panel', 'woocommerce-custom-fields' ); ?></p>;
		<?php
	}
	?>
	</div>
	<div class='wccf-icon-selector'>
		<div class="wccf-icon-selector-bg"></div>
		<div class="wccf-icon-selector-inner">
			<?php echo wp_kses_post( get_woo_icons() ); ?>
		</div>
	</div>
	<?php
}
add_action( 'admin_init', 'wccf_display_panel_fields' );

/**
 * HTML for single panel
 *
 * @param number  $index Array index of the panel.
 * @param array   $panel Array of panel information - key, value and icon.
 * @param boolean $return Return or echo output.
 */
function wccf_panel_template( $index, $panel, $return = false ) {
	$icon = ! empty( $panel['icon'] ) ? $panel['icon'] : 'wccf-icon';

	if ( $return ) {
		ob_start();
	}

	?>
	<div class='wccf-panels-wrapper'>
		<input type='text' placeholder="<?php esc_attr_e( 'Panel name', 'woocommerce-custom-fields' ); ?>" required name='<?php echo esc_html( WCCF_PANELS ); ?>[<?php echo esc_attr( $index ); ?>][value]' class='wccf_panels wccf_input_name' value='<?php echo esc_attr( $panel['value'] ); ?>' />
		<input type='text' placeholder="<?php esc_attr_e( 'Panel key', 'woocommerce-custom-fields' ); ?>" required name='<?php echo esc_html( WCCF_PANELS ); ?>[<?php echo esc_attr( $index ); ?>][key]' class='wccf_panels wccf_input_key' value='<?php echo esc_attr( $panel['key'] ); ?>' />  
		<input required name='<?php echo esc_html( WCCF_PANELS ); ?>[<?php echo esc_attr( $index ); ?>][icon]' type='hidden' value='<?php echo esc_attr( $icon ); ?>' />
		<div class='<?php echo esc_attr( $icon ); ?>' data-index='<?php echo esc_attr( $index ); ?>'><span class='wccf-icon-text'><?php esc_attr_e( 'icon', 'woocommerce-custom-fields' ); ?></span></div>
		<a class='wccf-panels-remove' href='#'><?php echo esc_attr__( 'Remove', 'woocommerce-custom-fields' ); ?></a>		
	</div>
	<?php

	if ( $return ) {
		return ob_get_clean();
	}
}
