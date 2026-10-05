<?php

namespace RadiusTheme\Cldoctor\Customizer;

use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Customizer\Settings\Blog;
use RadiusTheme\Cldoctor\Customizer\Settings\Blog_Layout;
use RadiusTheme\Cldoctor\Customizer\Settings\Color;
use RadiusTheme\Cldoctor\Customizer\Settings\Contact_Info;
use RadiusTheme\Cldoctor\Customizer\Settings\Error;
use RadiusTheme\Cldoctor\Customizer\Settings\Error_Layout;
use RadiusTheme\Cldoctor\Customizer\Settings\General;
use RadiusTheme\Cldoctor\Customizer\Settings\Header;
use RadiusTheme\Cldoctor\Customizer\Settings\Footer;
use RadiusTheme\Cldoctor\Customizer\Settings\Listings;
use RadiusTheme\Cldoctor\Customizer\Settings\Listing_Archive_Layout;
use RadiusTheme\Cldoctor\Customizer\Settings\Listing_Single_Layout;
use RadiusTheme\Cldoctor\Customizer\Settings\Page_Layout;
use RadiusTheme\Cldoctor\Customizer\Settings\Post;
use RadiusTheme\Cldoctor\Customizer\Settings\Post_Layout;
use RadiusTheme\Cldoctor\Customizer\Typography\Typography;

Helper::requires( 'Customizer/Controls/Sanitization.php');

class Init {
	protected static $instance = null;

	/**
	 * Create an inaccessible constructor.
	 */
	private function __construct() {
		$this->includes();
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function includes() {
		new General();
		new Header();
		new Footer();
		new Blog();
		new Post();
		new Error();
		new Contact_Info();
		new Typography();
		new Color();
		// Layout
		new Blog_Layout();
		new Post_Layout();
		new Page_Layout();
		new Error_Layout();
		// Listings
		if ( class_exists( 'Rtcl' ) ) {
			new Listings();
			new Listing_Archive_Layout();
			new Listing_Single_Layout();
		}
	}
}