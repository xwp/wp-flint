<?php
/**
 * The template for displaying Project cards
 *
 * @package Flint
 */

global $flint_plugin;

if ( $flint_plugin->projects->field_is_valid( 'feature_color' ) ) {
	$color = get_field( 'feature_color' );
} else {
	$color = 'inherit';
};
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<style type="text/css">
		#post-<?php the_ID(); ?> {
			background-color: <?php echo esc_attr( $color ); ?>;
		}
		#post-<?php the_ID(); ?> .learn-more-link {
			color: <?php echo esc_attr( $color ); ?>;
			border-color: <?php echo esc_attr( $color ); ?>;
		}
		#post-<?php the_ID(); ?> .learn-more-link {
			color: <?php echo esc_attr( $color ); ?>;
			border-color: <?php echo esc_attr( $color ); ?>;
		}
		#post-<?php the_ID(); ?> .learn-more-link:hover {
			background-color: <?php echo esc_attr( $color ); ?>;
			color: #fff;
		}
		#post-<?php the_ID(); ?> .avatar.empty {
			background-color: <?php echo esc_attr( $color ); ?>;
		}
	</style>
	<?php the_title( '<h1 class="entry-title"><a href="' . get_permalink() . '">', '</a></h1>' ); ?>
	<?php $flint_plugin->projects->likes->display(); ?>
	<div class="description">
		<?php
		if ( $flint_plugin->projects->field_is_valid( 'summary' ) ) {
			$flint_plugin->projects->display_field( 'summary' );
		};
		?>
		<p><a class="learn-more-link" href="<?php the_permalink(); ?>">Learn More</a></p>
	</div>
	<div class="actions">
		<?php
		if ( $flint_plugin->projects->field_is_valid( 'roles' ) ) {
			$flint_plugin->projects->display_field( 'roles' );
		};
		?>
	</div>
</article>
