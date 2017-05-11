<?php
/**
 * Sets up the Business Model custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Business_Model implements Field_Group {
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
