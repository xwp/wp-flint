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
			'active' => __( 'Active', 'flint' ),
		);

		$plugin = get_plugin_instance();
		add_filter( 'views_edit-' . $plugin->projects->key, array( $this, 'add_post_view' ) );
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
		if ( current_user_can( 'edit_others_posts' ) ) {
			return;
		}

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

	/**
	 * Add Stage filters to Edit Projects screen.
	 *
	 * @filter views_edit-project
	 *
	 * @param array $views
	 * @return array
	 */
	public function add_post_view( $views ) {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return $views;
		}

		foreach ( $this->stages as $stage => $label ) {
			$views[] = $this->get_post_view( $stage );
		}

		return $views;
	}

	/**
	 * Get the HTML for a Stage filter on the Edit Projects screen
	 *
	 * @param string $stage
	 * @return string
	 */
	public function get_post_view( $stage ) {
		$plugin = get_plugin_instance();
		$input  = sanitize_key( filter_input( INPUT_GET, 'stage' ) );

		wp_reset_query();
		/**
		 * @see https://core.trac.wordpress.org/ticket/29178
		 */
		$args = array(
			'posts_per_page' => -1,
			'post_type'      => $plugin->projects->key,
			'meta_key'       => 'stage',
			'meta_value'     => $stage,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		);

		$projects       = new \WP_Query( $args );
		$projects_count = count( $projects->posts );
		$projects_class = '';
		$projects_url   = add_query_arg(
			array( 'post_type' => $plugin->projects->key, 'stage' => $stage ),
			admin_url( 'edit.php' )
		);

		if ( $stage === $input ) {
			$projects_class = 'class="current"';
		}

		return sprintf(
			'<a %s href="%s">%s</a> (%d)',
			$projects_class,
			$projects_url,
			$this->stages[ $stage ],
			$projects_count
		);
	}

	/**
	 * Filter Projects on Edit Projects screen if a Stage view is active
	 *
	 * @filter pre_get_posts
	 *
	 * @param \WP_Query $query
	 * @return \WP_Query
	 */
	public function filter_post_view( $query ) {
		global $pagenow;

		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return $query;
		}

		if ( 'stage' === $query->get( 'meta_key' ) ) {
			return $query;
		}

		if( 'edit.php' !== $pagenow || ! $query->is_admin ) {
			return $query;
		}

		$stage = sanitize_key( filter_input( INPUT_GET, 'stage' ) );
		if ( '' !== $stage ) {
			$query->set( 'meta_key', 'stage' );
			$query->set( 'meta_value', $stage );
		}

		return $query;
	}
}
