<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.1
 */

namespace RadiusTheme\Cldoctor\Customizer\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RadiusTheme\Cldoctor\Customizer\Controls\Image_Radio;
use RadiusTheme\Cldoctor\Customizer\Customizer;
use RadiusTheme\Cldoctor\Helper;
use WP_Customize_Media_Control;
use WP_Customize_Color_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Error_Layout extends Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_error_layout_controls' ] );
	}

	public function register_error_layout_controls( $wp_customize ) {
		// Header Layout
		$wp_customize->add_setting( 'error_header_style',
			[
				'default'           => $this->defaults['error_header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Image_Radio( $wp_customize, 'error_header_style',
			[
				'label'       => esc_html__( 'Layout', 'cldoctor' ),
				'description' => esc_html__( 'Select the default header layout', 'cldoctor' ),
				'section'     => 'error_layout_section',
				'choices' => Helper::get_header_list(),
			]
		) );

		// Menu Alignment
		$wp_customize->add_setting( 'error_menu_alignment', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['menu_alignment'],
		] );

		$wp_customize->add_control( 'error_menu_alignment', [
			'type'    => 'select',
			'section' => 'error_layout_section', // Add a default or your own section
			'label'   => esc_html__( 'Menu Alignment', 'cldoctor' ),
			'choices' => [
				'default'     => esc_html__( 'Default', 'cldoctor' ),
				'menu-left'   => esc_html__( 'Left Alignment', 'cldoctor' ),
				'menu-center' => esc_html__( 'Center Alignment', 'cldoctor' ),
				'menu-right'  => esc_html__( 'Right Alignment', 'cldoctor' ),
			],
		] );

		// Header width
		$wp_customize->add_setting( 'error_header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['error_header_width'],
		] );

		$wp_customize->add_control( 'error_header_width', [
			'type'    => 'select',
			'section' => 'error_layout_section', // Add a default or your own section
			'label'   => esc_html__( 'Header Width', 'cldoctor' ),
			'choices' => [
				'default'   => esc_html__( 'Default', 'cldoctor' ),
				'box-width' => esc_html__( 'Box width', 'cldoctor' ),
				'fullwidth' => esc_html__( 'Fullwidth', 'cldoctor' ),
			],
		] );

		// Banner
		$wp_customize->add_setting( 'error_banner',
			[
				'default'           => $this->defaults['error_banner'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_banner', [
			'type'    => 'select',
			'section' => 'error_layout_section',
			'label'   => esc_html__( 'Banner', 'cldoctor' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'cldoctor' ),
				'on'      => esc_html__( 'Enable', 'cldoctor' ),
				'off'     => esc_html__( 'Disable', 'cldoctor' ),
			],
		] );

		// Banner background image
        $wp_customize->add_setting( 'error_banner_bgimg',
            array(
                'default' => $this->defaults['error_banner_bgimg'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'error_banner_bgimg',
            array(
                'label' => __( 'Banner Background Image', 'cldoctor' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'cldoctor' ),
                'section' => 'error_layout_section',
                'mime_type' => 'image',
                'button_labels' => array(
                    'select' => __( 'Select File', 'cldoctor' ),
                    'change' => __( 'Change File', 'cldoctor' ),
                    'default' => __( 'Default', 'cldoctor' ),
                    'remove' => __( 'Remove', 'cldoctor' ),
                    'placeholder' => __( 'No file selected', 'cldoctor' ),
                    'frame_title' => __( 'Select File', 'cldoctor' ),
                    'frame_button' => __( 'Choose File', 'cldoctor' ),
                ),
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_error_banner_enable' ],
            )
        ) );

		// Banner background color
        $wp_customize->add_setting('error_banner_bgcolor', 
            array(
                'default' => $this->defaults['error_banner_bgcolor'],
                'type' => 'theme_mod', 
                'capability' => 'edit_theme_options', 
                'transport' => 'refresh', 
                'sanitize_callback' => 'sanitize_hex_color',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'error_banner_bgcolor',
            array(
                'label' => esc_html__('Banner Background Color', 'cldoctor'),
                'settings' => 'error_layout_section', 
                'priority' => 10, 
                'section' => 'error_layout_section',
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_error_banner_enable' ],
            )
        ));
        
        // Banner background color opacity
        $wp_customize->add_setting( 'error_banner_bgopacity',
            array(
                'default' => $this->defaults['error_banner_bgopacity'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'error_banner_bgopacity',
            array(
                'type' => 'number',
                'label' => esc_html__( 'Background Opacity', 'cldoctor' ),
                'section' => 'error_layout_section',
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_error_banner_enable' ],
            )
        );

		// Banner Text
		$wp_customize->add_setting( 'error_banner_title',
			[
				'default'           => $this->defaults['error_banner_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_kses_post',
			]
		);
		$wp_customize->add_control( 'error_banner_title',
			[
				'type'    => 'text',
				'label'   => esc_html__( 'Banner Title', 'cldoctor' ),
				'section' => 'error_layout_section',
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_error_banner_enable' ],
			]
		);

		// Padding Top
		$wp_customize->add_setting( 'error_padding_top',
			[
				'default'           => $this->defaults['error_padding_top'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_padding_top',
			[
				'type'        => 'text',
				'label'       => esc_html__( 'Content Padding Top', 'cldoctor' ),
				'description' => esc_html__( '404 Page Content Padding Top ', 'cldoctor' ),
				'section'     => 'error_layout_section',
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_error_banner_enable' ],
			]
		);

		// Padding Bottom
		$wp_customize->add_setting( 'error_padding_bottom',
			[
				'default'           => $this->defaults['error_padding_bottom'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_padding_bottom',
			[
				'type'        => 'text',
				'label'       => esc_html__( 'Content Padding Bottom', 'cldoctor' ),
				'description' => esc_html__( '404 Page Content Padding Bottom', 'cldoctor' ),
				'section'     => 'error_layout_section',
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_error_banner_enable' ],
			]
		);

		// Breadcrumb
		$wp_customize->add_setting( 'error_breadcrumb',
			[
				'default'           => $this->defaults['error_breadcrumb'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_breadcrumb', [
			'type'    => 'select',
			'section' => 'error_layout_section',
			'label'   => esc_html__( 'Breadcrumb', 'cldoctor' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'cldoctor' ),
				'on'      => esc_html__( 'Enable', 'cldoctor' ),
				'off'     => esc_html__( 'Disable', 'cldoctor' ),
			],
			'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_error_banner_enable' ],
		] );
		// Footer Layout
		$wp_customize->add_setting( 'error_footer_style',
			[
				'default'           => $this->defaults['error_footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Image_Radio( $wp_customize, 'error_footer_style',
			[
				'label'       => esc_html__( 'Layout', 'cldoctor' ),
				'description' => esc_html__( 'Select the default footer layout', 'cldoctor' ),
				'section'     => 'error_layout_section',
				'choices' => Helper::get_footer_list(),
			]
		) );
	}
}