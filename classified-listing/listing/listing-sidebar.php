<?php
/**
 * @author  RadiusTheme
 * @since   1.1.4
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Link;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;
use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Listing_Functions;
use Rtcl\Controllers\BusinessHoursController as BHS;
use RtclBooking\Helpers\Functions as BookingFunctions;
use RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingFrontendHooks as RtrbFrontendHooks;

global $listing;

// Sidebar position
$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );
$sidebar_class    = [ 'col-xl-3 col-lg-4', 'order-2 sidebar-possition-right' ];
if ( $sidebar_position === 'left' ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1 sidebar-possition-left';
} elseif ( $sidebar_position === 'bottom' ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-xl-3 col-lg-4' ] );
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}

$hide_listing_map = get_post_meta( get_the_ID(), 'hide_map', true );

// Data
$listing_id      = $listing->get_id();
$listingAddress  = get_post_meta( $listing_id, 'address', true );
$listingPhone    = get_post_meta( $listing_id, 'phone', true );
$listingPhoneUrl = str_replace( ' ', '', $listingPhone );
$listingWhatsapp   = get_post_meta( $listing->get_id(), '_rtcl_whatsapp_number', true );
$listingEmail      = get_post_meta( $listing->get_id(), 'email', true );
$doctor_meet_time = get_post_meta( $listing_id, 'doctor_meet_time', true );
$hospital_time   = get_post_meta( $listing_id, 'hospital_time', true );
$hours_text      = ! empty( $doctor_meet_time ) ? $doctor_meet_time : $hospital_time;
$is_247          = ! empty( $hours_text ) && false !== stripos( $hours_text, '24/7' );
$generalSettings = Functions::get_option( 'rtcl_general_listing_label_settings' );
$phone_label     = ! empty( $generalSettings['phone_button_label'] ) ? $generalSettings['phone_button_label'] : 'Call For Details';
$booking_label   = ! empty( $generalSettings['booking_button_label'] ) ? $generalSettings['booking_button_label'] : 'Book Appointment';
$has_price       = $listing->can_show_price();
$location_url    = ! empty( $listingAddress ) ? 'https://maps.google.com/?q=' . rawurlencode( $listingAddress ) : '';
$add_type         = $listing->get_ad_type();
$listing_types    = Functions::get_listing_types();
$clinic_tab_label = ! empty( $listing_types[ $add_type ] ) ? $listing_types[ $add_type ] : $add_type;
$single_settings = Functions::get_option( 'rtcl_single_listing_settings' );
$show_address    = ! empty( $single_settings['display_options_detail'] ) && in_array( 'address', $single_settings['display_options_detail'] );

// Video URLs
$video_urls = [];
if ( ! Functions::is_video_urls_disabled() ) {
	$video_urls = get_post_meta( $listing_id, '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}
$has_video = ! empty( $video_urls );

// Chat button vars
$show_chat    = false;
$chat_classes = 'rtcl-chat-link';
$chat_url     = '';
$chat_label   = '';
if ( class_exists( 'RtclPro' ) && Fns::is_enable_chat() && ( ( is_user_logged_in() && $listing->get_author_id() !== get_current_user_id() ) || ! is_user_logged_in() ) ) {
	$show_chat = true;
	if ( is_user_logged_in() ) {
		$chat_url     = '#';
		$chat_classes = 'rtcl-chat-link rtcl-contact-seller';
		$chat_label   = esc_html__( 'Quick Chat', 'cldoctor' );
	} else {
		$chat_url     = esc_url( Link::get_my_account_page_link() );
		$chat_classes = 'rtcl-chat-link rtcl-no-contact-seller';
		$chat_label   = esc_html__( 'Login for Chat', 'cldoctor' );
	}
}

?>
<div class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
	<div class="listing-sidebar listing-sidebar-new">
		<!-- ===================== BOOKING CARD ===================== -->
		<div class="sb-booking-card">
			<?php if ( $has_price ) : ?>
			<div class="sb-price-row">
				<div class="sb-price"><?php echo wp_kses_post( $listing->get_price_html() ); ?></div>
				<span class="sb-price-meta">
					<span class="sb-avail-dot"></span>
					<?php esc_html_e( 'Available today', 'cldoctor' ); ?>
				</span>
			</div>
			<?php endif; ?>

			<div class="sb-booking-options">
				<button class="sb-opt-btn sb-opt-active" type="button" data-target="sb-clinic-content">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l8-4 8 4v14"></path></svg>
					<?php echo esc_html( $clinic_tab_label ); ?>
				</button>
				<?php if ( $has_video ) : ?>
				<button class="sb-opt-btn" type="button" data-target="sb-video-content">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
					<?php esc_html_e( 'Video visit', 'cldoctor' ); ?>
				</button>
				<?php endif; ?>
			</div>

			<!-- In-clinic content -->
			<div class="sb-clinic-content">
				<?php
                    if ( function_exists( 'rtrb_cl_booking_button' ) ) {
                        rtrb_cl_booking_button();
                    }
                ?>
				<div class="sb-book-foot">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"></path><circle cx="12" cy="12" r="10"></circle></svg>
					<?php esc_html_e( 'Free cancellation up to 24h before', 'cldoctor' ); ?>
				</div>
			</div>

			<!-- Video visit content -->
			<?php if ( $has_video ) : ?>
			<div class="sb-video-content" style="display:none;">
				<div class="sb-video-wrap ratio ratio-16x9">
					<iframe class="rtcl-lightbox-iframe" src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_urls[0] ) ); ?>" allowfullscreen loading="lazy"></iframe>
				</div>
				<div class="sb-book-foot">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"></path><circle cx="12" cy="12" r="10"></circle></svg>
					<?php esc_html_e( 'Video consultation available', 'cldoctor' ); ?>
				</div>
			</div>
			<?php endif; ?>
		</div>

		<!-- ===================== CLINIC INFO CARD ===================== -->
		<div class="sb-info-card">
			<h3>
                <?php echo esc_html( $clinic_tab_label ); ?>
                <?php esc_html_e('Information', 'cldoctor' ); ?>
            </h3>

			<?php if ( ! empty( $hours_text ) ) : ?>
			<div class="sb-info-row">
				<div class="sb-info-icon">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
				</div>
				<div class="sb-info-text">
					<div class="sb-info-label"><?php esc_html_e( 'Working hours', 'cldoctor' ); ?></div>
					<div class="sb-info-value"><?php echo esc_html( $hours_text ); ?></div>
					<?php if ( $is_247 ) : ?>
					<span class="sb-avail-pill">
						<span class="sb-pulse"></span>
						<?php esc_html_e( 'Open now', 'cldoctor' ); ?>
					</span>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>

			<?php if ( ! empty( $listingAddress ) && $show_address || ( $listing->has_location() && $listing->can_show_location() ) ) : ?>
			<div class="sb-info-row">
				<div class="sb-info-icon">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
				</div>
				<div class="sb-info-text">
					<div class="sb-info-label"><?php esc_html_e( 'Address', 'cldoctor' ); ?></div>
					<?php if ( ! empty( $listingAddress && $show_address ) ) : ?>
					<div class="sb-info-value"><?php echo esc_html( $listingAddress ); ?></div>
					<?php endif; ?>
					<?php if ( $listing->has_location() && $listing->can_show_location() ) : ?>
					<div class="sb-info-sub sb-location-sub"><?php $listing->the_locations( true, true ); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>

			<?php if ( $listingPhone && Functions::check_visibility( $listing->get_author_id(), 'phone' ) ) : ?>
			<div class="sb-info-row">
				<div class="sb-info-icon">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
				</div>
				<div class="sb-info-text">
					<div class="sb-info-label"><?php esc_html_e( 'Phone', 'cldoctor' ); ?></div>
					<div class="sb-info-value"><?php Listing_Functions::cldoctor_seller_phone_number( $listing, 'phone' ); ?></div>
				</div>
			</div>
			<?php endif; ?>

			<?php if ( $listingWhatsapp && Functions::check_visibility( $listing->get_author_id(), 'whatsapp' ) ) : ?>
                <div class="sb-info-row whatsapp-icon">
                    <div class="sb-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m187.477 92.424-35.8-73.486a30.531 30.531 0 0 0-26.864-17.407L86.229.279a49.811 49.811 0 0 0-31.6 9.691C18.943 35.855.083 76.39.083 127.192a16 16 0 0 0 .126 2.008C12.957 230.152 55.794 319.613 124.09 387.909S281.848 499.043 382.8 511.791a15.991 15.991 0 0 0 2 .126c50.8 0 91.338-18.861 117.222-54.543a49.9 49.9 0 0 0 9.692-31.6l-1.253-38.585a30.537 30.537 0 0 0-17.407-26.862l-73.486-35.8a55.683 55.683 0 0 0-68.3 15.412l-25.657 32.146c-88.274-40.1-145.6-97.429-185.7-185.7l32.147-25.657a55.682 55.682 0 0 0 15.419-68.304zm218.084 260.867 72.944 35.537.875 26.935a613.158 613.158 0 0 1-109.465-47.878l6.377-7.989a23.574 23.574 0 0 1 29.269-6.605zm-92.421 48.266a30.957 30.957 0 0 0 36.522-8.347A644.453 644.453 0 0 0 469.9 446.327c-19.671 22.093-47.9 33.384-84.094 33.587C195.265 455.426 56.574 316.735 32.086 126.2c.2-36.065 11.418-64.224 33.358-83.886 12.695 40.076 30.6 80.364 53.352 120.019a30.959 30.959 0 0 0-8.353 36.525c43.074 95.217 107.483 159.626 202.697 202.699zM152.1 135.709l-8.01 6.393c-20.459-36.29-36.61-73.025-48.157-109.491l27.235.884 35.537 72.944a23.575 23.575 0 0 1-6.605 29.27zM384.322.083a127.617 127.617 0 0 0-113.786 185.38l-13.326 49.731a16 16 0 0 0 19.6 19.6l49.731-13.325A127.606 127.606 0 1 0 384.322.083zm95.595 127.6a95.612 95.612 0 0 1-143.363 82.828 16 16 0 0 0-12.144-1.6l-29.118 7.8 7.8-29.118a16 16 0 0 0-1.6-12.145A95.605 95.605 0 0 1 451.917 60.082a94.973 94.973 0 0 1 28 67.596zm-25.326 26.731a16 16 0 0 1-16 16h-108.6a16 16 0 0 1 0-32h108.6a16 16 0 0 1 16 15.995zm-140.6-53.4a16 16 0 0 1 16-16h62.365a16 16 0 0 1 0 32h-62.365a16 16 0 0 1-16-16.005z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                    </div>
                    <div class="sb-info-text">
                        <div class="sb-info-label"><?php esc_html_e( 'Whatsapp', 'cldoctor' ); ?></div>
                        <div class="sb-info-value"><?php Listing_Functions::cldoctor_seller_whatsapp_number( $listing ); ?></div>
                    </div>
                </div>
			<?php endif; ?>

            <?php if ( $listingEmail && Functions::check_visibility( $listing->get_author_id(), 'email' ) ) : ?>
                <div class="sb-info-row email-icon">
                    <div class="sb-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path fill-rule="evenodd" d="M462.88 337.781c0 43.236-35.17 78.351-78.351 78.351H127.472c-43.181 0-78.352-35.116-78.352-78.351V174.219a77.802 77.802 0 0 1 10.809-39.615l125.428 125.428c18.765 18.82 43.894 29.19 70.67 29.19 26.721 0 51.85-10.37 70.615-29.19L452.07 134.604a77.79 77.79 0 0 1 10.809 39.615v163.562zM384.528 95.868H127.472c-17.832 0-34.293 6.035-47.461 16.076l126.69 126.745c13.114 13.058 30.616 20.301 49.326 20.301 18.655 0 36.158-7.243 49.271-20.301l126.69-126.745c-13.167-10.041-29.627-16.076-47.46-16.076zm0-30.232H127.472c-59.861 0-108.584 48.723-108.584 108.584v163.562c0 59.916 48.723 108.584 108.584 108.584h257.056c59.861 0 108.584-48.668 108.584-108.584V174.219c0-59.861-48.723-108.583-108.584-108.583z" clip-rule="evenodd" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                    </div>
                    <div class="sb-info-text">
                        <div class="sb-info-label"><?php esc_html_e( 'Email', 'cldoctor' ); ?></div>
                        <a class="rtcl-phone-link" href="mailto:<?php echo esc_attr( $listingEmail ); ?>" target="_blank">
		                    <?php echo esc_html( $listingEmail ); ?>
                        </a>
                    </div>
                </div>
			<?php endif; ?>

			<?php if ( $show_chat ) : ?>
			<a class="sb-chat-btn <?php echo esc_attr( $chat_classes ); ?>" href="<?php echo esc_url( $chat_url ); ?>" data-listing_id="<?php the_ID(); ?>">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
				<?php echo esc_html( $chat_label ); ?>
			</a>
			<?php endif; ?>
		</div>

        <?php do_action( 'rtcl_single_listing_social_profiles' ) ?>

		<!-- ===================== MAP CARD ===================== -->
		<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ) : ?>
		<div class="sb-map-card">
			<div class="sb-map-head">
				<h4><?php esc_html_e( 'Location', 'cldoctor' ); ?></h4>
				<?php if ( ! empty( $location_url ) ) : ?>
				<a class="sb-directions" href="<?php echo esc_url( $location_url ); ?>" target="_blank" rel="nofollow noopener">
					<?php esc_html_e( 'Get directions', 'cldoctor' ); ?>
					<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
				</a>
				<?php endif; ?>
			</div>
			<div class="sb-map-canvas">
				<?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
			</div>
		</div>
		<?php endif; ?>

		<?php
		if ( ! empty( BHS::get_business_hours( $listing->get_id() ) ) ) :
			do_action( 'rtcl_single_listing_business_hours' );
		endif;

		if ( Helper::has_sidebar() ) :
			do_action( 'rtcl_sidebar' );
		endif;
		?>

	</div><!-- .listing-sidebar -->
</div>
