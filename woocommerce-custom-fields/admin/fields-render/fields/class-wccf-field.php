<?php
/**
 * Abstract class for fields rendered on WooCommerce
 *
 * @package WooCommerceCustomFields/InterfaceField
 */

defined( 'ABSPATH' ) || exit;

/**
 * Blueprint for field classes
 */
abstract class WCCF_Field {
	/**
	 * Field array with key, value, title.
	 *
	 * @var array $field
	 */
	protected $field;

	/**
	 * Assign field value.
	 *
	 * @param array $field Field array with key, value, title.
	 */
	public function __construct( $field ) {
		$this->field = $field;
	}

	/**
	 * Basic render procedure
	 */
	public function render() {
		if ( $this->validate() ) {
			$this->render_field();
		} else {
			$this->render_incomplete_field();
		}
	}

	/**
	 * Render field
	 */
	abstract protected function render_field();

	/**
	 * Validate field parameters
	 */
	protected function validate() {
		return $this->validate_title_value();
	}
	/**
	 * Every field has title and value. We don't want to repeat ourselves.
	 */
	protected function validate_title_value() {
		if ( empty( $this->field['fields']['title']['value'] ) ) {
			return false;
		}
		if ( empty( $this->field['fields']['key']['value'] ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Validate options if field has them
	 */
	protected function validate_options() {
		if ( empty( $this->field['fields']['options'] ) ) {
			return false;
		}

		if ( count( $this->field['fields']['options']['value'] ) === 0 ) {
			return false;
		}

		foreach ( $this->field['fields']['options']['value'] as $key => $option_value ) {
			if ( empty( $key ) ) {
				return false;
			}
			if ( empty( $option_value ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Render Incomplete field.
	 */
	protected function render_incomplete_field() {
		?>
		<p>Field has incomplete configuration.</p>
		<?php
	}

	/**
	 * Get description
	 */
	protected function get_description() {
		if ( empty( $this->field['fields']['description'] ) ) {
			return '';
		} else {
			return $this->field['fields']['description']['value'];
		}
	}
}
