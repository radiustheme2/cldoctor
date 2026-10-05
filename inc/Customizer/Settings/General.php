<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RadiusTheme\Cldoctor\Customizer\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RadiusTheme\Cldoctor\Customizer\Controls\Separator;
use RadiusTheme\Cldoctor\Customizer\Controls\Switcher;
use RadiusTheme\Cldoctor\Customizer\Controls\Heading;
use RadiusTheme\Cldoctor\Customizer\Customizer;
use WP_Customize_Media_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class General extends Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_general_controls' ] );
	}

	public function register_general_controls( $wp_customize ) {
		// Main Logo
		$wp_customize->add_setting( 'logo',
			[
				'default'           => $this->defaults['logo'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'logo',
			[
				'label'         => esc_html__( 'Main Logo', 'cldoctor' ),
				'description'   => esc_html__( 'Add site main logo', 'cldoctor' ),
				'section'       => 'general_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => esc_html__( 'Select Logo', 'cldoctor' ),
					'change'       => esc_html__( 'Change Logo', 'cldoctor' ),
					'default'      => esc_html__( 'Default', 'cldoctor' ),
					'remove'       => esc_html__( 'Remove', 'cldoctor' ),
					'placeholder'  => esc_html__( 'No file selected', 'cldoctor' ),
					'frame_title'  => esc_html__( 'Select File', 'cldoctor' ),
					'frame_button' => esc_html__( 'Choose File', 'cldoctor' ),
				],
			]
		) );

		$wp_customize->selective_refresh->add_partial( 'logo', [
			'selector'        => '.site-logo',
			'render_callback' => '__return_false',
		] );

		// White logo
		$wp_customize->add_setting( 'logo_dark',
			[
				'default'           => $this->defaults['logo_dark'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'logo_dark',
			[
				'label'         => esc_html__( 'Dark Logo', 'cldoctor' ),
				'description'   => esc_html__( 'Add logo for white background', 'cldoctor' ),
				'section'       => 'general_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => esc_html__( 'Select Logo', 'cldoctor' ),
					'change'       => esc_html__( 'Change Logo', 'cldoctor' ),
					'default'      => esc_html__( 'Default', 'cldoctor' ),
					'remove'       => esc_html__( 'Remove', 'cldoctor' ),
					'placeholder'  => esc_html__( 'No file selected', 'cldoctor' ),
					'frame_title'  => esc_html__( 'Select File', 'cldoctor' ),
					'frame_button' => esc_html__( 'Choose File', 'cldoctor' ),
				],
			]
		) );
		// Logo Width
		$wp_customize->add_setting( 'logo_width',
			[
				'default'           => $this->defaults['logo_width'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'logo_width',
			[
				'label'           => esc_html__( 'Logo max width', 'cldoctor' ),
				'section'         => 'general_section',
				'type'            => 'text',
				'description'     => esc_html__( 'Enter logo width. Eg: 200px', 'cldoctor' ),
				'input_attrs'     => [
					'placeholder' => esc_html__( '200px', 'cldoctor' ),
				],
			]
		);

		// Mobile Logo
		$wp_customize->add_setting('mobile_logo_area', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Heading($wp_customize, 'mobile_logo_area', array(
            'label' => esc_html__( 'Mobile Logo', 'cldoctor' ),
            'section' => 'general_section',
        )));
		$wp_customize->add_setting( 'mobile_logo',
			[
				'default'           => $this->defaults['mobile_logo'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'mobile_logo',
			[
				'label'         => esc_html__( 'Mobile Logo', 'cldoctor' ),
				'description'   => esc_html__( 'Add logo for mobile header', 'cldoctor' ),
				'section'       => 'general_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => esc_html__( 'Select Logo', 'cldoctor' ),
					'change'       => esc_html__( 'Change Logo', 'cldoctor' ),
					'default'      => esc_html__( 'Default', 'cldoctor' ),
					'remove'       => esc_html__( 'Remove', 'cldoctor' ),
					'placeholder'  => esc_html__( 'No file selected', 'cldoctor' ),
					'frame_title'  => esc_html__( 'Select File', 'cldoctor' ),
					'frame_button' => esc_html__( 'Choose File', 'cldoctor' ),
				],
			]
		) );
		// Logo Width
		$wp_customize->add_setting( 'mobile_logo_width',
			[
				'default'           => $this->defaults['mobile_logo_width'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'mobile_logo_width',
			[
				'label'           => esc_html__( 'Logo max width', 'cldoctor' ),
				'section'         => 'general_section',
				'type'            => 'text',
				'description'     => esc_html__( 'Enter logo width. Eg: 200px', 'cldoctor' ),
				'input_attrs'     => [
					'placeholder' => esc_html__( '200px', 'cldoctor' ),
				],
			]
		);

		// Separator
		$wp_customize->add_setting( 'separator_general1', [
			'default'           => '',
			'sanitize_callback' => 'esc_html',
		] );
		$wp_customize->add_control( new Separator( $wp_customize, 'separator_general1', [
			'settings' => 'separator_general1',
			'section'  => 'general_section',
		] ) );

		// Banner Image
		$wp_customize->add_setting( 'banner_image',
			[
				'default'           => $this->defaults['banner_image'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'banner_image',
			[
				'label'         => esc_html__( 'Banner Image', 'cldoctor' ),
				'description'   => esc_html__( 'Add banner image to change default image', 'cldoctor' ),
				'section'       => 'general_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => esc_html__( 'Select Image', 'cldoctor' ),
					'change'       => esc_html__( 'Change Image', 'cldoctor' ),
					'default'      => esc_html__( 'Default', 'cldoctor' ),
					'remove'       => esc_html__( 'Remove', 'cldoctor' ),
					'placeholder'  => esc_html__( 'No file selected', 'cldoctor' ),
					'frame_title'  => esc_html__( 'Select File', 'cldoctor' ),
					'frame_button' => esc_html__( 'Choose File', 'cldoctor' ),
				],
			]
		) );

		/**
         * Heading
         */
        $wp_customize->add_setting('site_switching', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Heading($wp_customize, 'site_switching', array(
            'label' => esc_html__( 'Site Switch Control', 'cldoctor' ),
            'section' => 'general_section',
        )));

		// Preloader
		$wp_customize->add_setting( 'preloader',
			[
				'default'           => $this->defaults['preloader'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Switcher( $wp_customize, 'preloader',
			[
				'label'   => esc_html__( 'Preloader', 'cldoctor' ),
				'section' => 'general_section',
			]
		) );

		// Preloader gif image
		$wp_customize->add_setting( 'preloader_gif',
			[
				'default'           => $this->defaults['preloader_gif'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'preloader_gif',
			[
				'label'         => esc_html__( 'Loading Image', 'cldoctor' ),
				'description'   => esc_html__( 'Page loading gif/animated image', 'cldoctor' ),
				'section'       => 'general_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => esc_html__( 'Select Image', 'cldoctor' ),
					'change'       => esc_html__( 'Change Image', 'cldoctor' ),
					'default'      => esc_html__( 'Default', 'cldoctor' ),
					'remove'       => esc_html__( 'Remove', 'cldoctor' ),
					'placeholder'  => esc_html__( 'No file selected', 'cldoctor' ),
					'frame_title'  => esc_html__( 'Select File', 'cldoctor' ),
					'frame_button' => esc_html__( 'Choose File', 'cldoctor' ),
				],
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_preloader_enabled' ],
			]
		) );


		// Back to top
		$wp_customize->add_setting( 'back_to_top',
			[
				'default'           => $this->defaults['back_to_top'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Switcher( $wp_customize, 'back_to_top',
			[
				'label'   => esc_html__( 'Back to Top', 'cldoctor' ),
				'section' => 'general_section',
			]
		) );


		// Hide admin bar
		$wp_customize->add_setting( 'remove_admin_bar',
			[
				'default'           => $this->defaults['remove_admin_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Switcher( $wp_customize, 'remove_admin_bar',
			[
				'label'       => esc_html__( 'Remove Admin Bar', 'cldoctor' ),
				'section'     => 'general_section',
				'description' => esc_html__( 'This option not work for administrator users.', 'cldoctor' ),
			]
		) );

	}

}
