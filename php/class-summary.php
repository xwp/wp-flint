<?php
/**
 * Sets up the Summary custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Summary implements Field_Group {
	/**
	 * Check if the field contains a valid value.
	 *
	 * @return bool
	 */
	public function is_valid() {
		if ( empty( get_field( 'tweet_pitch' ) ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Print the HTML template.
	 */
	public function display() {
		the_field( 'tweet_pitch' );
	}
}
