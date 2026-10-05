<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Options;

global $listing;

$rtcl_view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! empty( $rtcl_view ) && in_array( $rtcl_view, [ 'grid', 'list' ], true ) ) {
	$view = $rtcl_view;
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

$style = Options::$options['listing_archive_style'];

?>
<div <?php Functions::listing_class('listing-layout-'.$style, $listing) ?><?php Functions::listing_data_attr_options() ?>>
    <?php
		if ( $view == 'grid' ) {
			Helper::get_custom_listing_template( 'archive/grid/grid-'.$style );
		} else {
			Helper::get_custom_listing_template( 'archive/list/list-'.$style );
		}
	?>
</div>
