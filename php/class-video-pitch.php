<?php
/**
 * Sets up the Business Model custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Video_Pitch implements Field_Group {
	/**
	 * Print the HTML template.
	 */
	public function display() {
		$youtube_url = get_field( 'youtube_url' );
		$allowed_html = array(
			'iframe' => array(
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			),
		);
		echo wp_kses( apply_filters( 'the_content', $youtube_url ), $allowed_html );
	}
}
