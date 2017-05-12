<?php
/**
 * Sets up the Feature Color custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Feature_Color implements Field_Group {
	/**
	 * Print the HTML template.
	 */
	public function display() {
		the_field( 'feature_color' );
	}
}
