<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Options;
use RtclMarketplace\Hooks\ActionHooks;
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
$hospital_slogan      = get_post_meta( $listing->get_id(), 'hospital_slogan', true );
$short_description    = get_post_meta( $listing->get_id(), 'short_description', true );
$langualges           = get_post_meta( $listing->get_id(), 'langualges_checkbox', false );
$generalSettings      = Functions::get_option( 'rtcl_general_listing_label_settings' );
$phone_label          = ! empty( $generalSettings['phone_button_label'] ) ? $generalSettings['phone_button_label'] : 'Call For Details';
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

// Designation line parts
$designation_parts = [];
if ( ! empty( $hospital_slogan ) )    $designation_parts[] = esc_html( $hospital_slogan );
if ( ! empty( $doctor_designation ) ) $designation_parts[] = esc_html( $doctor_designation );

?>

<div class="listing-details-head">
    <div class="listing-header-img">
		<?php
		if ( $listing->has_category() && $listing->can_show_category() ):
			Listing_Functions::cldoctor_listing_categories();
		endif;
		?>
		<?php
		$image_size    = 'cldoctor-size2';
		$the_thumbnail = $listing->get_the_thumbnail( $image_size );
		if ( $the_thumbnail ) {
			echo wp_kses_post( $the_thumbnail );
		}
		?>
    </div>
    <div class="meta-info-box">
        <div class="listing-details-head-top">
            <div class="listing-badge">
				<?php if ( in_array('ad_type', $detailOption) && ! empty( $listing_type ) ) : ?>
                    <span class="listing-type-badge">
                        <?php echo wp_kses_post( sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'cldoctor' ) ), $listing_type['label'] ) ); ?>
                     </span>
				<?php endif; ?>
				<?php $listing->the_badges(); ?>
            </div>
        </div>
        <div class="title-price">
            <h2 class="rtcl-listing-title">
				<?php the_title(); ?>
            </h2>
			<?php if (!empty( $hospital_slogan )) { ?>
                <div class="doctor-designation">
					<?php echo esc_html( $hospital_slogan ); ?>
                </div>
			<?php } if (!empty( $doctor_designation )) { ?>
                <div class="doctor-designation">
					<?php echo esc_html( $doctor_designation ); ?>
                </div>
			<?php } if ( $rating_count > 0 ) { ?>
                <!-- Rating -->
                <div class="listing-ratings">
					<?php echo wp_kses_post( Fns::get_rating_html( $average_rating, $rating_count ) ); ?>
                    <span class="rating-counts">(<?php echo absint( $rating_count ); ?>)</span>
                </div>
			<?php } ?>
            <div class="doctor-short-description">
				<?php echo esc_html( $short_description ); ?>
            </div>
            <!-- Price -->
			<?php if ( $listing->can_show_price() ): ?>
                <div class="rtcl-price-wrap price-in-mobile">
					<?php echo wp_kses_post( $listing->get_price_html() ); ?>
                </div>
			<?php endif; ?>
        </div>
        <div class="meta-list">
			<?php Listing_Functions::cldoctor_single_listing_meta(); ?>
        </div>

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
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </a>
							<?php else: ?>
                                <a href="javascript:void(0)" class="rtcl-require-login">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
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

					<?php if ( !empty( $phone ) ) { ?>
                        <li class="appointment-box">
                            <a href="tel:<?php echo esc_attr( $phone_url ); ?>"><i class="icon-calender-2"></i> <?php echo esc_html( $phone_label ); ?></a>
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
