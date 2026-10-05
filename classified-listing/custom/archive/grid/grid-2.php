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
use Rtcl\Controllers\Hooks\TemplateHooks;
use RadiusTheme\Cldoctor\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;

global $listing;
$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
$show_hospital_slogan = ! empty( $archive_settings['display_options'] ) && in_array( 'show_hospital_slogan', $archive_settings['display_options'] );
$hospital_slogan = get_post_meta( $listing->get_id(), 'hospital_slogan', true );

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
    <div class="price-time-box">
        <?php TemplateHooks::listing_price(); ?>
        <?php if ( $listing->can_show_date() ) : ?>
            <div class="date-time">
                <?php Listing_Functions::cldoctor_the_time(get_the_ID()); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="all-meta-info-box">
        <?php TemplateHooks::loop_item_listing_title(); ?>
        <?php
            if ( class_exists( 'RtclPro' )) {
                ProTemplateHooks::loop_item_listable_fields();
            }
        ?>
        <?php TemplateHooks::loop_item_meta(); ?>
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