<?php
/**
 * Sets up the Stage custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Stage implements Field_Group {

	/**
	 * Field key. Required to display without administrator permission.
	 *
	 * @var String
	 */
	public $key = 'field_5914e73db5ed7';

	/**
	 * Stage names.
	 *
	 * @var String[]
	 */
	public $stages;

	/**
	 * Stage constructor.
	 */
	public function __construct() {
		$this->stages = array(
			'open' => __( 'Open', 'flint' ),
			'ready' => __( 'Pre-Approved', 'flint' ),
			'active' => __( 'Active', 'flint' ),
			'done' => __( 'Launched', 'flint' ),
		);
	}

	/**
	 * Print the HTML template.
	 */
	public function display() {
		$stage = get_field( $this->key );
		echo wp_kses_post( $this->stages[ $stage ] );
	}

	/**
	 * Adds a metabox to the Edit Project screen.
	 *
	 * @action add_meta_boxes
	 */
	public function add_meta_box() {
		$plugin = get_plugin_instance();
		add_meta_box(
			'stage',
			__( 'Stage', 'flint' ),
			array( $this, 'display_meta_box' ),
			$plugin->projects->key,
			'side',
			'default'
		);
	}

	/**
	 * Displays a metabox containing the current Stage.
	 */
	public function display_meta_box() {
		$stage = get_field( $this->key );
		echo wp_kses_post( $this->stages[ $stage ] );
	}
}
