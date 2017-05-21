<?php
/**
 * Sets up the Flint Project post type.
 *
 * @package Flint
 */

namespace Flint;

class Projects {

	/**
	 * Custom field groups.
	 *
	 * @var Field_Group[]
	 */
	public $custom_field_groups;

	/**
	 * Likes.
	 *
	 * @var Likes
	 */
	public $likes;

	/**
	 * Updates.
	 *
	 * @var Updates
	 */
	public $updates;

	/**
	 * Post type key.
	 *
	 * @var String
	 */
	public $key = 'project';

	/**
	 * Register post type.
	 *
	 * @action init
	 */
	public function register_post_type() {
		$labels = array(
			'name'          => __( 'Projects', 'flint' ),
			'singular_name' => __( 'Project', 'flint' ),
		);

		$args = array(
			'label'               => __( 'Projects', 'flint' ),
			'labels'              => $labels,
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'rest_base'           => '',
			'has_archive'         => false,
			'show_in_menu'        => true,
			'exclude_from_search' => false,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'rewrite'             => array( 'slug' => $this->key, 'with_front' => true ),
			'query_var'           => true,
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-pressthis',
			'supports'            => array( 'title', 'editor', 'comments', 'author' ),
		);

		register_post_type( $this->key, $args );
	}

	/**
	 * Register custom fields.
	 *
	 * @action init
	 */
	public function register_custom_fields() {
		$this->custom_field_groups = apply_filters( 'flint_custom_field_groups', array(
			'business_model' => new Business_Model(),
			'summary'        => new Summary(),
			'stage'          => new Stage(),
			'roles'          => new Roles(),
			'timeline'       => new Timeline(),
			'feature_color'  => new Feature_Color(),
			'video_pitch'    => new Video_Pitch(),
		) );

		$plugin = get_plugin_instance();
		foreach ( $this->custom_field_groups as $custom_field_group ) {
			$plugin->add_doc_hooks( $custom_field_group );
		}
	}

	/**
	 * Load custom field json.
	 *
	 * @filter acf/settings/load_json
	 *
	 * @param array $paths
	 * @return array $paths
	 */
	public function load_custom_field_json( $paths ) {
		$plugin = get_plugin_instance();
		$paths[] = trailingslashit( $plugin->dir_path ) . 'acf-json';
		return $paths;
	}

	/**
	 * Custom Field Group Rows should start at 0.
	 *
	 * @see https://www.advancedcustomfields.com/resources/get_row_index/
	 *
	 * @filter acf/settings/row_index_offset
	 *
	 * @param $row_index_offset
	 * @return int
	 */
	function row_index_offset_setting( $row_index_offset ) {
		return 0;
	}

	/**
	 * Register likes.
	 *
	 * @action init
	 */
	public function register_likes() {
		$this->likes = new Likes();
	}

	/**
	 * Register updates.
	 *
	 * @action init, 1
	 */
	public function register_updates() {
		$this->updates = new Updates();
		$plugin = get_plugin_instance();
		$plugin->add_doc_hooks( $this->updates );
	}

	/**
	 * Print HTML template of custom field group
	 *
	 * @param string $field
	 */
	public function display_field( $field ) {
		if ( isset( $this->custom_field_groups[ $field ] ) ) {
			$this->custom_field_groups[ $field ]->display();
		}
	}

	/**
	 * Use a template for the Project custom post type.
	 *
	 * @filter template_include
	 *
	 * @param string $template Path to template
	 * @return string
	 */
	public function project_template( $template ) {
		global $flint_plugin;

		if ( is_singular( $this->key ) ) {
			$this->enqueue_scripts();

			$template = locate_template( 'single-project.php' );
			if ( ! $template ) {
				$template = $flint_plugin->dir_path . '/templates/single-project.php';
			}
		}
		return $template;
	}

	/**
	 * Change the Comments form title.
	 *
	 * @filter comment_form_defaults
	 *
	 * @param array $defaults The default comment form arguments.
	 * @return array
	 */
	public function project_comments_template( $defaults ) {
		if ( is_singular( $this->key ) ) {
			$defaults['title_reply'] = __( 'Questions & Suggestions', 'flint' );
		}
		return $defaults;
	}

	/**
	 * Enqueue the javascript required for the Project template
	 */
	public function enqueue_scripts() {
		$plugin = get_plugin_instance();
		wp_enqueue_script( 'project', trailingslashit( $plugin->dir_url ) . 'js/project.js', array( 'jquery' ), false, true );
	}
}
