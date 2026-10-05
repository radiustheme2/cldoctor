<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use RtclMarketplace\Hooks\ActionHooks;
use Rtrs\Modules\Review\Helpers\ReviewFns;
use RadiusTheme\Cldoctor\Listing_Functions;
use RtclBooking\Helpers\Functions as BookingFunctions;
use RtclClaimListing\Helpers\Functions as ClaimFunctions;

defined('ABSPATH') || exit;

global $listing;
global $current_user;

$detailOption         = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );
$add_type             = $listing->get_ad_type();
$average_rating       = $listing->get_average_rating();
$rating_count         = $listing->get_rating_count();
$social_page          = Functions::get_option_item( 'rtcl_general_social_share_settings', 'social_pages', [] );
$can_report_abuse     = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_report_abuse', '', 'checkbox' ) ? true : false;

$phone                = get_post_meta( $listing->get_id(), 'phone', true );
$phone_url            = str_replace( ' ', '', $phone );
$doctor_meet_time     = get_post_meta( $listing->get_id(), 'doctor_meet_time', true );
$doctor_designation   = get_post_meta( $listing->get_id(), 'doctor_designation', true );
$doctor_doctor_practice_area   = get_post_meta( $listing->get_id(), 'doctor_practice_area', true );
$hospital_slogan      = get_post_meta( $listing->get_id(), 'hospital_slogan', true );
$short_description    = get_post_meta( $listing->get_id(), 'short_description', true );
$langualges           = get_post_meta( $listing->get_id(), 'langualges_checkbox', false );
$generalSettings      = Functions::get_option( 'rtcl_general_listing_label_settings' );
$phone_label          = ! empty( $generalSettings['phone_button_label'] ) ? $generalSettings['phone_button_label'] : 'Call Clinic';
$booking_button_label = ! empty( $generalSettings['booking_button_label'] ) ? $generalSettings['booking_button_label'] : 'Make An Appointment';

// Build stats
$stats = [];
if ( $rating_count > 0 ) {
	$stats[] = [
		'label' => __( 'Rating', 'cldoctor' ),
		'value' => number_format( (float) $average_rating, 1 ),
	];
}
$lang_count = is_array( $langualges ) ? count( array_filter( $langualges ) ) : 0;
if ( $lang_count > 0 ) {
	$stats[] = [
		'label' => __( 'Languages', 'cldoctor' ),
		'value' => $lang_count,
	];
}

// Rating
if( class_exists( ReviewFns::class ) ){
	$average_rating = ReviewFns::getAvgRatings( get_the_ID() );
	$rating_count   = ReviewFns::getTotalRatings( get_the_ID() );
} else {
	$average_rating = $listing->get_average_rating();
	$rating_count   = $listing->get_rating_count();
}

// Designation line parts
$designation_parts = [];
if ( ! empty( $hospital_slogan ) )    $designation_parts[] = esc_html( $hospital_slogan );
if ( ! empty( $doctor_designation ) ) $designation_parts[] = esc_html( $doctor_designation );

?>
<div class="hero-profile">
    <div class="hero-photo">
        <?php
            $image_size    = 'cldoctor-size2';
            $the_thumbnail = $listing->get_the_thumbnail( $image_size );
            if ( $the_thumbnail ) {
                echo wp_kses_post( $the_thumbnail );
            }

            if ( $listing->has_category() && $listing->can_show_category() ):
                Listing_Functions::cldoctor_listing_categories();
            endif;
        ?>
    </div>
    <div class="hero-body">
        <?php if ( 0 < $rating_count ){ ?>
        <div class="hero-meta">
          <span class="hero-rating">
              <?php echo wp_kses_post( Functions::get_rating_html( $average_rating, $rating_count ) ); ?>
              <?php echo wp_kses_post( $average_rating ); ?>
              (<?php echo wp_kses_post( $rating_count ); esc_html_e( ' reviews', 'cldoctor'); ?>)
          </span>
        </div>
        <?php } ?>
        <h1 class="hero-name"><?php the_title(); ?></h1>
        <?php if (!empty( $doctor_designation || $doctor_doctor_practice_area )) { ?>
        <div class="hero-credentials">
	        <?php echo esc_html( $doctor_designation ); ?>
	        <?php echo esc_html( $doctor_doctor_practice_area ); ?>
        </div>
        <?php } ?>
	    <?php if (!empty( $hospital_slogan )) { ?>
            <div class="hero-credentials">
			    <?php echo esc_html( $hospital_slogan ); ?>
            </div>
	    <?php } ?>
	    <?php if (!empty( $short_description )) { ?>
        <p class="hero-bio">
	        <?php echo esc_html( $short_description ); ?>
        </p>
	    <?php } ?>
        <div class="listing-details-head-bottom">
            <div class="rtcl-single-actions">
                <ul class="meta-tags">
				    <?php if ( in_array('listing', $social_page) ) { ?>
                        <li class="social-share-li">
                            <button class="listing-social-action" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                <i class="icon-share"></i>
                            </button>
                        </li>
				    <?php } if (Functions::is_enable_favourite()){ ?>
                        <li class="meta-favourite">
						    <?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
                        </li>
				    <?php } if ( Fns::is_enable_compare() ) { ?>
                        <li class="meta-compare">
						    <?php
						    $compare_ids = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						    $selected_class = '';
						    if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
							    $selected_class = ' selected';
						    }
						    ?>
                            <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                                <i class="icon-compare"></i>
                            </a>
                        </li>
				    <?php } if ( $can_report_abuse ){ ?>
                        <li class="report-abuse-li">
						    <?php if ( is_user_logged_in() ): ?>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#rtcl-report-abuse-modal">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                </a>
						    <?php else: ?>
                                <a href="javascript:void(0)" class="rtcl-require-login">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                </a>
						    <?php endif; ?>
                        </li>
				    <?php } ?>

				    <?php if ( function_exists( 'rtclClaimListing' ) && ClaimFunctions::claim_listing_enable() ){ ?>
                        <li class='claim-listing-li' data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Claim", "cldoctor" ) ?>">
						    <?php if ( is_user_logged_in() ): ?>
                                <span data-bs-toggle="tooltip" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#rtcl-claim-listing-modal">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </a>
                                        </span>
						    <?php else: ?>
                                <a href="javascript:void(0)" data-bs-toggle="tooltip" class="rtcl-require-login" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                                    <i class="fas fa-exclamation-circle"></i>
                                </a>
						    <?php endif; ?>
                        </li>
				    <?php } ?>
                </ul>

                <ul class="meta-actions">
		            <?php if ( !empty( $phone ) ) { ?>
                        <li class="appointment-box">
                            <a href="tel:<?php echo esc_attr( $phone_url ); ?>"><i class="icon-phone-2"></i> <?php echo esc_html( $phone_label ); ?></a>
                        </li>
		            <?php } ?>

		            <?php if ( class_exists('RtclBooking') && ( BookingFunctions::is_active_booking( $listing->get_id() ) && BookingFunctions::is_enable_booking() ) ){ ?>
                        <li class="appointment-box">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#doctorBookingModal_<?php echo esc_attr( $listing->get_id() ); ?>"><i class="icon-calender-2"></i> <?php echo esc_html( $booking_button_label ); ?></a>
                        </li>
		            <?php } ?>

		            <?php if ( class_exists('RtclMarketplace') ) { ?>
                        <li class="marketplace-box">
				            <?php ActionHooks::add_buy_button(); ?>
                        </li>
		            <?php } ?>

                </ul>
            </div>
        </div>
    </div>
</div>
