<?php
/**
 * Sets up the Business Model custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Summary implements Field_Group {

	/**
	 * Register fields.
	 */
	public function register_fields() {
		if ( function_exists ('acf_add_local_field_group') ) {
			acf_add_local_field_group(array (
				'key' => 'group_58e336d7dd36b',
				'title' => 'Summary',
				'fields' => array (
					array (
						'key' => 'field_58e336f274642',
						'label' => 'Tweet Pitch',
						'name' => 'tweet_pitch',
						'type' => 'textarea',
						'instructions' => '140 character summary.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'maxlength' => 140,
						'rows' => 4,
						'new_lines' => 'wpautop',
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
				'menu_order' => 0,
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
		the_field( 'tweet_pitch' );
	}
}
