<?php
/**
 * Sets up the Business Model custom field group.
 *
 * @package Flint
 */

namespace Flint;

class Roles implements Field_Group {

	/**
	 * Register fields.
	 */
	public function register_fields() {
		if ( function_exists ('acf_add_local_field_group') ) {
			acf_add_local_field_group(array (
				'key' => 'group_58e32a580f7f6',
				'title' => 'Team Members',
				'fields' => array (
					array (
						'key' => 'field_58e32a75f2e29',
						'label' => 'Roles',
						'name' => 'roles',
						'type' => 'repeater',
						'instructions' => 'Create project roles and assign team members to them.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => 'field_58e32a9bf2e2a',
						'min' => 1,
						'max' => 0,
						'layout' => 'row',
						'button_label' => 'Add Role',
						'sub_fields' => array (
							array (
								'key' => 'field_58e32a9bf2e2a',
								'label' => 'Role',
								'name' => 'role',
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'None' => 'None',
									'Architect' => 'Architect',
									'Designer' => 'Designer',
									'Engineer' => 'Engineer',
									'Frontend Engineer' => 'Frontend Engineer',
									'Product Owner' => 'Product Owner',
									'QA' => 'QA',
									'Team Lead' => 'Team Lead',
									'User Experience' => 'User Experience',
									'Other' => 'Other',
								),
								'default_value' => array (
									0 => 'None',
								),
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
							array (
								'key' => 'field_58e32afaf2e2b',
								'label' => 'Role Title',
								'name' => 'role_title',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_58e32a9bf2e2a',
											'operator' => '==',
											'value' => 'Other',
										),
									),
								),
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array (
								'key' => 'field_58f6cc71a91d0',
								'label' => 'Role Description',
								'name' => 'role_description',
								'type' => 'textarea',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'maxlength' => '',
								'rows' => '2',
								'new_lines' => 'wpautop',
							),
							array (
								'key' => 'field_58e32b2ff2e2c',
								'label' => 'Person',
								'name' => 'user',
								'type' => 'user',
								'instructions' => 'Leave empty if the role is currently available.',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'role' => '',
								'allow_null' => 1,
								'multiple' => 0,
							),
						),
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'project',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			));
		};
	}

	/**
	 * Print the HTML template.
	 */
	public function display() {
		$this->save();

		if ( have_rows( 'roles' ) ) {
			echo '<ul class="roles">';
			$can_join = is_user_logged_in() && is_single();
			$current_user_id = get_current_user_id();

			if ( $can_join && have_rows( 'roles' ) ) {
				while( have_rows( 'roles' ) ) {
					the_row();
					$user = get_sub_field( 'user' );
					if ( $user && $current_user_id === $user['ID'] ) {
						$can_join = false;
						break;
					}
				}
				reset_rows();
			}
			while( have_rows( 'roles' ) ) {
				the_row();
				?>
				<li>
					<?php
					$user = get_sub_field( 'user' );
					$role = get_sub_field( 'role' );
					if ( 'Other' === $role ) {
						$role = get_sub_field( 'role_title' );
					}
					?>
					<?php if ( $user ) : ?>
						<div class="closed">
							<?php echo wp_kses_post( $user['user_avatar'] ); ?>
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<span class="name"><?php echo esc_html( $user['display_name'] ); ?></span>
						</div>
					<?php elseif ( $can_join ) : ?>
						<div class="open">
							<?php echo wp_kses_post( get_avatar( $current_user_id ) ); ?>
							<span class="avatar empty">
								<a href="javascript:document.getElementById('row-<?php echo esc_attr( get_row_index() ); ?>').submit();" class="join">Join</a>
							</span>
							<span class="role"><?php echo esc_html( $role ); ?></span>
							<form method="post" id="row-<?php echo esc_attr( get_row_index() ); ?>">
								<input type="hidden" name="join-role" value="<?php echo esc_attr( get_row_index() ); ?>" />
							</form>
						</div>
					<?php else : ?>
						<div class="locked">
							<span class="avatar empty"></span>
							<span class="role"><?php echo esc_html( $role ); ?></span>
						</div>
					<?php endif; ?>
				</li>
				<?php
			}
			reset_rows();
			echo '</ul>';
		}
	}

	/**
	 * Save submitted roles form
	 */
	public function save() {
		if ( isset( $_POST['join-role'] ) && is_user_logged_in() ) {
			$row = filter_var( $_POST['join-role'], FILTER_VALIDATE_INT );
			update_sub_field( array( 'roles', $row, 'user' ), get_current_user_id() );
		}
	}
}
