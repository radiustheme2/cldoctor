<?php 
/**
 * Listing Archive Layout
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 use Rtcl\Helpers\Functions;
 use RadiusTheme\Cldoctor\Options;
 use Rtcl\Controllers\Hooks\TemplateHooks;
 use RadiusTheme\Cldoctor\Listing_Functions;
 use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;
use RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingFrontendHooks as RtrbFrontendHooks;

global $listing;
$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
//Doctor
$show_doctor_designation = ! empty( $archive_settings['display_options'] ) && in_array( 'show_doctor_designation', $archive_settings['display_options'] );
$doctor_designation = get_post_meta( $listing->get_id(), 'doctor_designation', true );
//Hospital
$show_hospital_slogan = ! empty( $archive_settings['display_options'] ) && in_array( 'show_hospital_slogan', $archive_settings['display_options'] );
$hospital_slogan = get_post_meta( $listing->get_id(), 'hospital_slogan', true );
//Others
$general_listing_label = Functions::get_option( 'rtcl_general_listing_label_settings' );
$phone_label = !empty( $general_listing_label['phone_button_label'] ) ? $general_listing_label['phone_button_label'] : 'Call For Details';
$button_label = esc_html__( 'Listing Details', 'cldoctor' );
$phone = get_post_meta( $listing->get_id(), 'phone', true );
$phone_url = str_replace(' ', '', $phone);

?>
<?php
/**
 * Hook: rtcl_before_listing_loop_item.
 *
 * @hooked rtcl_template_loop_product_link_open - 10
 */
do_action( 'rtcl_before_listing_loop_item' );

/**
 * Hook: rtcl_listing_loop_item.
 *
 * @hooked listing_thumbnail - 10
 */
do_action( 'rtcl_listing_loop_item_start' );

/**
 * Hook: rtcl_listing_loop_item.
 *
 * @hooked loop_item_wrap_start - 10
 * @hooked loop_item_listing_title - 20
 * @hooked loop_item_labels - 30
 * @hooked loop_item_listable_fields - 40 
 * @hooked loop_item_meta - 50
 * @hooked loop_item_excerpt - 60
 * @hooked loop_item_wrap_end - 100
 */

?>

<?php TemplateHooks::loop_item_wrapper_start(); ?>
    <?php Listing_Functions::cldoctor_listing_list_categories(); ?>
    <div class="title-designation">
        <?php TemplateHooks::loop_item_listing_title(); ?>
        <?php if (!empty( $doctor_designation && $show_doctor_designation )) { ?>
            <div class="doctor-designation">
                <?php echo esc_html( $doctor_designation ); ?>
            </div>
        <?php } ?>
        <?php if (!empty( $hospital_slogan && $show_hospital_slogan )) { ?>
            <div class="doctor-designation hospital-slogan">
                <?php echo esc_html( $hospital_slogan ); ?>
            </div>
        <?php } ?>
    </div>
    <?php if ( $listing->can_show_excerpt() ) { ?>
    <div class="listing-info-text">
        <?php Listing_Functions::cldoctor_listing_excerpt(Options::$options['listing_excerpt']); ?>
    </div>
    <?php } ?>
    <div class="all-meta-info-box">
        <div class="all-meta-info-box-left">
	        <?php
                if ( class_exists( 'RtclPro' )) {
                    ProTemplateHooks::loop_item_listable_fields();
                }
	        ?>
            <?php TemplateHooks::loop_item_meta(); ?>
        </div>
    </div>
    <div class="appointment-box">
        <a href="<?php the_permalink(); ?>"><?php echo esc_html( $button_label ); ?></a>
    </div>

<?php TemplateHooks::loop_item_wrapper_end(); ?>

<?php 
/**
 * Hook: rtcl_after_listing_loop_item.
 *
 * @hooked listing_loop_map_data - 50
 */
do_action( 'rtcl_after_listing_loop_item' );

?>