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
			'open' => array(
				'label'       => __( 'Open', 'flint' ),
				'description' => __( 'Open Projects are still forming a team and defining a business model.', 'flint' ),
			),
			'active' => array(
				'label'       => __( 'Active', 'flint' ),
				'description' => __( 'Active Projects have been released, or are in development.', 'flint' ),
			),
		);

		$plugin = get_plugin_instance();
		add_filter( 'views_edit-' . $plugin->projects->key, array( $this, 'add_post_view' ) );
	}

	/**
	 * Default new Projects to the 'open' Stage
	 *
	 * @action save_post
	 *
	 * @param int $post_id
	 */
	public function save_post( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) {
			// No need to force a save, since they already have permission to edit this field.
			return;
		}

		$default = array_keys( $this->stages )[0];
		$field   = acf_maybe_get_field( $this->key );
		$value   = '';

		if ( $field ) {
			$value = $field->value;
		}

		if ( ! array_key_exists( $value, $this->stages ) ) {
			update_field( $this->key, $default, $post_id );
		}
	}

	/**
	 * Print the HTML template.
	 */
	public function display() {
		$stage = get_field( $this->key );
		echo wp_kses_post( $this->stages[ $stage ]['label'] );
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
		$value = get_field( $this->key );
		$stage = $this->stages[ $value ];
		echo wp_kses_post( sprintf(
			'<h4>%s %s</h4><p class="description">%s</p>',
			__( 'Current Stage: ', 'flint' ),
			$stage['label'],
			$stage['description']
		) );
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
		foreach ( $this->stages as $stage => $args ) {
			$views[] = wp_kses_post( $this->get_post_view( $stage ) );
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

		$class = '';
		$count = $this->get_post_stage_count( $stage );
		$url   = add_query_arg(
			array(
				'post_type' => $plugin->projects->key,
				'all_posts' => 1,
				'stage' => $stage
			),
			admin_url( 'edit.php' )
		);

		if ( $stage === $input ) {
			$class = 'class="current"';
		}

		return sprintf(
			'<a %s href="%s">%s</a> (%d)',
			$class,
			$url,
			$this->stages[ $stage ]['label'],
			$count
		);
	}

	/**
	 * Get the total amount of Projects in the Stage
	 *
	 * @param string $stage
	 * @return int
	 */
	public function get_post_stage_count( $stage ) {
		$plugin = get_plugin_instance();
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
			'post_status'    => 'any',
		);

		$projects = new \WP_Query( $args );
		return count( $projects->posts );
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
