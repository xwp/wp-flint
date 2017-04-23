<?php
/**
 * Sets up the Business Model custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Business_Model implements Field_Group {

	/**
	 * Register fields.
	 */
	public function register_fields() {
		if ( function_exists ('acf_add_local_field_group') ) {
			acf_add_local_field_group(array (
				'key' => 'group_58e32fe892abb',
				'title' => 'Business Model',
				'fields' => array (
					array (
						'key' => 'field_58e33022e9a16',
						'label' => 'Key Partners',
						'name' => 'key_partners',
						'type' => 'textarea',
						'instructions' => 'Who are our key partners?
Who are our key suppliers?
Which key resources are we acquiring from partners?
Which key activities do partners perform?',
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
						'rows' => 4,
						'new_lines' => 'wpautop',
					),
					array (
						'key' => 'field_58e330a1e9a17',
						'label' => 'Key Activities',
						'name' => 'key_activities',
						'type' => 'textarea',
						'instructions' => 'Which key activities do our value propositions require?
Which key activities do our distribution channels require?
Which key activities do our customer relationships require?
Which key activities do our revenue streams require?',
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
						'rows' => 4,
						'new_lines' => 'wpautop',
					),
					array (
						'key' => 'field_58e330bde9a18',
						'label' => 'Key Resources',
						'name' => 'key_resources',
						'type' => 'textarea',
						'instructions' => 'Which key resources do our value propositions require?
Which key resources do our distribution channels require?
Which key resources do our customer relationships require?
Which key resources do our revenue streams require?',
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
						'rows' => 4,
						'new_lines' => 'wpautop',
					),
					array (
						'key' => 'field_58e330cae9a19',
						'label' => 'Value Proposition',
						'name' => 'value_proposition',
						'type' => 'textarea',
						'instructions' => 'What value do we deliver to the customer?
Which one of our customer\'s problems are we helping to solve?
What bundles of products and services are we offering to each customer segment?
Which customer needs are we satisfying?',
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
						'rows' => 4,
						'new_lines' => 'wpautop',
					),
					array (
						'key' => 'field_58e330d5e9a1a',
						'label' => 'Customer Relationships',
						'name' => 'customer_relationships',
						'type' => 'textarea',
						'instructions' => 'What type of relationship do our customer segments expect us to establish and maintain?
How are they integrated with the rest of our business model?
How costly are they?',
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
						'rows' => 4,
						'new_lines' => 'wpautop',
					),
					array (
						'key' => 'field_58e330e1e9a1b',
						'label' => 'Channels',
						'name' => 'channels',
						'type' => 'textarea',
						'instructions' => 'Through which channels do our customer segments want to be reached?
How are our channels integrated?
Which ones work best?
Which ones are most cost-efficient?
How are we integrating with customer routines?',
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
						'rows' => 4,
						'new_lines' => 'wpautop',
					),
					array (
						'key' => 'field_58e330e7e9a1c',
						'label' => 'Customer Segments',
						'name' => 'customer_segments',
						'type' => 'textarea',
						'instructions' => 'For whom are we creating value?
Who are our most important customers?',
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
						'rows' => 4,
						'new_lines' => 'wpautop',
					),
					array (
						'key' => 'field_58e3329ce9a1d',
						'label' => 'Cost Structure',
						'name' => 'cost_structure',
						'type' => 'textarea',
						'instructions' => 'What are the most important costs inherent in our business model?
Which key resources are the most expensive?
Which key activities are the most expensive?',
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
						'rows' => 4,
						'new_lines' => 'wpautop',
					),
					array (
						'key' => 'field_58e332cfe9a1e',
						'label' => 'Revenue Streams',
						'name' => 'revenue_streams',
						'type' => 'textarea',
						'instructions' => 'For what value are our customers willing to pay?
For what do they currently pay?
How are they currently paying?
How would they prefer to pay?',
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
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'left',
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
		?>
		<h2><?php _e( 'Business Model', 'flint' ); ?></h2>
		<div class="canvas">
			<div class="column">
				<div class="key-partners"><h3><?php _e( 'Key Partners', 'flint' ); ?></h3><?php the_field( 'key_partners' ); ?></div>
			</div>
			<div class="column">
				<div class="key-activities"><h3><?php _e( 'Key Activities', 'flint' ); ?></h3><?php the_field( 'key_activities' ); ?></div>
				<div class="key-resources"><h3><?php _e( 'Key Resources', 'flint' ); ?></h3><?php the_field( 'key_resources' ); ?></div>
			</div>
			<div class="column">
				<div class="value-proposition"><h3><?php _e( 'Value Proposition', 'flint' ); ?></h3><?php the_field( 'value_proposition' ); ?></div>
			</div>
			<div class="column">
				<div class="customer-relationships"><h3><?php _e( 'Customer Relationships', 'flint' ); ?></h3><?php the_field( 'customer_relationships' ); ?></div>
				<div class="channels"><h3><?php _e( 'Channels', 'flint' ); ?></h3><?php the_field( 'channels' ); ?></div>
			</div>
			<div class="column">
				<div class="customer-segments"><h3><?php _e( 'Customer Segments', 'flint' ); ?></h3><?php the_field( 'customer_segments' ); ?></div>
			</div>
			<div class="row">
				<div class="cost-structure"><h3><?php _e( 'Cost Structure', 'flint' ); ?></h3><?php the_field( 'cost_structure' ); ?></div>
				<div class="revenue-streams"><h3><?php _e( 'Revenue Streams', 'flint' ); ?></h3><?php the_field( 'revenue_streams' ); ?></div>
			</div>
		</div>
		<?php
	}
}
