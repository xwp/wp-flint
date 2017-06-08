<?php
/**
 * Sets up the Video Pitch custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Video_Pitch implements Field_Group {
	/**
	 * Check if the field contains a valid value.
	 *
	 * @return bool
	 */
	public function is_valid() {
		$value = get_field( 'youtube_url' );
		if ( empty( $value ) || false === filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return false;
		}
		return true;
	}

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
