<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

namespace RadiusTheme\Cldoctor;

use RadiusTheme\Cldoctor\Traits\SvgIcons;
use RadiusTheme\Cldoctor\Traits\DataTrait;

class Helper {

	use SvgIcons;
	use DataTrait;

	public static function has_sidebar() {
		return ( self::has_full_width() ) ? false : true;
	}

	public static function has_full_width() {
		$theme_option_full_width = Options::$layout == 'full-width';
		$not_active_sidebar      = ! is_active_sidebar( 'sidebar' );
		$bool                    = $theme_option_full_width || $not_active_sidebar;

		return $bool;
	}

	public static function the_layout_class() {
		$fullwidth_col = ( Options::$options['blog_style'] == 'style1' && is_home() ) ? 'col-sm-10 offset-sm-1 col-12' : 'col-sm-12 col-12';

		$layout_class = self::has_sidebar() ? 'col-lg-8 col-sm-12 col-12' : $fullwidth_col;
		if ( Options::$layout == 'left-sidebar' ) {
			$layout_class .= ' order-lg-2';
		}

		echo esc_attr( apply_filters( 'cldoctor_layout_class', $layout_class ) );
	}

	public static function the_listing_layout_class() {
		$fullwidth_col = ( Options::$options['blog_style'] == 'style1' && is_home() ) ? 'col-sm-10 offset-sm-1 col-12' : 'col-sm-12 col-12';

		$layout_class = self::has_sidebar() ? 'col-lg-9 col-sm-12 col-12' : $fullwidth_col;
		if ( Options::$layout == 'left-sidebar' ) {
			$layout_class .= ' order-lg-2';
		}

		echo esc_attr( apply_filters( 'cldoctor_layout_class', $layout_class ) );
	}

	public static function the_sidebar_class() {
		$sidebar_class = self::has_sidebar() ? 'col-lg-4 col-sm-12 sidebar-break-lg' : 'col-sm-12 col-12';
		echo esc_attr( apply_filters( 'cldoctor_sidebar_class', $sidebar_class ) );
	}

	public static function the_listing_sidebar_class() {
		$sidebar_class = self::has_sidebar() ? 'col-lg-3 col-sm-12 sidebar-break-lg' : 'col-sm-12 col-12';
		echo esc_attr( apply_filters( 'cldoctor_sidebar_class', $sidebar_class ) );
	}

	public static function comments_callback( $comment, $args, $depth ) {
		$args2 = get_defined_vars();
		Helper::get_template_part( 'template-parts/comments-callback', $args2 );
	}

	public static function nav_menu_args() {
		$nav_menu_args = [ 'theme_location' => 'primary', 'container' => 'nav', 'fallback_cb' => false ];

		return $nav_menu_args;
	}

	public static function requires( $filename, $dir = false ) {
		if ( $dir ) {
			$child_file = get_stylesheet_directory() . '/' . $dir . '/' . $filename;

			if ( file_exists( $child_file ) ) {
				$file = $child_file;
			} else {
				$file = get_template_directory() . '/' . $dir . '/' . $filename;
			}
		} else {
			$child_file = get_stylesheet_directory() . '/inc/' . $filename;

			if ( file_exists( $child_file ) ) {
				$file = $child_file;
			} else {
				$file = Constants::$theme_inc_dir . $filename;
			}
		}
		if ( file_exists( $file ) ) {
			require_once $file;
		} else {
			return false;
		}
	}

	public static function get_file( $path ) {
		$file = get_stylesheet_directory_uri() . $path;
		if ( ! file_exists( $file ) ) {
			$file = get_template_directory_uri() . $path;
		}

		return $file;
	}

	public static function get_img( $filename ) {
		$path = '/assets/img/' . $filename;

		return self::get_file( $path );
	}

	public static function get_css( $filename ) {
		$path = '/assets/css/' . $filename . '.css';

		return self::get_file( $path );
	}

	public static function get_maybe_rtl_css( $filename ) {
		if ( is_rtl() ) {
			$path = '/assets/rtl-css/' . $filename . '.css';
			return self::get_file( $path );
		} else {
			return self::get_css( $filename );
		}
	}

