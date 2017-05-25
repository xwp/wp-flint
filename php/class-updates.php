<?php
/**
 * Adds News Updates to Projects.
 *
 * @package Flint
 */

namespace Flint;

class Updates {

	/**
	 * Post type key.
	 *
	 * @var String
	 */
	public $key = 'updates';

	/**
	 * Taxonomy key.
	 *
	 * @var String
	 */
	public $tax_key = 'project';

	/**
	 * Register post type.
	 *
	 * @action init
	 */
	public function register_post_type() {
		$labels = array(
			'name'          => __( 'Updates', 'flint' ),
			'singular_name' => __( 'Update', 'flint' ),
		);

		$args = array(
			'label'               => __( 'Updates', 'flint' ),
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
			'menu_icon'           => 'dashicons-welcome-write-blog',
			'supports'            => array( 'title', 'editor', 'author' ),
		);

		register_post_type( $this->key, $args );
	}

	/**
	 * Register taxonomy.
	 *
	 * @action init
	 */
	function register_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Projects', 'Taxonomy General Name', 'flint' ),
			'singular_name'              => _x( 'Project', 'Taxonomy Singular Name', 'flint' ),
			'menu_name'                  => __( 'Projects', 'flint' ),
			'all_items'                  => __( 'All Projects', 'flint' ),
			'parent_item'                => __( 'Parent Project', 'flint' ),
			'parent_item_colon'          => __( 'Parent Project:', 'flint' ),
			'new_item_name'              => __( 'New Project Name', 'flint' ),
			'add_new_item'               => __( 'Add New Project', 'flint' ),
			'edit_item'                  => __( 'Edit Project', 'flint' ),
			'update_item'                => __( 'Update Project', 'flint' ),
			'view_item'                  => __( 'View Project', 'flint' ),
			'separate_items_with_commas' => __( 'Separate projects with commas', 'flint' ),
			'add_or_remove_items'        => __( 'Add or remove projects', 'flint' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'flint' ),
			'popular_items'              => __( 'Popular Projects', 'flint' ),
			'search_items'               => __( 'Search Projects', 'flint' ),
			'not_found'                  => __( 'Not Found', 'flint' ),
			'no_terms'                   => __( 'No projects', 'flint' ),
			'items_list'                 => __( 'Projects list', 'flint' ),
			'items_list_navigation'      => __( 'Projects list navigation', 'flint' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => false,
			'show_in_menu'      => false,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
			'capabilities'      => array(
				'manage_terms' => 'nobody',
				'edit_terms'   => 'nobody',
				'delete_terms' => 'nobody',
				'assign_terms' => 'edit_posts',
			),
		);

		register_taxonomy( 'project', array( 'updates' ), $args );
	}

	/**
	 * Add Project taxonomy terms when a Project is saved.
	 * Creates a Project (Taxonomy) term for each Project (Post Type) post.
	 *
	 * @action save_post
	 *
	 * @param int $post_id
	 */
	public function add_taxonomy_terms( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$post   = get_post( $post_id );
		$plugin = get_plugin_instance();

		if ( $plugin->projects->key !== $post->post_type ) {
			return;
		}

		if ( 'publish' !== get_post_status( $post ) || empty( $post->post_name ) ) {
			return;
		}

		$term = get_term_by( 'slug', $post->post_name, $this->tax_key );

		if ( false !== $term ) {
			return;
		}

		$term = wp_insert_term(
			get_the_title( $post ),
			$this->tax_key,
			array(
				'slug' => $post->post_name,
			)
		);

		if ( is_wp_error( $term ) || ! isset( $term['term_id'] ) ) {
			return;
		}

		add_term_meta( $term['term_id'], 'project_id', $post_id );
		add_term_meta( $term['term_id'], 'project_slug', $post->post_name );
	}

	/**
	 * Only show Project terms if the current user is on the team
	 *
	 * @filter get_terms
	 *
	 * @param array         $terms      Array of found terms.
	 * @param array         $taxonomies An array of taxonomies.
	 * @param array         $args       An array of get_terms() arguments.
	 * @return array
	 */
	public function filter_terms( $terms, $taxonomies, $args ) {
		// Don't filter in front end
		if ( ! is_admin() ) {
			return $terms;
		}

		// Only filter if the tax query is specific to our taxonomy
		if ( 1 !== count( $taxonomies ) || ! isset( $taxonomies[0] ) || $this->tax_key !== $taxonomies[0] ) {
			return $terms;
		}

		// Only filter if the tax query is for all fields and includes empty terms
		if ( 'all' !== $args['fields'] || $args['hide_empty'] ) {
			return $terms;
		}

		$plugin = get_plugin_instance();

		// Only filter if our plugin is using the Roles custom field group
		if ( ! isset( $plugin->projects->custom_field_groups['roles'] ) ) {
			return $terms;
		}

		/**
		 * @var $roles Roles
		 */
		$roles = $plugin->projects->custom_field_groups['roles'];

		foreach ( $terms as $key => $term ) {
			$project_id = get_term_meta( $term->term_id, 'project_id', true );
			$role_index = $roles->get_role_index( $project_id );
			if ( false === $role_index ) {
				unset( $terms[ $key ] );
			}
		}

		return array_values( $terms );
	}
}
