<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RadiusTheme\Cldoctor\Traits;
use RadiusTheme\Cldoctor\Options;
use RtclPro\Helpers\Fns;

trait DataTrait {

  protected static $instance = null;

  public static function instance() {
    if ( null == self::$instance ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /* = Cldoctor Logo Settings
  ==========================================================*/
  public static function cldoctor_main_logo(){
    $meta_logo = get_post_meta(get_the_ID(), 'cldoctor_layout_settings', true);
    if ( !empty($meta_logo['cldoctor_logo_version']) ) {
      $logo_one = wp_get_attachment_image( $meta_logo['cldoctor_logo_version'], 'full' );
    } else {
      if (!empty( Options::$options['logo'] )) {
        $logo_one = wp_get_attachment_image( Options::$options['logo'], 'full' );
      } else {
        $logo_one = '';
      }
    }
    return $logo_one;
  }
  
  	public static function rt_logo_two(){
		$meta_logo = get_post_meta(get_the_ID(), 'cldoctor_layout_settings', true);
		if ( !empty($meta_logo['cldoctor_logo_version']) ) {
			$logo_two = wp_get_attachment_image( $meta_logo['cldoctor_logo_version'], 'full' );
		} else {
			if (!empty( Options::$options['logo_dark'] )) {
				$logo_two = wp_get_attachment_image( Options::$options['logo_dark'], 'full' );
			} else {
				$logo_two = '';
			}
		}
		return $logo_two;
  	}

  	public static function rt_mobile_logo(){
		if ( has_custom_logo() ) {
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$mobile_logo = wp_get_attachment_image( $custom_logo_id, 'full' );
		} else { 
			if (!empty( Options::$options['mobile_logo'] )) {
				$mobile_logo = wp_get_attachment_image( Options::$options['mobile_logo'], 'full' );
			} else {
				$mobile_logo = '';
			}
		}
		return $mobile_logo;
  	}

  	public static function get_primary_color() {
		return apply_filters( 'rt_primary_color', Options::$options['primary_color'] );
	}

	public static function get_secondary_color() {
		return apply_filters( 'rt_secondary_color', Options::$options['secondary_color'] );
	}

	public static function get_body_color() {
		return apply_filters( 'rt_body_color', Options::$options['body_color'] );
	}

	public static function get_heading_color() {
		return apply_filters( 'rt_heading_color', Options::$options['heading_color'] );
	}

 	public static function get_button_color1() {
		return apply_filters( 'rt_heading_color_1', Options::$options['button_color_1'] );
	}

  	public static function get_button_color2() {
		return apply_filters( 'rt_heading_color_2', Options::$options['button_color_2'] );
	}

  	public static function is_chat_enabled() {
		if ( Options::$options['header_chat_icon'] && class_exists( 'Rtcl' ) && class_exists( 'RtclPro' ) ) {
			if ( Fns::is_enable_chat() ) {
				return true;
			}
		}
		return false;
	}

	public static function is_chat_enabled_mobile() {
		if ( Options::$options['header_chat_btn_mobile'] && class_exists( 'Rtcl' ) && class_exists( 'RtclPro' ) ) {
			if ( Fns::is_enable_chat() ) {
				return true;
			}
		}
		return false;
	}

	public static function is_header_link_btn_enabled() {
		$btn_flag = get_theme_mod( 'header_btn' );
		if ( empty( $btn_flag ) ) {
			return false;
		}
		return true;
	}

  	public static function is_header_login_enabled() {
		$login_btn = get_theme_mod( 'header_login_icon' );
		if ( empty( $login_btn ) ) {
			return false;
		}
		return true;
	}

  	public static function is_header_chat_enabled() {
		$login_btn = get_theme_mod( 'header_chat_icon' );
		if ( empty( $login_btn ) ) {
			return false;
		}
		return true;
	}

	public static function is_copyright_area_enabled() {
		$copyright_area_flag = get_theme_mod( 'copyright_area' );
		if ( empty( $copyright_area_flag ) ) {
			return false;
		}
		return true;
	}
	
	public static function is_listing_archive_title_enabled() {
		$archive_title_flag = get_theme_mod( 'listing_archive_title' );
		if ( empty( $archive_title_flag ) ) {
			return false;
		}
		return true;
	}
	public static function is_listing_archive_custom_search_filter() {
		$filter_type = get_theme_mod( 'listing_archive_filter_type' );
		if ( $filter_type == 'custom' ) {
			return true;
		}
		return false;
	}

	public static function is_blog_banner_enable() {
		$blog_banner = get_theme_mod( 'blog_banner' );
		if( $blog_banner == 'off' ) {
			return false;
		}
		return true;
	}
	
	public static function is_single_post_banner_enable() {
		$single_post_banner = get_theme_mod( 'single_post_banner' );
		if( $single_post_banner == 'off' ) {
			return false;
		}
		return true;
	}
	public static function is_page_banner_enable() {
		$page_banner = get_theme_mod( 'page_banner' );
		if( $page_banner == 'off' ) {
			return false;
		}
		return true;
	}

	public static function is_error_banner_enable() {
		$error_banner = get_theme_mod( 'error_banner' );
		if( $error_banner == 'off' ) {
			return false;
		}
		return true;
	}
	
	public static function is_listing_archive_banner_enable() {
		$listing_archive_banner = get_theme_mod( 'listing_archive_banner' );
		if( $listing_archive_banner == 'off' ) {
			return false;
		}
		return true;
	}

	public static function is_listing_single_banner_enable() {
		$listing_archive_banner = get_theme_mod( 'listing_single_banner' );
		if( $listing_archive_banner == 'off' ) {
			return false;
		}
		return true;
	}

	public static function is_blog_layout_grid_enable() {
		$blog_style = get_theme_mod( 'blog_style' );
		if( $blog_style == '2' ) {
			return true;
		}
		return false;
	}

	public static function is_preloader_enabled() {
		$preloader = get_theme_mod( 'preloader' );
		if ( empty( $preloader ) ) {
			return false;
		}
		return true;
	}

	public static function rt_grid_options(){
		return [
			'1'  => esc_html__( '1 Columns', 'cldoctor' ),
			'2'  => esc_html__( '2 Columns', 'cldoctor' ),
			'3'  => esc_html__( '3 Columns', 'cldoctor' ),
			'4'  => esc_html__( '4 Columns', 'cldoctor' ),
			'5'  => esc_html__( '5 Columns', 'cldoctor' ),
			'6'  => esc_html__( '6 Columns', 'cldoctor' ),
		];
	}

  public static function rt_number_options(){
		return [
			'1'  => esc_html__( '1', 'cldoctor' ),
			'2'  => esc_html__( '2', 'cldoctor' ),
			'3'  => esc_html__( '3', 'cldoctor' ),
			'4'  => esc_html__( '4', 'cldoctor' ),
			'5'  => esc_html__( '5', 'cldoctor' ),
			'6'  => esc_html__( '6', 'cldoctor' ),
		];
	}

}

