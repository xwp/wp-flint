<?php
/**
 * Interface for custom field group classes.
 *
 * @package Flint
 */

namespace Flint;

class Likes {

	/**
	 * Meta key.
	 *
	 * @var string
	 */
	public $meta_key = 'likes';

	/**
	 * Get the post ID for the current post.
	 *
	 * @param int $post_id Post ID.
	 * @return int
	 */
	public function get_post_id( $post_id ) {
		if ( ! $post_id ) {
			global $post;
			if ( $post->ID ) {
				return $post->ID;
			}
		}
		return $post_id;
	}

	/**
	 * Get total likes for a post.
	 *
	 * @param int $post_id Post ID.
	 * @return int
	 */
	public function total_post_likes( $post_id = 0 ) {
		$post_id = $this->get_post_id( $post_id );
		$likes   = get_post_meta( $post_id, $this->meta_key, true );

		if ( ! $likes ) {
			$likes = array();
		}

		return count( $likes );
	}

	/**
	 * Get the total posts a user likes.
	 *
	 * @param int $user_id User ID.
	 * @return int
	 */
	public function total_user_likes( $user_id ) {
		$likes = get_user_meta( $user_id, $this->meta_key, true );

		if ( ! $likes ) {
			$likes = array();
		}

		return count( $likes );
	}

	/**
	 * Get whether a user likes a post.
	 *
	 * @param int $post_id Post ID.
	 * @param int $user_id User ID.
	 * @return bool
	 */
	public function is_liked( $post_id = 0, $user_id ) {
		$post_id = $this->get_post_id( $post_id );
		$likes   = get_post_meta( $post_id, $this->meta_key, true );

		if ( ! $likes || empty( $likes ) ) {
			$likes = array();
		}

		return in_array( $user_id, $likes );
	}

	/**
	 * Add a user's like to a post.
	 *
	 * @param int $post_id Post ID.
	 * @param int $user_id User ID.
	 */
	public function add( $post_id = 0, $user_id ) {
		$post_id    = $this->get_post_id( $post_id );
		$post_likes = get_post_meta( $post_id, $this->meta_key, true );
		$user_likes = get_user_meta( $user_id, $this->meta_key, true );

		if ( ! $this->is_liked( $post_id, $user_id ) ) {
			$post_likes[] = $user_id;
			$user_likes[] = $post_id;
			update_post_meta( $post_id, $this->meta_key, $post_likes );
			update_user_meta( $user_id, $this->meta_key, $user_likes );
		}
	}

	/**
	 * Remove a user's like from a post.
	 *
	 * @param int $post_id Post ID.
	 * @param int $user_id User ID.
	 */
	public function remove( $post_id = 0, $user_id ) {
		$post_id    = $this->get_post_id( $post_id );
		$post_likes = get_post_meta( $post_id, $this->meta_key, true );
		$user_likes = get_user_meta( $user_id, $this->meta_key, true );

		if ( ! $post_likes ) {
			$post_likes = array();
		}

		if ( ! $user_likes ) {
			$user_likes = array();
		}

		if ( $this->is_liked( $post_id, $user_id ) ) {
			$key = array_search( $user_id, $post_likes );
			unset( $post_likes[ $key ] );

			$key = array_search( $post_id, $user_likes );
			unset( $user_likes[ $key ] );

			$post_likes = array_values( $post_likes );
			$user_likes = array_values( $user_likes );

			update_post_meta( $post_id, $this->meta_key, $post_likes );
			update_user_meta( $user_id, $this->meta_key, $user_likes );
		}
	}

	/**
	 * Print the HTML template.
	 *
	 * @param int $post_id Post ID.
	 */
	public function display( $post_id = 0 ) {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$post_id = $this->get_post_id( $post_id );
		$this->save();

		?>
		<div class="likes">
			<span class="total"><?php echo esc_html( $this->total_post_likes( $post_id ) > 0 ? $this->total_post_likes( $post_id ) : '' ); ?></span>

			<?php

			if ( $this->is_liked( $post_id, get_current_user_id() ) ) {
				?>
				<a href="javascript:document.getElementById('unlike-<?php echo esc_attr( $post_id ); ?>').submit();" class="unlike"><?php _e( 'Unlike', 'flint' ); ?></a>
				<form method="get" id="unlike-<?php echo esc_attr( $post_id ); ?>">
					<input type="hidden" name="unlike" value="<?php echo esc_attr( $post_id ); ?>"/>
				</form>
				<?php
			} else {
				?>
				<a href="javascript:document.getElementById('like-<?php echo esc_attr( $post_id ); ?>').submit();" class="like"><?php _e( 'Like', 'flint' ); ?></a>
				<form method="get" id="like-<?php echo esc_attr( $post_id ); ?>">
					<input type="hidden" name="like" value="<?php echo esc_attr( $post_id ); ?>"/>
				</form>
				<?php
			}
			?>
		</div>
		<?php
	}

	/**
	 * Save submitted likes form.
	 */
	public function save() {
		if ( isset( $_REQUEST['like'] ) && is_user_logged_in() ) {
			$post_id = filter_var( $_REQUEST['like'], FILTER_VALIDATE_INT );
			$this->add( $post_id, get_current_user_id() );
		}
		if ( isset( $_REQUEST['unlike'] ) && is_user_logged_in() ) {
			$post_id = filter_var( $_REQUEST['unlike'], FILTER_VALIDATE_INT );
			$this->remove( $post_id, get_current_user_id() );
		}
	}
}
