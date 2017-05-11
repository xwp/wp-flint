<?php
/**
 * The template for displaying Project posts
 *
 * @package Flint
 */

global $flint_plugin;

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

				<div class="entry-content container">
					<h2><?php _e( 'Overview', 'flint' ); ?></h2>
					<?php the_content(); ?>
				</div>

				<div class="entry-content role-descriptions container">
					<h2><?php _e( 'Team Roles', 'flint' ); ?></h2>
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

				<div class="entry-content business-model">
					<?php $flint_plugin->projects->display_field( 'business_model' ); ?>
				</div>

				<div class="entry-content timeline container">
					<?php $flint_plugin->projects->display_field( 'timeline' ); ?>
				</div>

				<footer class="entry-footer">
					<?php
					edit_post_link(
						sprintf(
							__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'flint' ),
							get_the_title()
						),
						'<span class="edit-link">',
						'</span>'
					);
					?>
				</footer>
			</article>
			<?php

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
