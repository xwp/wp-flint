<?php
/**
 * Sets up the Summary custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Summary implements Field_Group {
	/**
	 * Print the HTML template.
	 */
	public function display() {
		the_field( 'tweet_pitch' );
	}
}
