<?php
/**
 * Sets up the Roles custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Roles implements Field_Group {

	/**
	 * Handle Role requests.
	 *
	 * @var Role_Request
	 */
	public $request;

	/**
	 * Roles constructor.
	 */
	public function __construct() {
		$this->request = new Role_Request();
	}

	/**
	 * Check if the field contains a valid value.
	 *
	 * @return bool
	 */
	public function is_valid() {
		if ( ! have_rows( 'roles' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Print the HTML template.
	 */
	public function display() {
		global $post;

		$this->request->maybe_request();
		$this->enqueue_scripts();

		if ( have_rows( 'roles' ) ) {
			echo '<ul class="roles">';
			$can_join = is_user_logged_in() && is_single();
			$role_index = $this->get_role_index( $post->ID );
			$request_index = $this->request->get_request_index( $post->ID );

			// Check if current user has a current request.
			if ( $can_join && false !== $request_index ) {
				$can_join = false;
			}

			// Check if current user is already a member.
			if ( $can_join && false !== $role_index ) {
				$can_join = false;
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
							<?php echo wp_kses_post( get_avatar( $user['ID'], 512 ) ); ?>
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<span class="name"><?php echo esc_html( $user['display_name'] ); ?></span>
						</div>
					<?php elseif ( ! $can_join ) : ?>
						<div class="waiting">
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<span class="name"><?php echo esc_html( $user['display_name'] ); ?></span>
						</div>
					<?php else : ?>
						<div class="open">
							<?php echo wp_kses_post( get_avatar( get_current_user_id(), 512 ) ); ?>
							<span class="avatar empty <?php echo esc_attr( $request_index  === get_row_index() ? 'requested' : '' ); ?>"></span>
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<input type="button" class="join" value="<?php esc_attr_e( 'Join the Team', 'flint' ) ?>" />
							<form method="post" id="row-<?php echo esc_attr( get_row_index() ); ?>" class="row">
								<input type="hidden" name="request-role" value="<?php echo esc_attr( get_row_index() ); ?>" />
							</form>
							<?php if ( $request_index === get_row_index() ) : ?>
								<span class="requested"><?php esc_html_e( 'Requested' ); ?></span>
							<?php endif; ?>
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
	 * Enqueue the javascript required for Roles
	 */
	public function enqueue_scripts() {
		$plugin = get_plugin_instance();
		wp_enqueue_script( 'roles', trailingslashit( $plugin->dir_url ) . 'js/roles.js', array( 'jquery' ), false, true );
	}

	/**
	 * Return's the current user's Role index in the Team, or false if none
	 *
	 * @param int $project_id
	 * @return int|bool
	 */
	public function get_role_index( $project_id ) {
		$current_user = wp_get_current_user();
		$index = false;

		if ( have_rows( 'roles', $project_id ) ) {
			while( have_rows( 'roles', $project_id ) ) {
				the_row();
				$user = get_sub_field( 'user' );
				if ( $user && $current_user->ID === $user['ID'] ) {
					$index = get_row_index();
					break;
				}
			}
			reset_rows();
		}

		return $index;
	}
}
