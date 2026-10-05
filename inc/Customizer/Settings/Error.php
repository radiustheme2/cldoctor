<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

namespace RadiusTheme\Cldoctor\Customizer\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RadiusTheme\Cldoctor\Customizer\Customizer;
use WP_Customize_Media_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Error extends Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_error_controls' ] );
	}

	public function register_error_controls( $wp_customize ) {
		$wp_customize->add_setting( 'error_image',
			[
				'default'           => $this->defaults['error_image'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'error_image',
			[
				'label'         => esc_html__( 'Error Image', 'cldoctor' ),
				'section'       => 'error_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => esc_html__( 'Select File', 'cldoctor' ),
					'change'       => esc_html__( 'Change File', 'cldoctor' ),
					'default'      => esc_html__( 'Default', 'cldoctor' ),
					'remove'       => esc_html__( 'Remove', 'cldoctor' ),
					'placeholder'  => esc_html__( 'No file selected', 'cldoctor' ),
					'frame_title'  => esc_html__( 'Select File', 'cldoctor' ),
					'frame_button' => esc_html__( 'Choose File', 'cldoctor' ),
				],
			]
		) );

		// Error Text
		$wp_customize->add_setting( 'error_text',
			[
				'default'           => $this->defaults['error_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_kses_post',
			]
		);
		$wp_customize->add_control( 'error_text',
			[
				'label'   => esc_html__( 'Error Text', 'cldoctor' ),
				'section' => 'error_section',
				'type'    => 'text',
			]
		);

		// Error Sub-title
		$wp_customize->add_setting( 'error_subtitle',
			[
				'default'           => $this->defaults['error_subtitle'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_kses_post',
			]
		);
		$wp_customize->add_control( 'error_subtitle',
			[
				'label'   => esc_html__( 'Error Text', 'cldoctor' ),
				'section' => 'error_section',
				'type'    => 'text',
			]
		);

		// Button Text
		$wp_customize->add_setting( 'error_buttontext',
			[
				'default'           => $this->defaults['error_buttontext'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_buttontext',
			[
				'label'   => esc_html__( 'Button Text', 'cldoctor' ),
				'section' => 'error_section',
				'type'    => 'text',
			]
		);
	}
}