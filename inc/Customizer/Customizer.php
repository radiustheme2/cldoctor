<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

namespace RadiusTheme\Cldoctor\Customizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Customizer {

	// Get our default values
	protected $defaults;
	protected static $instance = null;

	public function __construct() {
		// Register Panels
		add_action( 'customize_register', [ $this, 'add_customizer_panels' ] );
		// Register sections
		add_action( 'customize_register', [ $this, 'add_customizer_sections' ] );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function populated_default_data() {
		$this->defaults = Default_Data::default_values();
	}

	/**
	 * Customizer Panels
	 */
	public function add_customizer_panels( $wp_customize ) {
		// Add General Panel
        $wp_customize->add_panel( 'rt_general_settings',
            [
                'title' => esc_html__( 'General', 'cldoctor' ),
                'description' => esc_html__( 'All general settings here.', 'cldoctor' ),
                'priority' => 1,
            ]
        );
		// Add Header Panel
        $wp_customize->add_panel( 'rt_header_settings',
            [
                'title' => esc_html__( 'Header', 'cldoctor' ),
                'description' => esc_html__( 'All header settings here.', 'cldoctor' ),
                'priority' => 2,
            ]
        );
		// Add Footer Panel
        $wp_customize->add_panel( 'rt_footer_settings',
            [
                'title' => esc_html__( 'Footer', 'cldoctor' ),
                'description' => esc_html__( 'All footer settings here.', 'cldoctor' ),
                'priority' => 3,
            ]
        );
		// Color Panel
		$wp_customize->add_panel( 'rt_color_panel',
			[
				'title'       => esc_html__( 'Color', 'cldoctor' ),
				'description' => esc_html__( 'Change site color', 'cldoctor' ),
				'priority'    => 5,
			]
		);
		// Add Blog Panel
        $wp_customize->add_panel( 'rt_blog_settings',
            [
                'title' => esc_html__( 'Blog', 'cldoctor' ),
                'description' => esc_html__( 'Blog archive & post single settings.', 'cldoctor' ),
                'priority' => 6,
            ]
        );
		// Layout Panel
		$wp_customize->add_panel( 'rt_layouts_defaults',
			[
				'title'       => esc_html__( 'Layout', 'cldoctor' ),
				'description' => esc_html__( 'Adjust the overall layout for your site.', 'cldoctor' ),
				'priority'    => 7,
			]
		);
		// Listing Panel
		$wp_customize->add_panel( 'rt_listing_settings',
			[
				'title'       => esc_html__( 'Listing', 'cldoctor' ),
				'description' => esc_html__( 'Adjust the overall layout for your site.', 'cldoctor' ),
				'priority'    => 8,
			]
		);
		
	}

	/**
	 * Customizer sections
	 */
	public function add_customizer_sections( $wp_customize ) {
		// Rename the default Colors section
		$wp_customize->get_section( 'colors' )->title = 'Background';
		// Move the default Colors section to our new Colors Panel
		$wp_customize->get_section( 'colors' )->panel = 'colors_panel';
		// Change the Priority of the default Colors section so it's at the top of our Panel
		$wp_customize->get_section( 'colors' )->priority = 10;

		/* = General Panel
		===================================================================*/
		$wp_customize->add_section( 'general_section',
			[
				'title'    => esc_html__( 'General', 'cldoctor' ),
				'priority' => 1,
				'panel'    => 'rt_general_settings',
			]
		);
		$wp_customize->add_section( 'contact_info_section',
			[
				'title'    => esc_html__( 'Contact & Social', 'cldoctor' ),
				'priority' => 2,
				'panel'    => 'rt_general_settings',
			]
		);
		
		/* = Header Panel
		===================================================================*/
		$wp_customize->add_section( 'header_main_section',
			[
				'title'    => esc_html__( 'Header Variation', 'cldoctor' ),
				'priority' => 1,
				'panel'    => 'rt_header_settings',
			]
		);
		$wp_customize->add_section( 'header_switches_section',
			[
				'title'    => esc_html__( 'Header Control Switches', 'cldoctor' ),
				'priority' => 2,
				'panel'    => 'rt_header_settings',
			]
		);

		/* = Footer Panel
		===================================================================*/
		$wp_customize->add_section( 'footer_all_section',
			[
				'title'    => esc_html__( 'All Footer', 'cldoctor' ),
				'panel'    => 'rt_footer_settings',
				'priority' => 1,
			]
		);
		$wp_customize->add_section( 'footer_1',
			[
				'title'    => esc_html__( 'Footer 1', 'cldoctor' ),
				'panel'    => 'rt_footer_settings',
				'priority' => 2,
			]
		);
		$wp_customize->add_section( 'footer_2',
			[
				'title'    => esc_html__( 'Footer 2', 'cldoctor' ),
				'panel'    => 'rt_footer_settings',
				'priority' => 3,
			]
		);
		$wp_customize->add_section( 'footer_3',
			[
				'title'    => esc_html__( 'Footer 3', 'cldoctor' ),
				'panel'    => 'rt_footer_settings',
				'priority' => 4,
			]
		);
		$wp_customize->add_section( 'footer_4',
			[
				'title'    => esc_html__( 'Footer 4', 'cldoctor' ),
				'panel'    => 'rt_footer_settings',
				'priority' => 5,
			]
		);

		/* = Blog Panel
		===================================================================*/
		$wp_customize->add_section( 'blog_archive_section',
			[
				'title'    => esc_html__( 'Blog', 'cldoctor' ),
				'priority' => 1,
				'panel'    => 'rt_blog_settings',
			]
		);
		$wp_customize->add_section( 'single_post_section',
			[
				'title'    => esc_html__( 'Post Details', 'cldoctor' ),
				'priority' => 2,
				'panel'    => 'rt_blog_settings',
			]
		);

		/* = Listings Panel
		===================================================================*/
		$wp_customize->add_section( 'listings_section',
			[
				'title'    => esc_html__( 'Listing Settings', 'cldoctor' ),
				'priority' => 1,
				'panel'    => 'rt_listing_settings',
			]
		);
		$wp_customize->add_section( 'listing_archive_section',
			[
				'title'    => esc_html__( 'Listing Archive', 'cldoctor' ),
				'priority' => 2,
				'panel'    => 'rt_listing_settings',
			]
		);
		$wp_customize->add_section( 'listing_single_section',
			[
				'title'    => esc_html__( 'Listing Single', 'cldoctor' ),
				'priority' => 3,
				'panel'    => 'rt_listing_settings',
			]
		);
		$wp_customize->add_section( 'listings_search_section',
			[
				'title'    => esc_html__( 'Listings Search', 'cldoctor' ),
				'priority' => 3,
				'panel'    => 'rt_listing_settings',
			]
		);

		/* = Color Panel settings
		===================================================================*/
		$wp_customize->add_section( 'site_color_section',
			[
				'title'    => esc_html__( 'Site Color', 'cldoctor' ),
				'panel'    => 'rt_color_panel',
				'priority' => 1,
			]
		);
		$wp_customize->add_section( 'button_color_section',
			[
				'title'    => esc_html__( 'Button Color', 'cldoctor' ),
				'panel'    => 'rt_color_panel',
				'priority' => 2,
			]
		);
		$wp_customize->add_section( 'footer_color_section',
			[
				'title'    => esc_html__( 'Footer Color', 'cldoctor' ),
				'panel'    => 'rt_color_panel',
				'priority' => 3,
			]
		);

		/* = Layout Panel settings
		===================================================================*/
		// Add Blog Layout Section
		$wp_customize->add_section( 'blog_layout_section',
			[
				'title'    => esc_html__( 'Blog Layout', 'cldoctor' ),
				'priority' => 1,
				'panel'    => 'rt_layouts_defaults',
			]
		);
		// Add Single Post Layout Section
		$wp_customize->add_section( 'single_post_layout_section',
			[
				'title'    => esc_html__( 'Single Post Layout', 'cldoctor' ),
				'priority' => 2,
				'panel'    => 'rt_layouts_defaults',
			]
		);
		// Add Pages Layout Section
		$wp_customize->add_section( 'page_layout_section',
			[
				'title'    => esc_html__( 'Pages Layout', 'cldoctor' ),
				'priority' => 2,
				'panel'    => 'rt_layouts_defaults',
			]
		);
		// Add Error Layout Section
		$wp_customize->add_section( 'error_layout_section',
			[
				'title'    => esc_html__( 'Error Layout', 'cldoctor' ),
				'priority' => 3,
				'panel'    => 'rt_layouts_defaults',
			]
		);
		// Add Listing Layout Section
		$wp_customize->add_section( 'listing_archive_layout_section',
			[
				'title'    => esc_html__( 'Listing Archive Layout', 'cldoctor' ),
				'priority' => 4,
				'panel'    => 'rt_layouts_defaults',
			]
		);
		// Add Listing Single Layout Section
		$wp_customize->add_section( 'listing_single_layout_section',
			[
				'title'    => esc_html__( 'Listing Single Layout', 'cldoctor' ),
				'priority' => 5,
				'panel'    => 'rt_layouts_defaults',
			]
		);
		
		/* = 404 Panel settings
		===================================================================*/
		// Add Error Page Section
		$wp_customize->add_section( 'error_section',
			[
				'title'    => esc_html__( 'Error Page', 'cldoctor' ),
				'priority' => 19,
			]
		);
	}

}
