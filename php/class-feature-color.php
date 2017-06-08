<?php
/**
 * Sets up the Feature Color custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Feature_Color implements Field_Group {
	/**
	 * Check if the field contains a valid value.
	 *
	 * @return bool
	 */
	public function is_valid() {
		$value = get_field( 'feature_color' );
		if ( empty( $value ) || '#' !== substr( $value, 0, 1 ) || 7 !== strlen( $value ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Print the HTML template.
	 */
	public function display() {
		the_field( 'feature_color' );
	}
}
