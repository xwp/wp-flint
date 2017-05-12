<?php
/**
 * Sets up the Timeline custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Timeline implements Field_Group {
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
