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

use RadiusTheme\Cldoctor\Customizer\Controls\Multiple_Checkbox;
use RadiusTheme\Cldoctor\Customizer\Controls\Image_Radio;
use RadiusTheme\Cldoctor\Customizer\Controls\Heading;
use RadiusTheme\Cldoctor\Customizer\Controls\Switcher;
use RadiusTheme\Cldoctor\Customizer\Customizer;
use RadiusTheme\Cldoctor\Helper;
use Rtcl\Helpers\Functions;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Listings extends Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {

		/* -- Listing -- */
		$this->__listing_settings_controls($wp_customize);
		/* -- Listing Archive -- */
		$this->__listing_archive_settings_controls($wp_customize);
		/* -- Listing Single -- */
		$this->__listing_single_settings_controls($wp_customize);
		/* -- Listings Search -- */
//		$this->__listings_search_settings_controls($wp_customize);
		
	}

	/* Listing Settiings
	==================================================================================================*/
	protected function __listing_settings_controls($wp_customize) {
		// Listing Box Style
		$wp_customize->add_setting( 'listing_archive_style',
			[
				'default'           => $this->defaults['listing_archive_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Image_Radio( $wp_customize, 'listing_archive_style',
			[
				'label'       => esc_html__( 'Listing Layout', 'cldoctor' ),
				'description' => esc_html__( 'This is listing archive box listing style', 'cldoctor' ),
				'section'     => 'listings_section',
				'choices'     => [
					'1' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/listing-1.jpg',
						'name'  => esc_html__( 'Layout 1', 'cldoctor' ),
					],
//					'2' => [
//						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/listing-2.jpg',
//						'name'  => esc_html__( 'Layout 2', 'cldoctor' ),
//					],
				],
			]
		) );
    }

	/* Listing Archive
	==================================================================================================*/
	protected function __listing_archive_settings_controls($wp_customize) {
		// Listing Archive Title
		$wp_customize->add_setting(
			'listing_archive_title',
			[
				'default'           => $this->defaults['listing_archive_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Switcher( $wp_customize, 'listing_archive_title',
				[
					'label'   => esc_html__( 'Listing Archive Title Visibility', 'cldoctor' ),
					'section' => 'listing_archive_section',
				]
			)
		);
        $wp_customize->add_setting( 'listing_archive_title_text',
            array(
                'default' => $this->defaults['listing_archive_title_text'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listing_archive_title_text',
            array(
                'label' => esc_html__( 'Listing Archive Title Text', 'cldoctor' ),
                'section' => 'listing_archive_section',
                'type' => 'text',
				'active_callback' => [ '\RadiusTheme\Cldoctor\Helper', 'is_listing_archive_title_enabled' ],
            )
        );

		// Listing content excerpt
        $wp_customize->add_setting( 'listing_excerpt',
            array(
                'default' => $this->defaults['listing_excerpt'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'listing_excerpt',
            array(
                'label' => esc_html__( 'Listing Content Excerpt', 'cldoctor' ),
                'section' => 'listing_archive_section',
                'type' => 'number',
            )
        );
	}

	/* Listing Single
	==================================================================================================*/
	protected function __listing_single_settings_controls($wp_customize) {

		// Listing owner information      
        $wp_customize->add_setting( 'single_sidebar_listing_info',
            array(
                'default' => $this->defaults['single_sidebar_listing_info'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_radio_sanitization'
            )
        );
        $wp_customize->add_control( 'single_sidebar_listing_info',
            array(
                'label' => esc_html__( 'Listing Author', 'cldoctor' ),
                'section' => 'listing_single_section',
                'description' => esc_html__( 'This is listing information like store info, store owner info, store manager info', 'cldoctor' ),
                'type' => 'select',
                'choices' => array(
                    'listing_owner_info' => esc_html__( 'Listing Owner Information', 'cldoctor' ),
                    'listing_info' => esc_html__( 'Listing Information', 'cldoctor' ),
                ),
            )
        );

		// Article Tab Enabled/Disabled
		$wp_customize->add_setting(
			'listing_details_article_tab',
			[
				'default'           => $this->defaults['listing_details_article_tab'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Switcher( $wp_customize, 'listing_details_article_tab',
				[
					'label'   => esc_html__( 'Listing Details Article Tab', 'cldoctor' ),
					'section' => 'listing_single_section',
				]
			)
		);

		// Related Listing
		$wp_customize->add_setting(
			'listing_related',
			[
				'default'           => $this->defaults['listing_related'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Switcher( $wp_customize, 'listing_related',
				[
					'label'   => esc_html__( 'Related Listing', 'cldoctor' ),
					'section' => 'listing_single_section',
				]
			)
		);
	}

	/* Listings Search
	==================================================================================================*/
	protected function __listings_search_settings_controls($wp_customize) {
		//Listing Banner Search Items
		$wp_customize->add_setting('listing_search_widget_search', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Heading($wp_customize, 'listing_search_widget_search', array(
            'label' => esc_html__( 'Search Widget', 'cldoctor' ),
            'section' => 'listings_search_section',
        )));

		// Price minimum range
        $wp_customize->add_setting( 'listing_widget_min_price',
            array(
                'default' => $this->defaults['listing_widget_min_price'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'listing_widget_min_price',
            array(
                'label' => esc_html__( 'Min Price', 'cldoctor' ),
                'section' => 'listings_search_section',
                'type' => 'number',
            )
        );
		// Price maximum range
        $wp_customize->add_setting( 'listing_widget_max_price',
            array(
                'default' => $this->defaults['listing_widget_max_price'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_sanitize_integer',
            )
        );
        $wp_customize->add_control( 'listing_widget_max_price',
            array(
                'label' => esc_html__( 'Max Price', 'cldoctor' ),
                'section' => 'listings_search_section',
                'type' => 'number',
            )
        );
	}
}