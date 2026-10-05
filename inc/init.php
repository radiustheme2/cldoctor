<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RadiusTheme\Cldoctor\General;
use RadiusTheme\Cldoctor\Layouts;
use RadiusTheme\Cldoctor\Scripts;
use RadiusTheme\Cldoctor\Options;
use RadiusTheme\Cldoctor\Constants;
use RadiusTheme\Cldoctor\TGM_Config;
use RadiusTheme\Cldoctor\Customizer\Init;
use RadiusTheme\Cldoctor\Listing_Functions;
use RadiusTheme\Cldoctor\Shortcode;

require_once __DIR__ . '/../vendor/autoload.php';

final class Includes {
	private $suffix;
	private $version;
	private static $singleton = false;

	/**
	 * Create an inaccessible constructor.
	 */
	private function __construct() {
		$this->suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$this->version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : Constants::$theme_version;

		$this->init();
	}

	/**
	 * Fetch an instance of the class.
	 */
	final public static function getInstance() {
		if ( self::$singleton === false ) {
			self::$singleton = new self();
		}

		return self::$singleton;
	}

	/**
	 * Classified Listing Constructor.
	 */
	protected function init() {
		new Constants();
		new TGM_Config();
		Options::instance();
		General::instance();
		Scripts::instance();
		Layouts::instance();
		if ( class_exists( 'WP_Customize_Control' ) ) {
			Init::instance();
		}
		if (class_exists('Rtcl')) {
			Listing_Functions::instance();
			Shortcode::instance();
		}
	}

}

/**
 * @return bool|Includes
 */
function Includes() {
	return Includes::getInstance();
}

Includes();
