<?php
/**
 * Sets up the Business Model custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Timeline implements Field_Group {

	/**
	 * Register fields.
	 */
	public function register_fields() {
		if ( function_exists ('acf_add_local_field_group') ) {
			acf_add_local_field_group(array (
				'key' => 'group_58e32e0e5acef',
				'title' => 'Timeline',
				'fields' => array (
					array (
						'key' => 'field_58e32e2e10089',
						'label' => 'Key Dates',
						'name' => 'key_dates',
						'type' => 'repeater',
						'instructions' => 'Date estimates for events such as Project Kickoff, MVP, or Launch.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => 'field_58e32eb71008b',
						'min' => 0,
						'max' => 0,
						'layout' => 'row',
						'button_label' => 'Add Key Date',
						'sub_fields' => array (
							array (
								'key' => 'field_58e32e901008a',
								'label' => 'Date',
								'name' => 'date',
								'type' => 'date_picker',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'display_format' => 'Y-m-d',
								'return_format' => 'F j, Y',
								'first_day' => 1,
							),
							array (
								'key' => 'field_58e32eb71008b',
								'label' => 'Event',
								'name' => 'event',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array (
								'key' => 'field_58e32edd1008c',
								'label' => 'Description',
								'name' => 'description',
								'type' => 'textarea',
								'instructions' => '(optional)',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'maxlength' => '',
								'rows' => 2,
								'new_lines' => 'wpautop',
							),
						),
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
				'position' => 'normal',
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
		if ( have_rows( 'key_dates' ) ) {
		?>
		<h2><?php _e( 'Timeline', 'flint' ); ?></h2>
		<ul>
			<?php while ( have_rows( 'key_dates' ) ): the_row(); ?>
				<li>
					<div class="date"><?php the_sub_field( 'date' ); ?></div>
					<div class="event">
						<h3><?php the_sub_field( 'event' ); ?></h3>
						<?php the_sub_field( 'description' ); ?>
					</div>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php
		}
	}
}
