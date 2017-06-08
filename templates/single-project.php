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
					<?php load_template( trailingslashit( $flint_plugin->dir_path ) . 'templates/project-details.php', false ); ?>
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
							load_template( trailingslashit( $flint_plugin->dir_path ) . 'templates/project-update.php', false );
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
