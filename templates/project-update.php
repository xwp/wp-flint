<?php
/**
 * The template for displaying Project updates
 *
 * @package Flint
 */
?>
<div class="entry-content container">
	<?php the_title( '<h2>', '</h2>' ); ?>
	<div class="entry-meta">
	<?php
	printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
		get_avatar( get_the_author_meta( 'user_email' ), 32 ),
		_x( 'Author', 'Used before post author name.', 'flint' ),
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
		_x( 'Posted on', 'Used before publish date.', 'flint' ),
		esc_url( get_permalink() ),
		$time_string
	);
	?>
	</div>

	<?php the_content(); ?>

	<?php
	edit_post_link(
		sprintf(
		/* translators: %s: Name of current post */
			__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'flint' ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);
	?>
</div><!-- .entry-content -->
