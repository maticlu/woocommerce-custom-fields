<?php

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
		<?php echo settings_errors(); ?>
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

	register_setting( 'wccf_panels_header', WCCF_PANELS, array( 'sanitize_callback' => 'khm' ) );
}

function khm( $fields ) {
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
	$panels = get_option( 'wccf_panels' );
	$panels = is_array( $panels ) ? $panels : array();

	if ( count( $panels ) > 0 ) {
		foreach ( $panels as $index => $panel ) {
			?>
			<div class='wccf-panels-wrapper'>
			<input type='text' required name='<?php echo esc_html( WCCF_PANELS ); ?>[<?php echo esc_attr( $index ); ?>][value]' class='wccf_panels wccf_input_name' value='<?php echo esc_attr( $panel['value'] ); ?>' />
			<input type='text' required name='<?php echo esc_html( WCCF_PANELS ); ?>[<?php echo esc_attr( $index ); ?>][key]' class='wccf_panels wccf_input_key' value='<?php echo esc_attr( $panel['key'] ); ?>' />   
			<?php if ( $index > 0 ) { ?>
			<a class='wccf_panels_remove' href='#'>Remove</a>
			<?php } ?>			
			</div>
			<?php
		}
	} else {
		?>
	<div class="wccf-panels-wrapper">
	<input type="text" name="<?php echo esc_html( WCCF_PANELS ); ?>[0][value]" class="wccf_panels" value=""/>
	<input type='text' name='<?php echo esc_html( WCCF_PANELS ); ?>[0][key]' class='wccf_panels' value="" /> 
	</div>
		<?php
	}
}
add_action( 'admin_init', 'wccf_display_panel_fields' );
