<?php
/**
 * Adds shortcode for displaying Flint Projects.
 *
 * @package Flint
 */

namespace Flint;

class Shortcode {

	/**
	 * Shortcode constructor.
	 */
	public function __construct() {
		add_shortcode( 'projects', array( $this, 'display' ) );
	}

	/**
	 * Print HTML template
	 *
	 * @param array $atts
	 * @return string
	 */
	public function display( $atts ) {
		global $flint_plugin;

		$atts = shortcode_atts(
			array(
				'open' => '',
				'likes' => '',
			),
			$atts
		);

		$args = array( 'post_type'  => array( 'project' ) );

		if ( 'true' === $atts['open'] || true === $atts['open'] ) {
			$args['meta_key']   = 'is_open';
			$args['meta_value'] = '1';
		}

		if ( 'false' === $atts['open'] || false === $atts['open'] ) {
			$args['meta_key']   = 'is_open';
			$args['meta_value'] = '0';
		}

		if ( is_user_logged_in() ) {
			$meta_key = get_plugin_instance()->projects->likes->meta_key;
			$likes = get_user_meta( get_current_user_id(), $meta_key, true );

			if ( 'true' === $atts['likes'] || true === $atts['likes'] ) {
				$args['post__in'] = $likes;
			}
			if ( 'false' === $atts['likes'] || false === $atts['likes'] ) {
				$args['post__not_in'] = $likes;
			}
		}

		$query = new \WP_Query( $args );

		ob_start();

		if ( $query->have_posts() ) {
			?>
			<section class="projects-archive">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<a href="<?php the_permalink(); ?>"><?php the_title( '<h1 class="entry-title" style="color: ' . get_field( 'feature_color' ) . '">', '</h1>' ); ?></a>
					<?php $flint_plugin->projects->likes->display(); ?>
					<div class="description">
						<?php the_field( 'tweet_pitch' ); ?>
					</div>
					<?php $flint_plugin->projects->display_field( 'roles' ); ?>
					<a class="learn-more-link" href="<?php the_permalink(); ?>">Learn More</a>
				</article>
				<?php
			}
			wp_reset_postdata();
			?>
			</section>
			<?php
		}

		return ob_get_clean();
	}
}
