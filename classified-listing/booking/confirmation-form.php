<?php
/**
 * Booking Confirmation Form
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.1
 *
 * @var int $listing_id
 * @var int $user_id
 */

use Rtcl\Helpers\Functions;
use RtclBooking\Helpers\Functions as BookingFunctions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header( 'listing' ); ?>

<?php
/**
 * rtcl_before_main_content hook.
 *
 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked rtcl_breadcrumb - 20
 */
do_action( 'rtcl_before_main_content' );
if ( is_user_logged_in() ) {
	?>
	<?php
	$name         = get_the_author_meta( 'display_name', $user_id );
	$email        = get_the_author_meta( 'user_email', $user_id );
	$phone        = get_the_author_meta( '_rtcl_phone', $user_id );
	$guest        = isset( $_GET['guest'] ) ? sanitize_text_field( wp_unslash( $_GET['guest'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$booking_date = isset( $_GET['booking_date'] ) ? sanitize_text_field( wp_unslash( $_GET['booking_date'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$time_slot    = isset( $_GET['time_slot'] ) ? sanitize_text_field( wp_unslash( $_GET['time_slot'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$ticket_fee   = (int) BookingFunctions::get_booking_meta( $listing_id, '_rtcl_booking_fee' );
	$total_fee    = $ticket_fee * (int) $guest;
	$booking_type = BookingFunctions::get_booking_meta( $listing_id, 'rtcl_listing_booking_type' );
	if ( 'rent' == $booking_type ) {
		$days      = BookingFunctions::get_days_from_date_range( $booking_date );
		$total_fee = $ticket_fee * (int) $days;
	}
	?>
    <div class="rtcl-booking-confirmation-form-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="rtcl-booking-confirmation-wrapper">
                        <div class="rtcl-reservation-info">
                            <div class="rtcl-guest-count">
                                <?php if ( 'pre_order' == $booking_type ): ?>
                                    <span><?php echo esc_html( sprintf( /* translators: %1\$s is the currency symbol, %2\$s is the fee amount */
                                    esc_html__( 'Booking Fee: %1\$s%2\$s', 'cldoctor' ), Functions::get_currency_symbol(), $ticket_fee ) ); ?></span>
                                    <span><?php echo esc_html( sprintf( /* translators: %s is the number of items/guests */
                                    esc_html__( 'Item: %s', 'cldoctor' ), $guest ) ); ?></span>
                                    <span><?php echo esc_html( sprintf( /* translators: %1\$s is the currency symbol, %2\$s is the total fee amount */
                                    esc_html__( 'Total Fee: %1\$s%2\$s', 'cldoctor' ), Functions::get_currency_symbol(), $total_fee ) ); ?></span>
                                <?php else: ?>
                                    <span><?php echo esc_html( sprintf( /* translators: %1\$s is the currency symbol, %2\$s is the reservation fee amount */
                                    esc_html__( 'Reservation Fee: %1\$s%2\$s', 'cldoctor' ), Functions::get_currency_symbol(), $ticket_fee ) ); ?></span>
                                    <span><?php echo esc_html( sprintf( /* translators: %s is the number of guests */
                                    esc_html__( 'Guest: %s', 'cldoctor' ), $guest ) ); ?></span>
                                    <?php if ( 'rent' == $booking_type ): ?>
                                        <span><?php echo esc_html( sprintf( /* translators: %s is the booking date */
                                        esc_html__( 'Date: %s', 'cldoctor' ), $booking_date ) ); ?></span>
                                        <span><?php echo esc_html( sprintf( /* translators: %s is the number of days */
                                        esc_html__( 'Days: %s', 'cldoctor' ), $days ) ); ?></span>
                                    <?php endif; ?>
                                    <span><?php echo esc_html( sprintf( /* translators: %1\$s is the currency symbol, %2\$s is the total reservation fee */
                                    esc_html__( 'Total Reservation Fee: %1\$s%2\$s', 'cldoctor' ), Functions::get_currency_symbol(), $total_fee ) ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h3><?php esc_html_e( 'Personal Information', 'cldoctor' ); ?></h3>
                        <?php
                        if ( 'rent' == $booking_type ) {
                            $ticket_fee = $total_fee;
                        }
                        ?>
                        <form method="post" class="rtcl-booking-confirmation-form">
                            <div class="form-group">
                                <label for="name"><?php esc_html_e( 'Name', 'cldoctor' ); ?></label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo esc_attr( $name ); ?>" required/>
                            </div>
                            <div class="form-group">
                                <label for="email"><?php esc_html_e( 'Email', 'cldoctor' ); ?></label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo esc_attr( $email ); ?>" required/>
                            </div>
                            <div class="form-group">
                                <label for="phone"><?php esc_html_e( 'Phone', 'cldoctor' ); ?></label>
                                <input type="tel" name="phone" id="phone" class="form-control" value="<?php echo esc_attr( $phone ); ?>" required/>
                            </div>
                            <div class="form-group">
                                <label for="message"><?php esc_html_e( 'Message', 'cldoctor' ); ?></label>
                                <textarea placeholder="<?php esc_attr_e( 'Write your message here', 'cldoctor' ); ?>" name="message" id="message" class="form-control"></textarea>
                            </div>
                            <input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing_id ); ?>"/>
                            <input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>"/>
                            <input type="hidden" name="ticket_no" value="<?php echo esc_attr( $guest ); ?>"/>
                            <input type="hidden" name="ticket_fee" value="<?php echo esc_attr( $ticket_fee ); ?>"/>
                            <?php if ( ! empty( $booking_date ) ): ?>
                                <input type="hidden" name="booking_date" value="<?php echo esc_attr( $booking_date ); ?>"/>
                            <?php endif; ?>
                            <?php if ( ! empty( $time_slot ) ): ?>
                                <input type="hidden" name="time_slot" value="<?php echo esc_attr( $time_slot ); ?>"/>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Confirm', 'cldoctor' ); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
}
/**
 * rtcl_after_main_content hook.
 *
 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'rtcl_after_main_content' );
?>

<?php
get_footer( 'listing' );