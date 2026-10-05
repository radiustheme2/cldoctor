<?php
/**
 *
 * @author 		RadiusTheme
 * @package 	classified-listing/templates
 * @version     1.0.0
 */

use RadiusTheme\Cldoctor\Options;
use RadiusTheme\Cldoctor\Helper;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
do_action( 'rtcl_before_account_navigation' );
$logo_url = '';
$custom_logo_id = get_theme_mod( 'custom_logo' );
if (!empty($custom_logo_id)) {
	$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
} else {
	$logo_url = wp_get_attachment_image_url( Options::$options['logo_dark'], 'full' );
}
?>
<nav class="rtcl-MyAccount-navigation">
	<?php $light_logo = empty( $logo_url ) ? Helper::get_img( 'logo-light.svg' ) : $logo_url; ?>
    <div class="rtcl-myaccount-logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( ! empty( $logo_url ) ) { ?>
                <a class="light-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						echo wp_get_attachment_image( Options::$options['logo_dark'], 'full' ); 
					?>
                </a>
			<?php } else { ?>
                <a class="light-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo esc_url( $light_logo ); ?>" width="150" height="45" alt="<?php bloginfo( 'name' ); ?>">
                </a>
			<?php } ?>
        </a>
    </div>
	<ul>
		<?php foreach ( Functions::get_account_menu_items() as $endpoint => $label ) : ?>
			<?php if ( 'add-listing' === $endpoint ): ?>
                <li class="<?php echo esc_attr( Functions::get_account_menu_item_classes( $endpoint ) ); ?>">
                    <a href="<?php echo esc_url( Link::get_listing_form_page_link() ); ?>"><?php echo esc_html( $label ); ?></a>
                </li>
			<?php else: ?>
                <li class="<?php echo esc_attr( Functions::get_account_menu_item_classes( $endpoint ) ); ?>">
                    <a data-href="<?php echo esc_url( Link::get_account_endpoint_url( $endpoint ) ); ?>"
                       href="<?php echo esc_url( Link::get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                </li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<?php do_action( 'rtcl_after_account_navigation_list' ); ?>
</nav>

<?php do_action( 'rtcl_after_account_navigation' ); ?>

