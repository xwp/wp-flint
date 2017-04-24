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
	public $meta_key;

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
	public function total( $post_id = 0 ) {
		$post_id = $this->get_post_id( $post_id );
		$likes   = get_post_meta( $post_id, $this->meta_key, true );

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

		if ( ! $likes ) {
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
		$post_id = $this->get_post_id( $post_id );
		$likes   = get_post_meta( $post_id, $this->meta_key, true );

		if ( ! $this->is_liked( $post_id, $user_id ) ) {
			$likes[] = $user_id;
			update_post_meta( $post_id, $this->meta_key, $likes );
		}
	}

	/**
	 * Remove a user's like from a post.
	 *
	 * @param int $post_id Post ID.
	 * @param int $user_id User ID.
	 */
	public function remove( $post_id = 0, $user_id ) {
		$post_id = $this->get_post_id( $post_id );
		$likes   = get_post_meta( $post_id, $this->meta_key, true );

		if ( $this->is_liked( $post_id, $user_id ) ) {
			$key = array_search( $user_id, $likes );
			unset( $likes[ $key ] );
			$likes = array_values( $likes );
			update_post_meta( $post_id, $this->meta_key, $likes );
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

		if ( $this->is_liked( $post_id, get_current_user_id() ) ) {
			?>
			<a href="javascript:document.getElementById('unlike-<?php echo esc_attr( $post_id ); ?>').submit();" class="likes unlike"><?php _e( 'Unlike', 'flint' ); ?></a>
			<form method="post" id="unlike-<?php echo esc_attr( $post_id ); ?>">
				<input type="hidden" name="unlike-post" value="<?php echo esc_attr( $post_id ); ?>"/>
			</form>
			<?php
		} else {
			?>
			<a href="javascript:document.getElementById('like-<?php echo esc_attr( $post_id ); ?>').submit();" class="likes like"><?php _e( 'Like', 'flint' ); ?></a>
			<form method="post" id="like-<?php echo esc_attr( $post_id ); ?>">
				<input type="hidden" name="like-post" value="<?php echo esc_attr( $post_id ); ?>"/>
			</form>
			<?php
		}
	}

	/**
	 * Save submitted likes form.
	 */
	public function save() {
		if ( isset( $_POST['like-post'] ) && is_user_logged_in() ) {
			$post_id = filter_var( $_POST['like-post'], FILTER_VALIDATE_INT );
			$this->add( $post_id, get_current_user_id() );
		}
		if ( isset( $_POST['unlike-post'] ) && is_user_logged_in() ) {
			$post_id = filter_var( $_POST['unlike-post'], FILTER_VALIDATE_INT );
			$this->remove( $post_id, get_current_user_id() );
		}
	}
}
