<?php
/**
 * Sets up the Roles custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Roles implements Field_Group {

	/**
	 * Meta key.
	 *
	 * @var string
	 */
	public $meta_key = 'project_applications';

	/**
	 * Print the HTML template.
	 */
	public function display() {
		$this->save();
		$this->enqueue_scripts();

		if ( have_rows( 'roles' ) ) {
			echo '<ul class="roles">';
			$can_join = is_user_logged_in() && is_single();
			$role_index = $this->get_role_index();
			$application_index = $this->get_application_index();

			// Check if current user has a current application.
			if ( $can_join && false !== $application_index ) {
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
					$role = $this->get_role_title( get_row_index() );
					?>
					<?php if ( $user ) : ?>
						<div class="closed">
							<?php echo wp_kses_post( $user['user_avatar'] ); ?>
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<span class="name"><?php echo esc_html( $user['display_name'] ); ?></span>
						</div>
					<?php else : ?>
						<div class="open">
							<?php echo wp_kses_post( get_avatar( get_current_user_id() ) ); ?>
							<span class="avatar empty <?php echo esc_attr( $application_index  === get_row_index() ? 'applied' : '' ); ?>"></span>
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<?php if ( $can_join ) : ?>
								<input type="button" class="join" value="<?php esc_attr_e( 'Join', 'flint' ) ?>" />
								<form method="post" id="row-<?php echo esc_attr( get_row_index() ); ?>" class="row">
									<input type="hidden" name="request-role" value="<?php echo esc_attr( get_row_index() ); ?>" />
								</form>
							<?php endif; ?>
							<?php if ( $application_index === get_row_index() ) : ?>
								<span class="applied"><?php esc_html_e( 'Requested' ); ?></span>
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
	 * Save submitted roles form
	 */
	public function save() {
		if ( isset( $_POST['request-role'] ) && is_user_logged_in() ) {
			$row  = filter_var( $_POST['request-role'], FILTER_VALIDATE_INT );
			$role = $this->get_role_title( $row );

			$this->email_new_application( $role );
			$this->update_application_user_meta( $row );
		}
	}

	/**
	 * Send an email to the Project owner, informing them of a Role application
	 *
	 * @param string $role
	 */
	public function email_new_application( $role ) {
		$user = wp_get_current_user();

		$message = sprintf( __( 'Hi %s,', 'flint' ), get_the_author() );
		$message .= "\n\n";
		$message .= sprintf(
			__( '%s has requested to join %s in the role of %s.', 'flint ' ),
			$user->display_name,
			sprintf( '<a href="%s">%s</a>', get_permalink(), get_the_title() ),
			$role
		);
		$message .= "\n\n";
		$message .= __( 'To manage the team roles for this project, click here:', 'flint' );
		$message .= "\n";
		$message .= sprintf( '<a href="%1$s">%1$s</a>', get_edit_post_link() );

		wp_mail(
			get_the_author_meta( 'user_email' ),
			__( 'New team member application on SP⚡️RK.', 'flint' ),
			$message
		);
	}

	/**
	 * Update user meta to include Role application
	 *
	 * @param int $index
	 */
	public function update_application_user_meta( $index ) {
		global $post;
		$user = wp_get_current_user();
		$applications = get_user_meta( $user->ID, $this->meta_key, true );
		$applications[ $post->ID ] = $index;
		update_user_meta( $user->ID, $this->meta_key, $applications );
	}

	/**
	 * Remove Role application from user meta
	 */
	public function remove_application_user_meta() {
		global $post;
		$user = wp_get_current_user();
		$applications = get_user_meta( $user->ID, $this->meta_key, true );
		unset( $applications[ $post->ID ] );
		update_user_meta( $user->ID, $this->meta_key, $applications );
	}

	/**
	 * Checks whether the current user has a current Role application
	 *
	 * @return int|bool
	 */
	public function get_application_index() {
		global $post;
		$user = wp_get_current_user();

		$applications = get_user_meta( $user->ID, $this->meta_key, true );

		if ( is_array( $applications ) && array_key_exists( $post->ID, $applications ) ) {
			$index  = $applications[ $post->ID ];
			$filled = $role = get_field( 'roles' )[ $index ];
			if ( $filled['user'] ) {
				$this->remove_application_user_meta();
				return false;
			} else {
				return $index;
			}
		}

		return false;
	}

	/**
	 * Checks whether the current user has a Role in the Team
	 *
	 * @return int|bool
	 */
	public function get_role_index() {
		$current_user = wp_get_current_user();
		$index = false;

		if ( have_rows( 'roles' ) ) {
			while( have_rows( 'roles' ) ) {
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

	/**
	 * Enqueue the javascript required for Roles
	 */
	public function enqueue_scripts() {
		$plugin = get_plugin_instance();
		wp_enqueue_script( 'roles', trailingslashit( $plugin->dir_url ) . 'js/roles.js', array( 'jquery' ), false, true );
	}

	/**
	 * Get role title
	 *
	 * @param int $index
	 * @return string
	 */
	public function get_role_title( $index ) {
		$role = get_field( 'roles' )[ $index ];
		$title = $role['role'];
		if ( 'Other' === $title ) {
			$title = $role['role_title'];
		}
		return $title;
	}
}
