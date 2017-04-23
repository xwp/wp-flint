<?php
/**
 * Sets up the Business Model custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Feature_Color implements Field_Group {

	/**
	 * Register fields.
	 */
	public function register_fields() {
		if ( function_exists ('acf_add_local_field_group') ) {
			acf_add_local_field_group(array (
				'key' => 'group_58e32cf62a155',
				'title' => 'Feature Color',
				'fields' => array (
					array (
						'key' => 'field_58e32cfa3a892',
						'label' => '',
						'name' => 'feature_color',
						'type' => 'color_picker',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'project',
						),
					),
				),
				'menu_order' => 1,
				'position' => 'side',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			));
		};
	}

	/**
	 * Print the HTML template.
	 */
	public function display() {
		the_field( 'feature_color' );
	}
}
