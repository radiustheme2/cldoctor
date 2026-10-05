<?php
/**
 * @var string  $address
 * @var string  $phone
 * @var string  $whatsapp_number
 * @var string  $email
 * @var string  $website
 * @var array   $phone_options
 * @var bool    $has_contact_form
 * @var string  $email_to_seller_form
 * @var Listing $listing
 * @var array   $locations
 * @var int     $listing_id Listing id
 * @author      RadiusTheme
 * @package     cldoctor/templates
 * @version     1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Listing_Functions;

global $listing;
$owner_id          = $listing->get_owner_id();
$ownerUrl          = get_author_posts_url($owner_id);
$listingAddress    = get_post_meta( $listing->get_id(), 'address', true );
$listingPhone      = get_post_meta( $listing->get_id(), 'phone', true );
$listingWhatsapp   = get_post_meta( $listing->get_id(), '_rtcl_whatsapp_number', true );
$listingEmail      = get_post_meta( $listing->get_id(), 'email', true );
$website           = get_post_meta( $listing->get_id(), 'website', true );
$listingWebsite    = str_replace(['https://', 'http://'], '', $website );
$has_contact_form  = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_contact_form', false, 'checkbox' );
$hide_listing_map  = get_post_meta( get_the_ID(), 'hide_map', true );

$general_listing_label = Functions::get_option( 'rtcl_general_listing_label_settings' );
$doctor_phone_label = !empty( $general_listing_label['doctor_phone_label'] ) ? $general_listing_label['doctor_phone_label'] : 'Make an Appointment';
$hospital_phone_label = !empty( $general_listing_label['hospital_phone_label'] ) ? $general_listing_label['hospital_phone_label'] : 'Call for Details';
$doctor_meet_time = get_post_meta( $listing->get_id(), 'doctor_meet_time', true );
$hospital_time = get_post_meta( $listing->get_id(), 'hospital_time', true );

?>

<?php if ( $doctor_meet_time || $listing->can_show_user() || $listingAddress || $listingEmail || $website || $hospital_time ){ ?>
<ul class="info-list">
    <?php if ( !empty($doctor_meet_time) ): ?>
        <li class="meet-time">
            <i class="icon-calender-3"></i>
            <?php echo esc_html( $doctor_meet_time ); ?>
        </li>
    <?php endif; ?>
    <?php
        if ($listing->can_show_user()) {
            if ( $listing->can_add_user_link() && ! is_author() ) : ?>
                <li class="meta-user">
                    <i class="icon-user-2"></i>
                    <a class="author-link" href="<?php echo esc_url( $ownerUrl ); ?>">
                        <?php echo esc_html( $listing->get_owner_name() ); ?>
                    </a>
                </li>
            <?php else: ?>
                <li class="meta-user">
                    <i class="icon-user-2"></i>
                    <?php echo wp_kses_post( $listing->get_owner_name() ); ?>
                </li>
            <?php endif;
        }
    ?>

    <?php if ( $listingAddress ){ ?>
        <li class="meta-address">
            <span class="icon d-flex">
                <i class="icon-location-pointer"></i>
            </span>
            <span class="address">
                <?php echo esc_html( $listingAddress ); ?>
            </span>
        </li>
    <?php } ?>

    <?php if ($listingPhone && Functions::check_visibility( $listing->get_author_id(), 'phone' ) ) : ?>
        <li class="rtin-phone">
            <i class="icon-phone-2"></i>
            <?php Listing_Functions::cldoctor_seller_phone_number( $listing, 'phone' ); ?>
        </li>
    <?php endif; ?>
    <?php if ($listingWhatsapp && Functions::check_visibility( $listing->get_author_id(), 'whatsapp' )) : ?>
        <li class="rtin-whatsapp">
            <i class="fab fa-whatsapp"></i>
            <?php Listing_Functions::cldoctor_seller_whatsapp_number( $listing, 'whatsapp' ); ?>
        </li>
    <?php endif; ?>

    <?php if ( $listingEmail && Functions::check_visibility( $listing->get_author_id(), 'email' ) ){ ?>
        <li class="meta-email">
            <div class="icon d-flex">
                <i class="icon-email"></i>
            </div>
            <a class="rtcl-phone-link" href="mailto:<?php echo esc_attr( $listingEmail ); ?>" target="_blank">
                <?php echo esc_html( $listingEmail ); ?>
            </a>
        </li>
    <?php } if ( $website ){ ?>
        <li class="meta-website">
            <div class="icon d-flex">
                <?php echo wp_kses_post( Helper::website_icon() ); ?>
            </div>
            <a class="rtcl-website-link" href="<?php echo esc_url( $website ); ?>" target="_blank"
                <?php echo Functions::is_external( $website ) ? ' rel="nofollow"' : ''; ?>>
                <?php echo esc_html( $listingWebsite ); ?>
            </a>
        </li>
    <?php } ?>
    <?php if ( !empty($hospital_time) ): ?>
        <li class="meet-time">
            <i class="icon-calender-3"></i>
            <?php echo esc_html( $hospital_time ); ?>
        </li>
    <?php endif; ?>
</ul>
<?php } ?>
<?php
    $social_list = Functions::get_user_social_profile( $owner_id );
    if ( ! empty( $social_list ) ) {
?>
    <div class="rtcl-user-social">
        <span><?php esc_html_e( 'Follow Us On:', 'cldoctor' ); ?></span>
        <div class="social-list">
            <?php
                foreach ( $social_list as $key => $value ) {
                    ?>
                    <a target="_blank" href="<?php echo esc_url( $value ) ?>">
                        <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $key ) ?>"></i>
                    </a>
                    <?php
                }
            ?>
        </div>
    </div>
<?php } ?>
