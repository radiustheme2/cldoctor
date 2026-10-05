<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @var WP_Query $rtcl_related_query
 * @var array $slider_options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RadiusTheme\Cldoctor\Options;
use Rtcl\Controllers\Hooks\TemplateHooks;
use RadiusTheme\Cldoctor\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;

if ( ! $rtcl_related_query->have_posts() ) {
	return;
}

global $listing;

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );

$listingSetting = $archive_settings['display_options'];

$show_doctor_designation = ! empty( $archive_settings['display_options'] ) && in_array( 'show_doctor_designation', $archive_settings['display_options'] );
$show_hospital_slogan = ! empty( $archive_settings['display_options'] ) && in_array( 'show_hospital_slogan', $archive_settings['display_options'] );
$general_listing_label = Functions::get_option( 'rtcl_general_listing_label_settings' );
$excerpt_length = Options::$options['listing_excerpt'];

?>
<div class="rtcl mb-3 rtcl-related-listing rtcl-listings">
    <div class="rtcl-related-title"><h2><?php esc_html_e( "Related Listing", "cldoctor" ); ?></h2></div>
    <div class="rtcl-related-listings">
        <div class="rtcl-related-slider-wrap">
            <div class="rtcl-related-slider rtcl-carousel-slider" id="rtcl-related-slider"
                 data-options="<?php
				 echo esc_attr( wp_json_encode( $slider_options ) ); ?>">
                <div class="swiper-wrapper">
					<?php
					global $post;
					while ( $rtcl_related_query->have_posts() ):
						$rtcl_related_query->the_post();
						$listing = rtcl()->factory->get_listing( get_the_ID() );

						$doctor_designation = get_post_meta( get_the_ID(), 'doctor_designation', true );
						$hospital_slogan = get_post_meta( get_the_ID(), 'hospital_slogan', true );

						$phone = get_post_meta( get_the_ID(), 'phone', true );
						$phone_url = str_replace(' ', '', $phone);
						?>
                        <div class="swiper-slide rtcl-related-slider-item listing-item rtcl-listing-item">
                            <div class="related-item-inner grid-item">
                                <div class="listing-thumb">
                                    <a href="<?php the_permalink(); ?>"><?php $listing->the_thumbnail( 'rtcl-thumbnail' ); ?></a>
                                </div>
                                <div class="item-content">
                                    <div class="all-meta-info-box">
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
			                                <?php
                                                if (in_array( 'excerpt', $listingSetting )) {
                                                    Listing_Functions::cldoctor_listing_excerpt($excerpt_length);
                                                }
			                                ?>
                                        </div>
	                                    <?php
	                                    if ( class_exists( 'RtclPro' )) {
		                                    ProTemplateHooks::loop_item_listable_fields();
	                                    }
	                                    ?>
		                                <?php TemplateHooks::loop_item_meta(); ?>
                                    </div>
                                    <div class="price-time-box">
		                                <?php TemplateHooks::listing_price(); ?>
                                    </div>
                                    <?php if (! empty( $phone)){ ?>
                                    <div class="appointment-box doctor-list">
                                        <a href="tel:<?php echo esc_attr( $phone_url ); ?>"><i class="icon-calender-2"></i> <?php esc_html_e( 'Call for Details', 'cldoctor' ); ?></a>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
					<?php endwhile;
					wp_reset_postdata();
					?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>
</div>