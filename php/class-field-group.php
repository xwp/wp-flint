<?php
/**
 * Interface for custom field group classes.
 *
 * @package Flint
 */

namespace Flint;

interface Field_Group {

	/**
	 * Register fields.
	 */
	public function register_fields();

	/**
	 * Print the HTML template.
	 */
	public function display();
}
