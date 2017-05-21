<?php
/**
 * The template for displaying Project posts
 *
 * @package Flint
 */

global $flint_plugin, $post;

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<?php the_field( 'tweet_pitch' ); ?>
					<?php $flint_plugin->projects->display_field( 'roles' ); ?>
				</header>

				<nav class="tabs">
					<ul>
						<li class="current"><a href="#details"><?php esc_html_e( 'Project Description', 'flint' ); ?></a></li>
						<li><a href="#updates"><?php esc_html_e( 'Updates', 'flint' ); ?></a></li>
						<li><a href="#discussion"><?php esc_html_e( 'Discussion', 'flint' ); ?></a></li>
					</ul>
				</nav>

				<section id="details" class="tab-content current">
					<div class="entry-content container">
						<?php $flint_plugin->projects->display_field( 'video_pitch' ); ?>
					</div>

					<div class="entry-content container">
						<h2><?php esc_html_e( 'Overview', 'flint' ); ?></h2>
						<?php the_content(); ?>
					</div>

					<div class="entry-content role-descriptions container">
						<h2><?php esc_html_e( 'Team Roles', 'flint' ); ?></h2>
						<?php
						while( have_rows( 'roles' ) ) {
							the_row();
							$role = get_sub_field( 'role' );
							if ( 'Other' === $role ) {
								$role = get_sub_field( 'role_title' );
							}
							echo '<h3>' . esc_html( $role ) . '</h3>';
							echo wp_kses_post( get_sub_field( 'role_description' ) );
						}
						reset_rows();
						?>
					</div>

					<div class="entry-content business-model container">
						<?php $flint_plugin->projects->display_field( 'business_model' ); ?>
					</div>

					<div class="entry-content timeline container">
						<?php $flint_plugin->projects->display_field( 'timeline' ); ?>
					</div>
				</section>

				<section id="updates" class="tab-content">
					<?php
					$args = array(
						'tax_query' => array(
							array(
								'taxonomy' => $flint_plugin->projects->updates->tax_key,
								'field'    => 'slug',
								'terms'    => $post->post_name,
							),
						),
					);
					$query = new \WP_Query( $args );
					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) :
							$query->the_post();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<div class="entry-content container">
									<?php the_title( '<h2>', '</h2>' ); ?>
									<?php
									printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
										get_avatar( get_the_author_meta( 'user_email' ), 32 ),
										_x( 'Author', 'Used before post author name.', 'twentysixteen' ),
										esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
										get_the_author()
									);

									$time_string = sprintf(
										'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
										esc_attr( get_the_date( 'c' ) ),
										get_the_date(),
										esc_attr( get_the_modified_date( 'c' ) ),
										get_the_modified_date()
									);

									printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
										_x( 'Posted on', 'Used before publish date.', 'twentysixteen' ),
										esc_url( get_permalink() ),
										$time_string
									);

									the_content();

									edit_post_link(
										sprintf(
										/* translators: %s: Name of current post */
											__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
											get_the_title()
										),
										'<span class="edit-link">',
										'</span>'
									);
									?>
								</div><!-- .entry-content -->
							</article><!-- #post-## -->
							<?php
						endwhile;
					endif;
					?>
				</section>

				<?php if ( comments_open() || get_comments_number() ) : ?>
				<section id="discussion" class="tab-content">
					<?php comments_template(); ?>
				</section>
				<?php endif; ?>

			</article>
			<?php
			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
