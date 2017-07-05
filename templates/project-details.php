<?php global $flint_plugin; ?>

<?php if ( $flint_plugin->projects->field_is_valid( 'video_pitch' ) ) : ?>
	<div class="entry-content container video-pitch">
		<?php $flint_plugin->projects->display_field( 'video_pitch' ); ?>
	</div>
<?php endif; ?>

<div class="entry-content container overview">
	<h2><?php esc_html_e( 'Overview', 'flint' ); ?></h2>
	<?php the_content(); ?>
</div>

<?php if ( $flint_plugin->projects->field_is_valid( 'roles' ) ) : ?>
	<div class="entry-content role-descriptions container">
		<h2><?php esc_html_e( 'Team Roles', 'flint' ); ?></h2>
		<div class="team-roles">
		<?php
		while( have_rows( 'roles' ) ) {
			the_row();
			$role = get_sub_field( 'role' );
			if ( 'Other' === $role ) {
				$role = get_sub_field( 'role_title' );
			}
			?>
			<div class="team-role">
				<h3><?php echo esc_html( $role ); ?></h3>
				<?php echo wp_kses_post( get_sub_field( 'role_description' ) ); ?>
			</div>
			<?php
		}
		reset_rows();
		?>
		</div>
	</div>
<?php endif; ?>

<?php if ( $flint_plugin->projects->field_is_valid( 'business_model' ) ) : ?>
	<div class="entry-content business-model container">
		<?php $flint_plugin->projects->display_field( 'business_model' ); ?>
	</div>
<?php endif; ?>

<?php if ( $flint_plugin->projects->field_is_valid( 'timeline' ) ) : ?>
	<div class="entry-content timeline container">
		<?php $flint_plugin->projects->display_field( 'timeline' ); ?>
	</div>
<?php endif; ?>
