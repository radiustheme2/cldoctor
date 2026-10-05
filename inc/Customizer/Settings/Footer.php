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

use RadiusTheme\Cldoctor\Customizer\Controls\Image_Radio;
use RadiusTheme\Cldoctor\Customizer\Controls\Switcher;
use RadiusTheme\Cldoctor\Customizer\Controls\Heading;
use RadiusTheme\Cldoctor\Customizer\Customizer;
use RadiusTheme\Cldoctor\Helper;
use WP_Customize_Media_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Footer extends Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_footer_controls' ] );
	}

	public function register_footer_controls( $wp_customize ) {

		/* -- Footer General -- */
		$this->__footer_general_settings_controls($wp_customize);
        /* -- Footer 1 -- */
		$this->__footer_1_settings_controls($wp_customize);
        /* -- Footer 2 -- */
		$this->__footer_2_settings_controls($wp_customize);
        /* -- Footer 3 -- */
		$this->__footer_3_settings_controls($wp_customize);
        /* -- Footer 4 -- */
		$this->__footer_4_settings_controls($wp_customize);
	}
	
	/* Footer General Settiings
	=======================================================================*/
	protected function __footer_general_settings_controls($wp_customize) {
        // Footer Style
		$wp_customize->add_setting( 'footer_style',
			[
				'default'           => $this->defaults['footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Image_Radio( $wp_customize, 'footer_style',
			[
				'label'       => esc_html__( 'Footer Layouts', 'cldoctor' ),
				'description' => esc_html__( 'Select the footer style', 'cldoctor' ),
				'section'     => 'footer_all_section',
				'choices'     => Helper::get_footer_list(),
			]
		) );

		// Copyright Area Control
		$wp_customize->add_setting( 'copyright_area',
			[
				'default'           => $this->defaults['copyright_area'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Switcher( $wp_customize, 'copyright_area',
			[
				'label'   => esc_html__( 'Display Copyright Area', 'cldoctor' ),
				'section' => 'footer_all_section',
			]
		) );

		// Copyright Text
		$wp_customize->add_setting( 'copyright_text',
			[
				'default'           => $this->defaults['copyright_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_textarea_field',
			]
		);
		$wp_customize->add_control( 'copyright_text',
			[
				'label'           => esc_html__( 'Copyright Text', 'cldoctor' ),
				'section'         => 'footer_all_section',
				'type'            => 'textarea',
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_copyright_area_enabled' ],
			]
		);
    }

	/* = Footer 1 Settiings
    =======================================================================*/
	protected function __footer_1_settings_controls($wp_customize) {
        
        $wp_customize->add_setting('f1_widgets_area', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Heading($wp_customize, 'f1_widgets_area', array(
            'label' => esc_html__( 'Footer 1 Settings', 'cldoctor' ),
            'section' => 'footer_1',
        )));
        // Background image
        $wp_customize->add_setting( 'f1_bg_img',
            array(
                'default' => $this->defaults['f1_bg_img'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'f1_bg_img',
            array(
                'label' => __( 'Background Image', 'cldoctor' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'cldoctor' ),
                'section' => 'footer_1',
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
            )
        ) );
        // Background color
        $wp_customize->add_setting( 'f1_bg_color',
            array(
                'default' => $this->defaults['f1_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'cldoctor' ),
                'section' => 'footer_1',
                'type' => 'color',
            )
        );
        // Background color opacity
        $wp_customize->add_setting( 'f1_bg_opacity',
            array(
                'default' => $this->defaults['f1_bg_opacity'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'f1_bg_opacity',
            array(
                'label' => esc_html__( 'Background Opacity (1-100%)', 'cldoctor' ),
                'section' => 'footer_1',
                'type' => 'number',
            )
        );
        // Copyright Background color
        $wp_customize->add_setting( 'f1_cr_bg_color',
            array(
                'default' => $this->defaults['f1_cr_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_cr_bg_color',
            array(
                'label' => esc_html__( 'Copyright Background Color', 'cldoctor' ),
                'section' => 'footer_1',
                'type' => 'color',
            )
        );
        // Widgets Area
        $wp_customize->add_setting( 'f1_widgets_area',
            array(
                'default' => $this->defaults['f1_widgets_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_widgets_area',
            array(
                'label'   => esc_html__( 'Widget Area', 'cldoctor' ),
                'section' => 'footer_1',
                'type'    => 'select',
                'choices' => Helper::rt_number_options(),
            )
        );
        $wp_customize->add_setting( 'f1_area1_column',
            array(
                'default' => $this->defaults['f1_area1_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area1_column',
            array(
                'label' => esc_html__( 'Area 1 Columns', 'cldoctor' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f1_area2_column',
            array(
                'default' => $this->defaults['f1_area2_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area2_column',
            array(
                'label' => esc_html__( 'Area 2 Columns', 'cldoctor' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f1_area3_column',
            array(
                'default' => $this->defaults['f1_area3_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area3_column',
            array(
                'label' => esc_html__( 'Area 3 Columns', 'cldoctor' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f1_area4_column',
            array(
                'default' => $this->defaults['f1_area4_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area4_column',
            array(
                'label' => esc_html__( 'Area 4 Columns', 'cldoctor' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f1_area5_column',
            array(
                'default' => $this->defaults['f1_area5_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f1_area5_column',
            array(
                'label' => esc_html__( 'Area 5 Columns', 'cldoctor' ),
                'section' => 'footer_1',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
		$wp_customize->add_control( 'f1_area6_column',
			array(
				'label' => esc_html__( 'Area 6 Columns', 'cldoctor' ),
				'section' => 'footer_1',
				'type' => 'select',
				'choices' => Helper::rt_grid_options(),
				'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
			)
		);
    }

    /* = Footer 2 Settiings
    =======================================================================*/
    protected function __footer_2_settings_controls($wp_customize) {

        $wp_customize->add_setting('f2_widgets_area', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Heading($wp_customize, 'f2_widgets_area', array(
            'label' => esc_html__( 'Footer 2 Settings', 'cldoctor' ),
            'section' => 'footer_2',
        )));

        // Background image
        $wp_customize->add_setting( 'f2_bg_img',
            array(
                'default' => $this->defaults['f2_bg_img'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'f2_bg_img',
            array(
                'label' => __( 'Background Image', 'cldoctor' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'cldoctor' ),
                'section' => 'footer_2',
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
            )
        ) );
        
        // Background color
        $wp_customize->add_setting( 'f2_bg_color',
            array(
                'default' => $this->defaults['f2_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'cldoctor' ),
                'section' => 'footer_2',
                'type' => 'color',
            )
        );
        // Background color opacity
        $wp_customize->add_setting( 'f2_bg_opacity',
            array(
                'default' => $this->defaults['f2_bg_opacity'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'f2_bg_opacity',
            array(
                'label' => esc_html__( 'Background Opacity (1-100%)', 'cldoctor' ),
                'section' => 'footer_2',
                'type' => 'number',
            )
        );
        // Copyright Background color
        $wp_customize->add_setting( 'f2_cr_bg_color',
            array(
                'default' => $this->defaults['f2_cr_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_cr_bg_color',
            array(
                'label' => esc_html__( 'Copyright Background Color', 'cldoctor' ),
                'section' => 'footer_2',
                'type' => 'color',
            )
        );
        // Widget Area
        $wp_customize->add_setting( 'f2_widgets_area',
            array(
                'default' => $this->defaults['f2_widgets_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_widgets_area',
            array(
                'label'   => esc_html__( 'Widget Area', 'cldoctor' ),
                'section' => 'footer_2',
                'type'    => 'select',
                'choices' => Helper::rt_number_options(),
            )
        );
        $wp_customize->add_setting( 'f2_area1_column',
            array(
                'default' => $this->defaults['f2_area1_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_area1_column',
            array(
                'label' => esc_html__( 'Area 1 Columns', 'cldoctor' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f2_area2_column',
            array(
                'default' => $this->defaults['f2_area2_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_area2_column',
            array(
                'label' => esc_html__( 'Area 2 Columns', 'cldoctor' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f2_area3_column',
            array(
                'default' => $this->defaults['f2_area3_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_area3_column',
            array(
                'label' => esc_html__( 'Area 3 Columns', 'cldoctor' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f2_area4_column',
            array(
                'default' => $this->defaults['f2_area4_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f2_area4_column',
            array(
                'label' => esc_html__( 'Area 4 Columns', 'cldoctor' ),
                'section' => 'footer_2',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
	    $wp_customize->add_setting( 'f2_area5_column',
		    array(
			    'default' => $this->defaults['f2_area5_column'],
			    'transport' => 'refresh',
			    'sanitize_callback' => 'rttheme_radio_sanitization',
		    )
	    );
	    $wp_customize->add_control( 'f2_area5_column',
		    array(
			    'label' => esc_html__( 'Area 5 Columns', 'cldoctor' ),
			    'section' => 'footer_2',
			    'type' => 'select',
			    'choices' => Helper::rt_grid_options(),
			    'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
		    )
	    );
	    $wp_customize->add_setting( 'f2_area6_column',
		    array(
			    'default' => $this->defaults['f2_area6_column'],
			    'transport' => 'refresh',
			    'sanitize_callback' => 'rttheme_radio_sanitization',
		    )
	    );
	    $wp_customize->add_control( 'f2_area6_column',
		    array(
			    'label' => esc_html__( 'Area 6 Columns', 'cldoctor' ),
			    'section' => 'footer_2',
			    'type' => 'select',
			    'choices' => Helper::rt_grid_options(),
			    'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
		    )
	    );
        /**
         * Newsletter
         */
        $wp_customize->add_setting('footer_newsletter', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Heading($wp_customize, 'footer_newsletter', array(
            'label' => esc_html__( 'Newsletter', 'cldoctor' ),
            'section' => 'footer_2',
        )));
        // Title
        $wp_customize->add_setting( 'f2_newsletter_title',
            [
                'default'           => $this->defaults['f2_newsletter_title'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            ]
        );
        $wp_customize->add_control( 'f2_newsletter_title',
            [
                'label'           => esc_html__( 'Title', 'cldoctor' ),
                'section'         => 'footer_2',
                'type'            => 'text',
                'description'     => esc_html__( 'Footer newsletter title', 'cldoctor' ),
            ]
        );
        // Sub  Title
        $wp_customize->add_setting( 'f2_newsletter_sub_title',
            [
                'default'           => $this->defaults['f2_newsletter_sub_title'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            ]
        );
        $wp_customize->add_control( 'f2_newsletter_sub_title',
            [
                'label'           => esc_html__( 'Title', 'cldoctor' ),
                'section'         => 'footer_2',
                'type'            => 'text',
                'description'     => esc_html__( 'Footer newsletter sub title', 'cldoctor' ),
            ]
        );
        // Title
        $wp_customize->add_setting( 'f2_newsletter_shortcode',
            [
                'default'           => $this->defaults['f2_newsletter_shortcode'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            ]
        );
        $wp_customize->add_control( 'f2_newsletter_shortcode',
            [
                'label'           => esc_html__( 'Shortcode', 'cldoctor' ),
                'section'         => 'footer_2',
                'type'            => 'text',
                'description'     => esc_html__( 'Footer newsletter Shortcode', 'cldoctor' ),
            ]
        );
    }

    /* = Footer 3 Settiings
    =======================================================================*/
    protected function __footer_3_settings_controls($wp_customize) {

        $wp_customize->add_setting('f3_widgets_area', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Heading($wp_customize, 'f3_widgets_area', array(
            'label' => esc_html__( 'Footer 3 Settings', 'cldoctor' ),
            'section' => 'footer_3',
        )));
        // Background image
        $wp_customize->add_setting( 'f3_bg_img',
            array(
                'default' => $this->defaults['f3_bg_img'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'f3_bg_img',
            array(
                'label' => __( 'Background Image', 'cldoctor' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'cldoctor' ),
                'section' => 'footer_3',
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
            )
        ) );
        // Background color
        $wp_customize->add_setting( 'f3_bg_color',
            array(
                'default' => $this->defaults['f3_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'f3_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'cldoctor' ),
                'section' => 'footer_3',
                'type' => 'color',
            )
        );
        // Background color opacity
        $wp_customize->add_setting( 'f3_bg_opacity',
            array(
                'default' => $this->defaults['f3_bg_opacity'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'f3_bg_opacity',
            array(
                'label' => esc_html__( 'Background Opacity (1-100%)', 'cldoctor' ),
                'section' => 'footer_3',
                'type' => 'number',
            )
        );
        // Copyright Background color
        $wp_customize->add_setting( 'f3_cr_bg_color',
            array(
                'default' => $this->defaults['f3_cr_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'f3_cr_bg_color',
            array(
                'label' => esc_html__( 'Copyright Background Color', 'cldoctor' ),
                'section' => 'footer_3',
                'type' => 'color',
            )
        );
        // Widget Area
        $wp_customize->add_setting( 'f3_widgets_area',
            array(
                'default' => $this->defaults['f3_widgets_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f3_widgets_area',
            array(
                'label'   => esc_html__( 'Widget Area', 'cldoctor' ),
                'section' => 'footer_3',
                'type'    => 'select',
                'choices' => Helper::rt_number_options(),
            )
        );
        $wp_customize->add_setting( 'f3_area1_column',
            array(
                'default' => $this->defaults['f3_area1_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f3_area1_column',
            array(
                'label' => esc_html__( 'Area 1 Columns', 'cldoctor' ),
                'section' => 'footer_3',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f3_area2_column',
            array(
                'default' => $this->defaults['f3_area2_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f3_area2_column',
            array(
                'label' => esc_html__( 'Area 2 Columns', 'cldoctor' ),
                'section' => 'footer_3',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f3_area3_column',
            array(
                'default' => $this->defaults['f3_area3_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f3_area3_column',
            array(
                'label' => esc_html__( 'Area 3 Columns', 'cldoctor' ),
                'section' => 'footer_3',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f3_area4_column',
            array(
                'default' => $this->defaults['f3_area4_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f3_area4_column',
            array(
                'label' => esc_html__( 'Area 4 Columns', 'cldoctor' ),
                'section' => 'footer_3',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
	    $wp_customize->add_setting( 'f3_area5_column',
		    array(
			    'default' => $this->defaults['f3_area5_column'],
			    'transport' => 'refresh',
			    'sanitize_callback' => 'rttheme_radio_sanitization',
		    )
	    );
	    $wp_customize->add_control( 'f3_area5_column',
		    array(
			    'label' => esc_html__( 'Area 5 Columns', 'cldoctor' ),
			    'section' => 'footer_3',
			    'type' => 'select',
			    'choices' => Helper::rt_grid_options(),
			    'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
		    )
	    );
	    $wp_customize->add_setting( 'f3_area6_column',
		    array(
			    'default' => $this->defaults['f3_area6_column'],
			    'transport' => 'refresh',
			    'sanitize_callback' => 'rttheme_radio_sanitization',
		    )
	    );
	    $wp_customize->add_control( 'f3_area6_column',
		    array(
			    'label' => esc_html__( 'Area 6 Columns', 'cldoctor' ),
			    'section' => 'footer_3',
			    'type' => 'select',
			    'choices' => Helper::rt_grid_options(),
			    'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
		    )
	    );
    }

    /* = Footer 4 Settiings
    =======================================================================*/
    protected function __footer_4_settings_controls($wp_customize) {

        $wp_customize->add_setting('f4_widgets_area', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Heading($wp_customize, 'f4_widgets_area', array(
            'label' => esc_html__( 'Footer 4 Settings', 'cldoctor' ),
            'section' => 'footer_4',
        )));        
        // Background image
        $wp_customize->add_setting( 'f4_bg_img',
            array(
                'default' => $this->defaults['f4_bg_img'],
                'transport' => 'refresh',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'f4_bg_img',
            array(
                'label' => __( 'Background Image', 'cldoctor' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'cldoctor' ),
                'section' => 'footer_4',
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
            )
        ) );
        // Background color
        $wp_customize->add_setting( 'f4_bg_color',
            array(
                'default' => $this->defaults['f4_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'f4_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'cldoctor' ),
                'section' => 'footer_4',
                'type' => 'color',
            )
        );
        // Background color opacity
        $wp_customize->add_setting( 'f4_bg_opacity',
            array(
                'default' => $this->defaults['f4_bg_opacity'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'f4_bg_opacity',
            array(
                'label' => esc_html__( 'Background Opacity (1-100%)', 'cldoctor' ),
                'section' => 'footer_4',
                'type' => 'number',
            )
        );
        // Copyright Background color
        $wp_customize->add_setting( 'f4_cr_bg_color',
            array(
                'default' => $this->defaults['f4_cr_bg_color'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'f4_cr_bg_color',
            array(
                'label' => esc_html__( 'Copyright Background Color', 'cldoctor' ),
                'section' => 'footer_4',
                'type' => 'color',
            )
        );
        // Widget Area
        $wp_customize->add_setting( 'f4_widgets_area',
            array(
                'default' => $this->defaults['f4_widgets_area'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f4_widgets_area',
            array(
                'label'   => esc_html__( 'Widget Area', 'cldoctor' ),
                'section' => 'footer_4',
                'type'    => 'select',
                'choices' => Helper::rt_number_options(),
            )
        );
        $wp_customize->add_setting( 'f4_area1_column',
            array(
                'default' => $this->defaults['f4_area1_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f4_area1_column',
            array(
                'label' => esc_html__( 'Area 1 Columns', 'cldoctor' ),
                'section' => 'footer_4',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f4_area2_column',
            array(
                'default' => $this->defaults['f4_area2_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f4_area2_column',
            array(
                'label' => esc_html__( 'Area 2 Columns', 'cldoctor' ),
                'section' => 'footer_4',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f4_area3_column',
            array(
                'default' => $this->defaults['f4_area3_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f4_area3_column',
            array(
                'label' => esc_html__( 'Area 3 Columns', 'cldoctor' ),
                'section' => 'footer_4',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
        $wp_customize->add_setting( 'f4_area4_column',
            array(
                'default' => $this->defaults['f4_area4_column'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization',
            )
        );
        $wp_customize->add_control( 'f4_area4_column',
            array(
                'label' => esc_html__( 'Area 4 Columns', 'cldoctor' ),
                'section' => 'footer_4',
                'type' => 'select',
                'choices' => Helper::rt_grid_options(),
                'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
            )
        );
	    $wp_customize->add_setting( 'f4_area5_column',
		    array(
			    'default' => $this->defaults['f4_area5_column'],
			    'transport' => 'refresh',
			    'sanitize_callback' => 'rttheme_radio_sanitization',
		    )
	    );
	    $wp_customize->add_control( 'f4_area5_column',
		    array(
			    'label' => esc_html__( 'Area 5 Columns', 'cldoctor' ),
			    'section' => 'footer_4',
			    'type' => 'select',
			    'choices' => Helper::rt_grid_options(),
			    'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
		    )
	    );
	    $wp_customize->add_setting( 'f4_area6_column',
		    array(
			    'default' => $this->defaults['f4_area6_column'],
			    'transport' => 'refresh',
			    'sanitize_callback' => 'rttheme_radio_sanitization',
		    )
	    );
	    $wp_customize->add_control( 'f4_area6_column',
		    array(
			    'label' => esc_html__( 'Area 6 Columns', 'cldoctor' ),
			    'section' => 'footer_4',
			    'type' => 'select',
			    'choices' => Helper::rt_grid_options(),
			    'description' => esc_html__( 'Total Columns 12', 'cldoctor' ),
		    )
	    );
    }

}