	public static function get_rtl_css( $filename ) {
		$path = '/assets/css-rtl/' . $filename . '.css';

		return self::get_file( $path );
	}

	public static function get_js( $filename ) {
		$path = '/assets/js/' . $filename . '.js';

		return self::get_file( $path );
	}

	public static function get_template_part( $template, $args = [] ) {
		extract( $args );

		$template = '/' . $template . '.php';

		if ( file_exists( get_stylesheet_directory() . $template ) ) {
			$file = get_stylesheet_directory() . $template;
		} else {
			$file = get_template_directory() . $template;
		}
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			return false;
		}
	}

	/**
	 * Get all sidebar list
	 *
	 * @return array
	 */
	public static function custom_sidebar_fields() {
		$base                                   = 'cldoctor';
		$sidebar_fields                         = [];
		$sidebar_fields['sidebar']              = esc_html__( 'Sidebar', 'cldoctor' );
		$sidebar_fields['rtcl-archive-sidebar'] = esc_html__( 'Listing Archive Sidebar', 'cldoctor' );
		$sidebar_fields['rtcl-single-sidebar']  = esc_html__( 'Listing Single Sidebar', 'cldoctor' );
		$sidebars                               = get_option( "{$base}_custom_sidebars", [] );

		if ( $sidebars ) {
			foreach ( $sidebars as $sidebar ) {
				$sidebar_fields[ $sidebar['id'] ] = $sidebar['name'];
			}
		}

		return $sidebar_fields;
	}

	/**
	 * Get site header list
	 *
	 * @return array
	 */
	public static function get_header_list() {
		$header_layout = [
			'1' => [
				'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/header-1.jpg',
				'name'  => esc_html__( 'Style 1', 'cldoctor' )
			],
//			'2' => [
//				'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/header-1.jpg',
//				'name'  => esc_html__( 'Style 2', 'cldoctor' )
//			],
		];
		return apply_filters( 'cldoctor_header_layout', $header_layout );
	}

	/**
	 * Get site footer list
	 *
	 * @return array
	 */
	public static function get_footer_list() {
		$footer_layout = [
			'1' => [
				'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/footer-1.jpg',
				'name'  => esc_html__( 'Layout 1', 'cldoctor' )
			],
			'2' => [
				'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/footer-2.jpg',
				'name'  => esc_html__( 'Layout 2', 'cldoctor' )
			],
			'3' => [
				'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/footer-3.jpg',
				'name'  => esc_html__( 'Layout 2', 'cldoctor' )
			],
			'4' => [
				'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/footer-4.jpg',
				'name'  => esc_html__( 'Layout 2', 'cldoctor' )
			]
		];
		return apply_filters( 'cldoctor_footer_layout', $footer_layout );
	}

	/**
	 * Get site search style
	 *
	 * @return array
	 */
	public static function get_search_form_style() {
		$style = [
			'standard' => esc_html__( 'Standard', 'cldoctor' ),
		];

		if ( class_exists( 'RtclPro' ) ) {
			$style = array_merge( $style, [
				'popup'      => esc_html__( 'Popup', 'cldoctor' ),
				'suggestion' => esc_html__( 'Auto Suggestion', 'cldoctor' ),
				'dependency' => esc_html__( 'Dependency Selection', 'cldoctor' ),
			] );
		}

		return $style;
	}

	public static function get_custom_listing_template( $template, $echo = true, $args = [], $path = 'custom/' ) {
		$template = 'classified-listing/' . $path . $template;
		if ( $echo ) {
			self::get_template_part( $template, $args );
		} else {
			$template .= '.php';

			return $template;
		}
	}

	public static function get_custom_store_template( $template, $echo = true, $args = [] ) {
		$template = 'classified-listing/store/custom/' . $template;
		if ( $echo ) {
			self::get_template_part( $template, $args );
		} else {
			$template .= '.php';

			return $template;
		}
	}

	public static function wp_set_temp_query( $query ) {
		global $wp_query;
		$temp     = $wp_query;
		$wp_query = $query;

		return $temp;
	}

	public static function wp_reset_temp_query( $temp ) {
		global $wp_query;
		$wp_query = $temp;
		wp_reset_postdata();
	}

	public static function cldoctor_excerpt( $limit ) {
		if (!empty($limit)) {
			$limit = $limit;
		} else {
			$limit = 0;
		}
	    $excerpt = explode(' ', get_the_excerpt(), $limit);
	    if (count($excerpt)>=$limit) {
	        array_pop($excerpt);
	        $excerpt = implode(" ",$excerpt).'';
	    } else {
	        $excerpt = implode(" ",$excerpt);
	    }
	    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);

		return $excerpt;
	}

	public static function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = "$r, $g, $b";

		return $rgb;
	}

	public static function socials() {
		$rdtheme_socials = [
			'facebook'  => [
				'class' => 'facebook',
				'icon' 	=> 'fa-brands fa-facebook-f',
				'url'  	=> Options::$options['facebook'],
			],
			'twitter'   => [
				'class' => 'twitter',
				'icon' 	=> 'fa-brands fa-x-twitter',
				'url'  	=> Options::$options['twitter'],
			],
			'instagram' => [
				'class' => 'instagram',
				'icon' 	=> 'fa-brands fa-instagram',
				'url'  	=> Options::$options['instagram'],
			],
			'pinterest' => [
				'class' => 'pinterest',
				'icon' 	=> 'fa-brands fa-pinterest-p',
				'url'  	=> Options::$options['pinterest'],
			],
			'linkedin'  => [
				'class' => 'linkedin',
				'icon' 	=> 'fa-brands fa-linkedin-in',
				'url'  	=> Options::$options['linkedin'],
			],
			'youtube'   => [
				'class' => 'youtube',
				'icon' 	=> 'fa-brands fa-youtube',
				'url'  	=> Options::$options['youtube'],
			],
			'skype'     => [
				'class' => 'skype',
				'icon' 	=> 'fa-brands fa-skype',
				'url'  	=> Options::$options['skype'],
			],
			'tiktok'     => [
				'class' => 'tiktok',
				'icon' 	=> 'fa-brands fa-tiktok',
				'url'  	=> Options::$options['tiktok'],
			],
		];

		return array_filter( $rdtheme_socials, [ __CLASS__, 'filter_social' ] );
	}

	public static function filter_social( $args ) {
		return ( $args['url'] != '' );
	}

	public static function the_title() {
		if ( is_404() ) {
			$banner_title = Options::$options['error_banner_title'];
		} elseif ( is_search() ) {
			$banner_title = esc_html__( 'Search Results for : ', 'cldoctor' ) . get_search_query();
		} elseif ( is_home() ) {
			if ( ! empty( Options::$options['blog_banner_title'] ) ) {
				$banner_title = Options::$options['blog_banner_title'];
			} elseif ( get_option( 'page_for_posts' ) ) {
				$banner_title = get_the_title( get_option( 'page_for_posts' ) );
			} else {
				$banner_title = apply_filters( 'cldoctor_blog_title', esc_html__( 'All Posts', 'cldoctor' ) );
			}
		} elseif ( is_author() ) {
			$banner_title = get_the_author();
		} elseif ( is_archive() ) {
			if ( is_post_type_archive( 'rtcl_listing' ) ) {
				if ( ! empty( Options::$options['listing_archive_title'] ) ) {
					if ( ! empty( Options::$options['listing_archive_title_text'] ) ) {
						$banner_title = Options::$options['listing_archive_title_text'];
					} else {
						$banner_title = post_type_archive_title( '', false );
					}
				} else {
					$banner_title = '';
				}
			} else {
				$banner_title = single_term_title( '', false );

				if ( empty( $banner_title ) ) {
					$banner_title = post_type_archive_title( '', false );
				}
			}
		} elseif ( is_singular( 'rtcl_listing' ) ) {
			$listing_type  = get_post_meta( get_the_ID(), 'ad_type', true );
			$banner_title = $listing_type . esc_html__( ' Details', 'cldoctor' );
		} else {
			$banner_title = get_the_title();
		}
		echo wp_kses_post( $banner_title );
	}
}