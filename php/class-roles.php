<?php
/**
 * Sets up the Business Model custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Roles implements Field_Group {
	/**
	 * Print the HTML template.
	 */
	public function display() {
		$this->save();

		if ( have_rows( 'roles' ) ) {
			echo '<ul class="roles">';
			$can_join = is_user_logged_in() && is_single();
			$current_user_id = get_current_user_id();

			if ( $can_join && have_rows( 'roles' ) ) {
				while( have_rows( 'roles' ) ) {
					the_row();
					$user = get_sub_field( 'user' );
					if ( $user && $current_user_id === $user['ID'] ) {
						$can_join = false;
						break;
					}
				}
				reset_rows();
			}
			while( have_rows( 'roles' ) ) {
				the_row();
				?>
				<li>
					<?php
					$user = get_sub_field( 'user' );
					$role = get_sub_field( 'role' );
					if ( 'Other' === $role ) {
						$role = get_sub_field( 'role_title' );
					}
					?>
					<?php if ( $user ) : ?>
						<div class="closed">
							<?php echo wp_kses_post( $user['user_avatar'] ); ?>
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<span class="name"><?php echo esc_html( $user['display_name'] ); ?></span>
						</div>
					<?php elseif ( $can_join ) : ?>
						<div class="open">
							<?php echo wp_kses_post( get_avatar( $current_user_id ) ); ?>
							<span class="avatar empty">
								<a href="javascript:document.getElementById('row-<?php echo esc_attr( get_row_index() ); ?>').submit();" class="join">Join</a>
							</span>
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<form method="post" id="row-<?php echo esc_attr( get_row_index() ); ?>">
								<input type="hidden" name="join-role" value="<?php echo esc_attr( get_row_index() ); ?>" />
							</form>
						</div>
					<?php else : ?>
						<div class="locked">
							<span class="avatar empty"></span>
							<span class="role"><?php echo esc_html( $role ); ?></span>
						</div>
					<?php endif; ?>
				</li>
				<?php
			}
			reset_rows();
			echo '</ul>';
		}
	}

	/**
	 * Save submitted roles form
	 */
	public function save() {
		if ( isset( $_POST['join-role'] ) && is_user_logged_in() ) {
			$row = filter_var( $_POST['join-role'], FILTER_VALIDATE_INT );
			update_sub_field( array( 'roles', $row, 'user' ), get_current_user_id() );
		}
	}
}
