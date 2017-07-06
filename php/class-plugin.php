<?php
/**
 * Bootstraps the Flint plugin.
 *
 * @package Flint
 */

namespace Flint;

/**
 * Main plugin bootstrap file.
 */
class Plugin extends Plugin_Base {

	/**
	 * Project post type.
	 *
	 * @var Projects
	 */
	public $projects;

	/**
	 * Shortcodes.
	 *
	 * @var Shortcodes
	 */
	public $shortcodes;

	/**
	 * Initiate the plugin resources.
	 *
	 * Priority is 9 because WP_Customize_Widgets::register_settings() happens at
	 * after_setup_theme priority 10. This is especially important for plugins
	 * that extend the Customizer to ensure resources are available in time.
	 *
	 * @action after_setup_theme, 9
	 */
	public function init() {
		$this->config = apply_filters( 'flint_plugin_config', $this->config, $this );

		if ( class_exists( 'acf_pro' ) ) {
			$this->projects = new Projects();
			$this->add_doc_hooks( $this->projects );

			$this->shortcodes = new Shortcodes();
			$this->add_doc_hooks( $this->shortcodes );
		} else {
			$this->acf_inactive_error();
		}
	}

	/**
	 * Register styles.
	 *
	 * @action wp_enqueue_scripts
	 */
	public function register_styles() {
		wp_enqueue_style( 'flint', trailingslashit( $this->dir_url ) . 'css/style.css', array(), filemtime( trailingslashit( $this->dir_url ) . 'css/style.css' ), false );
	}

	/**
	 * Display an error if the Advanced Custom Fields plugin is inactive.
	 */
	public function acf_inactive_error() {
		printf(
			'<div class="error"><p>%s</p></div>',
			esc_html( __( 'Flint requires the Advanced Custom Fields PRO plugin.', 'flint' ) )
		);
	}
}
