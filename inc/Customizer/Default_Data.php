<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.1
 */

namespace RadiusTheme\Cldoctor\Customizer;

class Default_Data {

	// Customizer Default Data

	public static function default_values() {
		$customizer_defaults = [

			// General
			'logo'                           => '',
			'logo_dark'                      => '',
			'logo_width'                     => '200px',
			'mobile_logo'                    => '',
			'mobile_logo_width'              => '150px',
			'banner_image'                   => '',
			'preloader'                 	 => '',
			'preloader_gif'                  => '',
			'back_to_top'                    => '',
			'remove_admin_bar'               => 0,

			// Header
			'top_bar'                        => 0,
			'sticky_header'                  => 0,
			'header_btn'                     => '',
			'header_btn_txt'                 => 'Post Your Add',
			'header_btn_url'                 => '',
			'breadcrumb'                     => '',
			'header_login_icon'              => '',
			'header_login_text'              => 'Account',
			'header_logout_text'             => 'Login/Register',
			'header_chat_icon'               => '',
			'header_chat_text'               => 'Live Chat',
			'header_style'                   => '1',
			'header_width'                   => 'box-width',
			'menu_alignment'                 => 'menu-right',
			'tr_header'                      => 0,
			'header_transparent_color'       => 'rgba(255, 255, 255, .5)',
			// Mobile
			'header_link_btn_mobile'         => 1,
			'header_login_btn_mobile'        => 1,
			'header_chat_btn_mobile'         => '',

			// Blog Archive
			'blog_style'                     => '1',
			'blog_grid'                      => '2',
			'blog_medium_grid'               => '1',
			'blog_date'                      => 1,
			'blog_author_name'               => 1,
			'blog_cat_visibility'            => 1,
			'blog_comment_num'               => 0,
			'excerpt_length'                 => 20,
			'blog_button'                    => 0,

			// Single Post
			'post_date'                      => 1,
			'post_author_name'               => 1,
			'post_comment_num'               => 1,
			'post_cats'                      => 0,
			'post_details_related_section'   => 0,
			'post_tag'                       => 1,
			'post_social_icon'               => 0,

			// Error
			'error_page_banner_title'        => 'Error Page',
			'error_image'               	 => '',
			'error_text'                     => 'Error Page',
			'error_subtitle'                 => 'Sorry! This Page is Not Available!',
			'error_buttontext'               => 'Go To Home Page',

			// Footer
			'footer_style'                   => '1',
			'copyright_area'                 => 1,
			'copyright_text'                 => gmdate( 'Y' ) . '© All right reserved by RadiusTheme',
			//Footer 1
            'f1_bg_img'     => '',
            'f1_bg_color'   => '',
            'f1_bg_opacity' => '',
            'f1_cr_bg_color' => '',
            'f1_widgets_area' => '5',
            'f1_area1_column' => '3',
            'f1_area2_column' => '2',
            'f1_area3_column' => '2',
            'f1_area4_column' => '2',
            'f1_area5_column' => '3',
            'f1_area6_column' => '2',

            //Footer 2
            'f2_bg_img'     => '',
            'f2_bg_color'   => '',
            'f2_bg_opacity' => '',
			'f2_cr_bg_color' => '',
            'f2_widgets_area' => '4',
            'f2_area1_column' => '3',
            'f2_area2_column' => '3',
            'f2_area3_column' => '2',
            'f2_area4_column' => '2',
            'f2_area5_column' => '4',
            'f2_area6_column' => '4',
			//Newsletter
			'f2_newsletter_title' 	  => '',
			'f2_newsletter_sub_title' => '',
			'f2_newsletter_shortcode' => '',
			
            //Footer 3
            'f3_bg_img'     => '',
            'f3_bg_color'   => '',
            'f3_bg_opacity' => '',
			'f3_cr_bg_color'  => '',
            'f3_widgets_area' => '4',
            'f3_area1_column' => '3',
            'f3_area2_column' => '2',
            'f3_area3_column' => '2',
            'f3_area4_column' => '3',
            'f3_area5_column' => '4',
            'f3_area6_column' => '4',
            //Footer 4
            'f4_cr_logo'    => '',
            'f4_bg_img'     => '',
            'f4_bg_color'   => '',
            'f4_bg_opacity' => '',
			'f4_cr_bg_color'  => '',
            'f4_widgets_area' => '2',
            'f4_area1_column' => '6',
            'f4_area2_column' => '5',
            'f4_area3_column' => '3',
            'f4_area4_column' => '4',
            'f4_area5_column' => '4',
            'f4_area6_column' => '4',

			// Listings Settings
			/* Archive */
			'listing_archive_style'       => '1',
			'listing_excerpt'         	  => '18',
			'listing_archive_title'       => '',
			'listing_archive_title_text'  => '',
			/* Single */
			'listing_details_article_tab' => 1,
			'listing_related'             => 1,

			// Listing Sidebar
			'single_sidebar_listing_info'  => 'listing_info',

			/* ====================================================
			= Blog Layout Settings
			======================================================= */
			'blog_layout'                    => 'right-sidebar',
			'blog_top_bar'                   => 'default',
			'blog_header_style'              => 'default',
			'blog_menu_alignment'            => 'default',
			'blog_header_width'              => 'default',
			'blog_tr_header'                 => 'default',
			// Banner 
			'blog_banner'                	 => 'on',
			'blog_banner_title'              => '',
			'blog_banner_bgimg'              => '',
			'blog_banner_bgcolor'            => '',
			'blog_banner_bgopacity'          => '',
			'blog_padding_top'               => '',
			'blog_padding_bottom'            => '',
			'blog_breadcrumb'                => 1,
			// Footer
			'blog_footer_style'              => 'default',

			/* ====================================================
			= Blog Single Layout Settings
			======================================================= */
			// Header
			'single_post_layout'             => 'right-sidebar',
			'single_post_top_bar'            => 'default',
			'single_post_header_style'       => 'default',
			'single_post_menu_alignment'     => 'default',
			'single_post_header_width'       => 'default',
			'single_post_tr_header'          => 'default',
			// Banner
			'single_post_banner'         	 => 'on',
			'single_post_banner_title'       => '',
			'single_post_banner_bgimg'       => '',
			'single_post_banner_bgcolor'   	 => '',
			'single_post_banner_bgopacity' 	 => '',
			'single_post_padding_top'        => '',
			'single_post_padding_bottom'     => '',
			'single_post_breadcrumb'         => 'default',
			// Footer
			'single_post_footer_style'       => 'default',

			/* ====================================================
			= Default Page Layout Settings
			======================================================= */
			// Header
			'page_layout'                    => 'full-width',
			'page_top_bar'                   => 'default',
			'page_header_style'              => 'default',
			'page_menu_alignment'            => 'default',
			'page_header_width'              => 'default',
			'page_tr_header'                 => 'default',
			// Banner
			'page_banner'                	 => 'on',
			'page_banner_title'              => '',
			'page_banner_bgimg'              => '',
			'page_banner_bgcolor'   		 => '',
			'page_banner_bgopacity' 		 => '',
			'page_padding_top'               => '',
			'page_padding_bottom'            => '',
			'page_breadcrumb'                => 'default',
			// Footer
			'page_footer_style'              => 'default',

			/* ====================================================
			= Error Page Layout Settings
			======================================================= */
			// Header
			'error_top_bar'                  => 'default',
			'error_header_style'             => 'default',
			'error_header_width'             => 'default',
			'error_menu_alignment'           => 'default',
			'error_tr_header'                => 'default',
			// Banner
			'error_banner'               	 => 'on',
			'error_banner_title'             => '',
			'error_banner_bgimg'             => '',
			'error_banner_bgcolor'           => '',
			'error_banner_bgopacity'         => '',
			'error_padding_top'              => '',
			'error_padding_bottom'           => '',
			'error_breadcrumb'               => 1,
			// Footer
			'error_footer_style'             => 'default',

			/* ====================================================
			= Listing Archive Layout
			======================================================= */
			// Header
			'listing_archive_top_bar'          => 'default',
			'listing_archive_header_style'     => 'default',
			'listing_archive_header_width'     => 'default',
			'listing_archive_menu_alignment'   => 'default',
			'listing_archive_tr_header'        => 'default',
			'listing_archive_layout'           => 'left-sidebar',
			'listing_archive_columns'          => '3',
			// Banner
			'listing_archive_banner'     	   => 'on',
			'listing_archive_banner_title'     => 1,
			'listing_archive_banner_bgimg'     => '',
			'listing_archive_banner_bgcolor'   => '',
			'listing_archive_banner_bgopacity' => '',
			'listing_archive_padding_top'      => '',
			'listing_archive_padding_bottom'   => '',
			'listing_archive_breadcrumb'       => 'default',
			// Footer
			'listing_archive_footer_style'     => 'default',
			
			/* ====================================================
			= Listing Single Layout
			======================================================= */
			// Header
			'listing_single_top_bar'         => 'default',
			'listing_single_header_style'    => 'default',
			'listing_single_header_width'    => 'default',
			'listing_single_menu_alignment'  => 'default',
			'listing_single_tr_header'       => 'default',
			// Banner
			'listing_single_banner'      	 => 'on',
			'listing_single_banner_title'    => '',
			'listing_single_banner_bgimg'    => '',
			'listing_single_banner_bgcolor'   => '',
			'listing_single_banner_bgopacity' => '',
			'listing_single_padding_top'     => '',
			'listing_single_padding_bottom'  => '',
			'listing_single_breadcrumb'      => 'default',
			// Footer
			'listing_single_footer_style'    => 'default',

			/* ====================================================
			= Contact Info
			======================================================= */
			'facebook'                       => '',
			'twitter'                        => '',
			'instagram'                      => '',
			'youtube'                        => '',
			'pinterest'                      => '',
			'linkedin'                       => '',
			'skype'                          => '',
			'tiktok'                         => '',

			/* ====================================================
			= Typography Settings
			======================================================= */
			/* = Body Typo Area
            ===========================*/
            'typo_body' => json_encode(
                array(
                    'font' => 'Roboto',
                    'regularweight' => 'normal',
                )
            ),
            'typo_body_size' => '16px',
            'typo_body_height'=> '1.8',

            /* = Menu Typo Area
            ===========================*/
            //Menu Typography
            'typo_menu' => json_encode(
                array(
                    'font' => 'Urbanist',
                    'regularweight' => '500',
                )
            ),
            'typo_menu_size' => '18px',
            'typo_menu_height'=> '1.6',

            //Sub Menu Typography
            'typo_submenu_size' => '16px',
            'typo_submenu_height'=> '1.6',

            /* = Heading Typo Area
            ===========================*/
            //Heading Typography
            'typo_heading' => json_encode(
                array(
                    'font' => 'Urbanist',
                    'regularweight' => '700',
                )
            ),

            //H1
            'typo_h1' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '700',
                )
            ),
            'typo_h1_size' => '42px',
            'typo_h1_height' => '1.4',

            //H2
            'typo_h2' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '700',
                )
            ),
            'typo_h2_size' => '36px',
            'typo_h2_height'=> '1.4',

            //H3
            'typo_h3' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '700',
                )
            ),
            'typo_h3_size' => '28px',
            'typo_h3_height'=> '1.3',

            //H4
            'typo_h4' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '700',
                )
            ),
            'typo_h4_size' => '22px',
            'typo_h4_height'=> '1.3',

            //H5
            'typo_h5' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '700',
                )
            ),
            'typo_h5_size' => '18px',
            'typo_h5_height'=> '1.3',

            //H6
            'typo_h6' => json_encode(
                array(
                    'font' => '',
                    'regularweight' => '700',
                )
            ),
            'typo_h6_size' => '14px',
            'typo_h6_height'=> '1.2',

			/* ====================================================
			= Site Color Settings
			======================================================= */
			'primary_color'   				 => '#3c19e8',
			'secondary_color' 				 => '#ece7fe',
			'body_color'      				 => '#7a7881',
			'heading_color'   				 => '#120745',
			'button_color_1'   				 => '#FF282C',
			'button_color_2'   				 => '#FF4E51',
		];

		return apply_filters( 'rttheme_customizer_defaults', $customizer_defaults );
	}
}