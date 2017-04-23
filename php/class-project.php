<?php
/**
 * Sets up the Flint Project post type.
 *
 * @package Flint
 */

namespace Flint;

class Project {

	/**
	 * Custom field groups.
	 *
	 * @var Field_Group[]
	 */
	public $custom_field_groups;

	/**
	 * Register post types.
	 *
	 * @action init
	 */
	public function register_post_types() {
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
			'rewrite'             => array( 'slug' => 'project', 'with_front' => true ),
			'query_var'           => true,
			'menu_icon'           => 'dashicons-pressthis',
			'supports'            => array( 'title', 'editor', 'comments' ),
		);

		register_post_type( 'project', $args );
	}

	/**
	 * Register custom fields.
	 *
	 * @action init
	 */
	public function register_custom_fields() {
		$this->custom_field_groups = array(
			'business_model' => new Business_Model(),
			'summary'        => new Summary(),
			'roles'          => new Roles(),
			'timeline'       => new Timeline(),
			'feature_color'  => new Feature_Color(),
		);

		foreach ( $this->custom_field_groups as $custom_field_group ) {
			$custom_field_group->register_fields();
		}
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

		if ( is_singular( 'project' ) ) {
			$template = locate_template( 'single-project.php' );
			if ( ! $template ) {
				$template = $flint_plugin->dir_path . '/templates/single-project.php';
			}
		}
		return $template;
	}

	/**
	 * Change the Comments are title.
	 *
	 * @filter comment_form_defaults
	 *
	 * @param array $defaults The default comment form arguments.
	 * @return array
	 */
	public function project_comments_template( $defaults ) {
		if ( is_singular( 'project' ) ) {
			$defaults['title_reply'] = __( 'Questions & Suggestions', 'flint' );
		}
		return $defaults;
	}

	/**
	 * Update meta to indicate open positions
	 *
	 * @action save_post, 20
	 *
	 * @param int $post_id
	 * @param \WP_Post $post
	 */
	public function update_project_meta( $post_id, $post ) {
		if ( 'project' !== $post->post_type ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$is_open = false;

		if ( have_rows( 'roles', $post_id ) ) {
			while( have_rows( 'roles', $post_id ) ) {
				the_row();
				$user = get_sub_field( 'user' );
				if ( ! $user ) {
					$is_open = true;
					break;
				}
			}
			reset_rows();
		}

		update_post_meta( $post_id, 'is_open', $is_open ? '1' : '0' );
	}
}
