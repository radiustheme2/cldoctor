<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_editor_style( 'style-editor.css' );

if ( !isset( $content_width ) ) {
	$content_width = 1240;
}

class Cldoctor_Main {
	public $theme   = 'cldoctor';
	public $action  = 'cldoctor_theme_init';
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
		add_action( 'admin_notices',     array( $this, 'plugin_update_notices' ) );
		$this->includes();
	}
	public function load_textdomain(){
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}

	public function includes(){
		do_action( $this->action );
		if ( ! defined( 'RT_DEBUG' ) || ! RT_DEBUG ) {
			require_once get_template_directory() . '/lib/updater/theme-updater.php';
			require_once get_template_directory() . '/lib/updater/lc-utility.php';
			require_once get_template_directory() . '/lib/updater/lc-helper.php';
		}
		require_once get_template_directory() . '/lib/class-tgm-plugin-activation.php';
		require_once get_template_directory() . '/inc/Customizer/Typography/Fonts-List.php';
		require_once get_template_directory() . '/inc/init.php';
		require_once get_template_directory() . '/inc/Customizer/sanitization.php';
	}

	public function plugin_update_notices() {
		$plugins = array();

		if ( defined( 'CLDOCTOR_CORE' ) ) {
			if ( version_compare( CLDOCTOR_CORE, '2.2.2', '<' ) ) {
				$plugins[] = 'Cldoctor Core';
			}
		}

		foreach ( $plugins as $plugin ) {
			/* translators: %1$s is the plugin name, %2$s is the update page URL */
			$notice = '<div class="error"><p>' . sprintf( __( "Please update plugin <b><i>%1\$s</b></i> to the latest version otherwise some functionalities will not work properly. You can update it from <a href='%2\$s'>here</a>", 'cldoctor' ), $plugin, menu_page_url( 'cldoctor-install-plugins', false ) ) . '</p></div>';
			echo wp_kses_post( $notice );
		}
	}
}

new Cldoctor_Main;