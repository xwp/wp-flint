<?php
/**
 * Interface for custom field group classes.
 *
 * @package Flint
 */

namespace Flint;

interface Field_Group {
	/**
	 * Check if the field contains a valid value.
	 *
	 * @return bool
	 */
	public function is_valid();

	/**
	 * Print the HTML template.
	 */
	public function display();
}
