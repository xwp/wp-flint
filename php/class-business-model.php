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
				<div class="delivery-activities"><h3><?php _e( 'Delivery Activities', 'flint' ); ?></h3><?php the_field( 'delivery_activities' ); ?></div>
			</div>
			<div class="column">
				<div class="value-proposition"><h3><?php _e( 'Value Proposition', 'flint' ); ?></h3><?php the_field( 'value_proposition' ); ?></div>
			</div>
			<div class="column">
				<div class="customer-profile"><h3><?php _e( 'Customer Profile', 'flint' ); ?></h3><?php the_field( 'customer_profile' ); ?></div>
			</div>
			<div class="row">
				<div class="cost-structure"><h3><?php _e( 'Cost Structure', 'flint' ); ?></h3><?php the_field( 'cost_structure' ); ?></div>
				<div class="revenue-streams"><h3><?php _e( 'Revenue Streams', 'flint' ); ?></h3><?php the_field( 'revenue_streams' ); ?></div>
			</div>
		</div>
		<?php
	}
}
