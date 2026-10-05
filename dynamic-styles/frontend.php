<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Helper::requires( 'common.php', 'dynamic-styles' );

$header_transparent_color = Options::$options['header_transparent_color'];
$logo_max_width           = Options::$options['logo_width'];
$mobile_logo_max_width    = Options::$options['mobile_logo_width'];

$primary_color   = Helper::get_primary_color();
$secondary_color = Helper::get_secondary_color();
$body_color      = Helper::get_body_color();
$heading_color   = Helper::get_heading_color();

$primary_rgb   = Helper::hex2rgb( $primary_color );
$secondary_rgb = Helper::hex2rgb( $secondary_color );

$button_color_1   = Helper::get_button_color1();

?>

<?php
/*-------------------------------------
#. Defaults
---------------------------------------*/
?>
:root {
	--cldoctor-white-color: #ffffff;
	--cldoctor-primary-color: <?php echo esc_html( $primary_color ? $primary_color : '#3c19e8' ); ?>;
	--cldoctor-secondary-color: <?php echo esc_html( $secondary_color ? $secondary_color : '#ece7fe' ); ?>;
	--cldoctor-body-color: <?php echo esc_html( $body_color ? $body_color : '#7a7881' ); ?>;
	--cldoctor-heading-color: <?php echo esc_html( $heading_color ? $heading_color : '#120745' ); ?>;
	--cldoctor-button-color: <?php echo esc_html( $button_color_1 ? $button_color_1 : '#FF282C' ); ?>;
	--cldoctor-line-color: #EBEBEB;
}
<?php
/*-------------------------------------
#. Header
---------------------------------------*/
?>

.trheader .main-header {
	background-color: <?php echo esc_html( $header_transparent_color ); ?>;
}
.main-header .site-branding {
	max-width: <?php echo esc_html( $logo_max_width ); ?>;
}
@media screen and (max-width: 1199px){
	.rt-mobile-menu .mobile-menu-bar .mobile-logo-area .custom-logo,
	.main-header .site-branding {
		max-width: <?php echo esc_html( $mobile_logo_max_width ); ?>;
	}
}

<?php 
/* = Footer 1 bg images
=======================================================*/
if ( !empty( Options::$options['f1_bg_img']) ) {
	$f1_bg_img = wp_get_attachment_image_src( Options::$options['f1_bg_img'], 'full', true );
?>

footer.footer-style-1 { 
	background-image: url(<?php echo esc_url($f1_bg_img[0]); ?>) !important
}

<?php } if ( !empty( Options::$options['f1_bg_color']) ) { ?>
footer.footer.footer-style-1:after {
	background-color: <?php echo esc_html( Options::$options['f1_bg_color'] ); ?>;
}
<?php } if ( !empty( Options::$options['f1_bg_opacity']) ) { 
	$opacity = Options::$options['f1_bg_opacity']/100;
?>
footer.footer.footer-style-1:after {
	opacity: <?php echo esc_html( $opacity ); ?>;
}
<?php } if ( !empty( Options::$options['f1_cr_bg_color']) ) { ?>
footer.footer-style-1 .footer-bottom {
	background-color: <?php echo esc_html( Options::$options['f1_cr_bg_color'] ); ?>;
}
<?php } ?>

<?php 
/* = Footer 2 bg images
=======================================================*/
if ( !empty( Options::$options['f2_bg_img']) ) {
	$f2_bg_img = wp_get_attachment_image_src( Options::$options['f2_bg_img'], 'full', true );
?>

footer.footer-style-2 { 
	background-image: url(<?php echo esc_url($f2_bg_img[0]); ?>) !important
}

<?php } if ( !empty( Options::$options['f2_bg_color']) ) { ?>
footer.footer.footer-style-2:after {
	background-color: <?php echo esc_html( Options::$options['f2_bg_color'] ); ?>;
}
<?php } if ( !empty( Options::$options['f2_bg_opacity']) ) { 
	$opacity = Options::$options['f2_bg_opacity']/100;
?>
footer.footer.footer-style-2:after {
	opacity: <?php echo esc_html( $opacity ); ?>;
}
<?php } if ( !empty( Options::$options['f2_cr_bg_color']) ) { ?>
footer.footer-style-2 .footer-bottom {
	background-color: <?php echo esc_html( Options::$options['f2_cr_bg_color'] ); ?>;
}
<?php } ?>

<?php 
/* = Footer 3 bg images
=======================================================*/
if ( !empty( Options::$options['f3_bg_img']) ) {
	$f3_bg_img = wp_get_attachment_image_src( Options::$options['f3_bg_img'], 'full', true );
?>

footer.footer-style-3 { 
	background-image: url(<?php echo esc_url($f3_bg_img[0]); ?>) !important;
}

<?php } if ( !empty( Options::$options['f3_bg_color']) ) { ?>
footer.footer.footer-style-3:after {
	background-color: <?php echo esc_html( Options::$options['f3_bg_color'] ); ?>;
}
<?php } if ( !empty( Options::$options['f3_bg_opacity']) ) { 
	$opacity = Options::$options['f3_bg_opacity']/100;
?>
footer.footer.footer-style-3:after {
	opacity: <?php echo esc_html( $opacity ); ?>;
}
<?php } if ( !empty( Options::$options['f3_cr_bg_color']) ) { ?>
footer.footer-style-3 .footer-bottom {
	background-color: <?php echo esc_html( Options::$options['f3_cr_bg_color'] ); ?> !important;
}
<?php } ?>

<?php
/* = Footer 4 bg images
=======================================================*/
if ( !empty( Options::$options['f4_bg_img']) ) {
	$f4_bg_img = wp_get_attachment_image_src( Options::$options['f4_bg_img'], 'full', true );
	?>

    footer.footer-style-4 {
        background-image: url(<?php echo esc_url($f4_bg_img[0]); ?>) !important
    }

<?php } if ( !empty( Options::$options['f4_bg_color']) ) { ?>
    footer.footer.footer-style-4:after {
    background-color: <?php echo esc_html( Options::$options['f4_bg_color'] ); ?>;
    }
<?php } if ( !empty( Options::$options['f4_bg_opacity']) ) {
	$opacity = Options::$options['f4_bg_opacity']/100;
	?>
    footer.footer.footer-style-4:after {
    opacity: <?php echo esc_html( $opacity ); ?>;
    }
<?php } if ( !empty( Options::$options['f4_cr_bg_color']) ) { ?>
    footer.footer-style-4 .footer-bottom {
    background-color: <?php echo esc_html( Options::$options['f4_cr_bg_color'] ); ?>;
    }
<?php } ?>


<?php 
$pt = Options::$padding_top;
$pb = Options::$padding_bottom;
/* = Banner content padding
=======================================================*/
if ( !empty( $pt )) { ?>

.breadcrumbs-area {
	padding-top: <?php echo esc_html( $pt ); ?>px;
}

<?php } if ( !empty( $pb )) { ?>

.breadcrumbs-area {
	padding-bottom: <?php echo esc_html( $pb ); ?>px;
}

<?php } ?>