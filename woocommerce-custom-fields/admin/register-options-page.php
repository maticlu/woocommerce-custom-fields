<?php
/**
 * Description: CSS and JS assets
 *
 * @package WooCommerceCustomFields/OptionPages
 */

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

	register_setting( 'wccf_panels_header', 'wccf_panels' );
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
			<input type='text' name='<?php echo esc_html( WCCF_PANELS ); ?>[]' class='wccf_panels' value=' <?php echo esc_attr( $panel ); ?>' />	
			<?php if ( $index > 0 ) { ?>
			<a class='wccf-panels-remove' href='#'>Remove</a>
			<?php } ?>			
			</div>
			<?php
		}
	} else {
		?>
	<div class="wccf-panels-wrapper">
	<input type="text" name="' . WCCF_PANELS . '[]" class="wccf_panels" value=""/>
	</div>
		<?php
	}
}
add_action( 'admin_init', 'wccf_display_panel_fields' );
