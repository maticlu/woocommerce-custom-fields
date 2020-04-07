<?php
/**
 * Woocommerce custom fields Save class. This class gets all fields and save them into database using Woocommerce custom fields post types.
 *
 * @package WooCommerceCustomFields/WCCF_Save
 */

defined( 'ABSPATH' ) || exit;

/**
 * Save all fields into database.
 */
class WCCF_Save {
	/**
	 * Current post id of Woocommerce Custom Fields post type
	 *
	 * @var $post_id
	 */
	private $post_id;

	/**
	 * Constructor gets all fields from WCCF_Fields static method and starts procedure
	 *
	 * @param integer $post_id Current post id.
	 */
	public function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->panels  = WCCF_Fields::get_fields();
		$this->start();
	}

	/**
	 * Loop through panels and field grousp to get to fields. Then Call save_field.
	 */
	private function start() {
		foreach ( $this->panels as $panel ) {
			foreach ( $panel as $field_group ) {
				foreach ( $field_group as $field ) {
					$this->save_field( $field );
				}
			}
		}
	}

	/**
	 * Saves passed field into database.
	 *
	 * @param array $field Current field values that will be stored into database.
	 */
	private function save_field( $field ) {
		$meta_key = $field['fields']['key']['value'];
		if ( ! empty( $_POST[ $meta_key ] ) ) {
			$meta_value = $_POST[ $meta_key ];
			update_post_meta( $this->post_id, $meta_key, $meta_value );
		}
	}
}
