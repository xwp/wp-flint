<?php
/**
 * The template for displaying Project posts
 *
 * @package Flint
 */

global $flint_plugin, $post;

get_header(); ?>

<div id="primary" class="content-area container">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			if ( $flint_plugin->projects->field_is_valid( 'feature_color' ) ) {
				$color = get_field( 'feature_color' );
			} else {
				$color = 'inherit';
			};
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<style type="text/css">
					#post-<?php the_ID(); ?> .entry-title {
						color: <?php echo esc_attr( $color ); ?>;
					}
					#post-<?php the_ID(); ?> .roles li {
						background-color: <?php echo esc_attr( $color ); ?>;
					}
				</style>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<?php
					if ( $flint_plugin->projects->field_is_valid( 'summary' ) ) {
						$flint_plugin->projects->display_field( 'summary' );
					};
					if ( $flint_plugin->projects->field_is_valid( 'roles' ) ) {
						$flint_plugin->projects->display_field( 'roles' );
					};
					?>
				</header>

				<?php
				$updates_args = array(
					'tax_query' => array(
						array(
							'taxonomy' => $flint_plugin->projects->updates->tax_key,
							'field'    => 'slug',
							'terms'    => $post->post_name,
						),
					),
				);
				$updates_query = new \WP_Query( $updates_args );
				?>
				<nav class="tabs">
					<ul>
						<li class="current"><a href="#details"><?php esc_html_e( 'Project Description', 'flint' ); ?></a></li>
						<?php if ( $updates_query->have_posts() ) : ?>
						<li><a href="#updates"><?php esc_html_e( 'Updates', 'flint' ); ?></a></li>
						<?php endif; ?>
						<li><a href="#discussion"><?php esc_html_e( 'Discussion', 'flint' ); ?></a></li>
					</ul>
				</nav>

				<section id="details" class="tab-content current">
					<?php load_template( trailingslashit( $flint_plugin->dir_path ) . 'templates/project-details.php', false ); ?>
				</section>

				<?php
				if ( $updates_query->have_posts() ) :
					echo '<section id="updates" class="tab-content">';
					while ( $updates_query->have_posts() ) :
						$updates_query->the_post();
						load_template( trailingslashit( $flint_plugin->dir_path ) . 'templates/project-update.php', false );
					endwhile;
					wp_reset_query();
					echo '</section>';
				endif;
				?>

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
