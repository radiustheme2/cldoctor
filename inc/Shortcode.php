<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace RadiusTheme\Cldoctor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use Rtcl\Controllers\BusinessHoursController as BHS;

/**
 * ThemeJetpack Class
 */
class Shortcode {
	protected static $instance = null;

	public static $shortcode_list = [
		'header'    => [
			'tag'      => 'cldoctor_listing_header',
			'callback' => 'render_listing_header',
		],
		'tab'   => [
			'tag'      => 'cldoctor_listing_tab',
			'callback' => 'render_listing_tab',
		],
		'sidebar'   => [
			'tag'      => 'cldoctor_listing_sidebar',
			'callback' => 'render_listing_sidebar',
		],
	];

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		add_filter(
			'rtcl/fb/single_layout/fields',
			function ( $fields ) {
				$shortcode_hints = '';
				foreach ( self::$shortcode_list as $label => $shortcode ) {
					$shortcode_hints .= '<br><b>' . ucfirst( $label ) . ":</b> [{$shortcode['tag']}]";
				}

				$fields['shortcode']['editor']['hints']['value'] = 'Available Shortcodes: ' . $shortcode_hints;

				return $fields;
			}
		);

		foreach ( self::$shortcode_list as $shortcode ) {
			add_shortcode( $shortcode['tag'], [ $this, $shortcode['callback'] ] );
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function render_listing_header() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			ob_start();

			Helper::get_custom_listing_template( 'single/content-single-head' );

			return ob_get_clean();
		}

		return '';
	}

	public function render_listing_tab() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			ob_start();

			Helper::get_custom_listing_template( 'single/content-single-tab' );

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
	public function render_listing_sidebar() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );

			ob_start();
			?>

			<div class="listing-sidebar">
				<div class="sidebar-top-default-information">
					<?php $listing->the_user_info(); ?>
					<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){ ?>
						<div class="rtcl-single-listing-details map-box">
							<?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
				if ( ! empty( BHS::get_business_hours( $listing->get_id() ) ) ):
					do_action( 'rtcl_single_listing_business_hours' );
				endif;

				if ( Helper::has_sidebar() ) {
					/**
					 * Hook: rtcl_sidebar.
					 *
					 * @hooked rtcl_get_sidebar - 10
					 */
					do_action( 'rtcl_sidebar' );
				}
			?>

			<?php
			return ob_get_clean();
		}

		return '';
	}
}
