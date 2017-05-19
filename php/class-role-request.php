<?php
/**
 * Handles Role requests.
 *
 * @package Flint
 */

namespace Flint;

class Role_Request {

	/**
	 * Meta key.
	 *
	 * @var string
	 */
	public $meta_key = 'project_requests';

	/**
	 * Handle submitted role requests
	 */
	public function maybe_request() {
		if ( isset( $_POST['request-role'] ) && is_user_logged_in() ) {
			$row  = filter_var( $_POST['request-role'], FILTER_VALIDATE_INT );
			$role = get_field( 'roles' )[ $row ];

			$title = $role['role'];
			if ( 'Other' === $title ) {
				$title = $role['role_title'];
			}

			$this->email_new_request( $title );
			$this->update_request_user_meta( $row );
		}
	}

	/**
	 * Send an email to the Project owner, informing them of a Role request
	 *
	 * @param string $role
	 */
	public function email_new_request( $role ) {
		global $post;
		$user = wp_get_current_user();

		$message = sprintf(
			'<p>%s</p><p>%s</p><p>%s<br />%s</p><p>%s</p>',
			sprintf( __( 'Hi %s,', 'flint' ), get_the_author() ),
			sprintf(
				__( '%s has requested to join %s in the role of %s.', 'flint ' ),
				$user->display_name,
				sprintf( '<a href="%s">%s</a>', get_permalink(), get_the_title() ),
				$role
			),
			__( 'To manage the team roles for this project, click here:', 'flint' ),
			sprintf( '<a href="%1$s">%1$s</a>', get_edit_post_link( $post->ID, '' ) ),
			'SP⚡️RK'
		);

		add_filter( 'wp_mail_content_type', array( $this, 'set_html_email_type' ) );

		wp_mail(
			get_the_author_meta( 'user_email' ),
			__( 'New team member request on SP⚡️RK.', 'flint' ),
			$message
		);

		remove_filter( 'wp_mail_content_type', array( $this, 'set_html_email_type' ) );
	}

	/**
	 * Filter the mail content type.
	 *
	 * @return string
	 */
	public function set_html_email_type() {
		return 'text/html';
	}

	/**
	 * Update user meta to include Role request
	 *
	 * @param int $index
	 */
	public function update_request_user_meta( $index ) {
		global $post;
		$user = wp_get_current_user();
		$requests = get_user_meta( $user->ID, $this->meta_key, true );
		$requests[ $post->ID ] = $index;
		update_user_meta( $user->ID, $this->meta_key, $requests );
	}

	/**
	 * Remove Role request from user meta
	 */
	public function remove_request_user_meta() {
		global $post;
		$user = wp_get_current_user();
		$requests = get_user_meta( $user->ID, $this->meta_key, true );
		unset( $requests[ $post->ID ] );
		update_user_meta( $user->ID, $this->meta_key, $requests );
	}

	/**
	 * Returns the current user's requested Role index, or false if none
	 *
	 * @return int|bool
	 */
	public function get_request_index() {
		global $post;
		$user = wp_get_current_user();

		$requests = get_user_meta( $user->ID, $this->meta_key, true );

		if ( is_array( $requests ) && array_key_exists( $post->ID, $requests ) ) {
			$index  = $requests[ $post->ID ];
			$filled = $role = get_field( 'roles' )[ $index ];
			if ( $filled['user'] ) {
				$this->remove_request_user_meta();
				return false;
			} else {
				return $index;
			}
		}

		return false;
	}
}
