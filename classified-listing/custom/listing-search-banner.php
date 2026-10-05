<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Rtcl' ) ) {
	return;
}

use Rtcl\Helpers\Functions;
use RadiusTheme\Cldoctor\Options;
use Rtcl\Resources\Options as RtclOptions;

$loc_text = esc_html__( 'Select Location', 'cldoctor' );
$cat_text = esc_html__( 'Select Category', 'cldoctor' );
$typ_text = esc_html__( 'Select Type', 'cldoctor' );

$selected_location = $selected_category = false;

if ( get_query_var( 'rtcl_location' ) && $location = get_term_by( 'slug', get_query_var( 'rtcl_location' ), rtcl()->location ) ) {
	$selected_location = $location;
}

if ( get_query_var( 'rtcl_category' ) && $category = get_term_by( 'slug', get_query_var( 'rtcl_category' ), rtcl()->category ) ) {
	$selected_category = $category;
}

$orderby = strtolower( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_orderby', 'name' ) );
$order   = strtoupper( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_order', 'ASC' ) );

// Sanitize GET inputs for search form (read-only, nonce not required for search).
// phpcs:disable WordPress.Security.NonceVerification.Recommended
$geo_address = isset( $_GET['geo_address'] ) ? sanitize_text_field( wp_unslash( $_GET['geo_address'] ) ) : '';
$center_lat  = isset( $_GET['center_lat'] ) ? sanitize_text_field( wp_unslash( $_GET['center_lat'] ) ) : '';
$center_lng  = isset( $_GET['center_lng'] ) ? sanitize_text_field( wp_unslash( $_GET['center_lng'] ) ) : '';
$distance    = isset( $_GET['distance'] ) ? sanitize_text_field( wp_unslash( $_GET['distance'] ) ) : '';
$search_q    = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
$price_min   = isset( $_GET['filters']['price']['min'] ) ? absint( wp_unslash( $_GET['filters']['price']['min'] ) ) : null;
$price_max   = isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ? absint( wp_unslash( $_GET['filters']['price']['max'] ) ) : null;
// phpcs:enable WordPress.Security.NonceVerification.Recommended

?>

<div class="rtcl rtcl-advanced-search-widget rtcl-search-<?php echo esc_attr( $orientation ); ?> advanced-search-banner layout-<?php echo esc_attr( $layout ); ?>">
    <form action="<?php echo esc_url( Functions::get_filter_form_url() ); ?>" class="form-vertical rtcl-widget-search-form rtcl-search-inline-form advance-search-form rtin-style-<?php echo esc_attr( $style ); ?>">
        <?php if ( $search_by_listing_type == 'yes' ): ?>
            <div class="ad-type-wrapper search-radio-check rtcl-category-ajax">
                <ul class="list-inline">
                    <?php
                    $listing_types = Functions::get_listing_types();
                    $listing_types = empty( $listing_types ) ? [] : $listing_types;
                    ?>
                    <li class="ad-type-all" data-type="all">
                        <label for="all" class='ad-type-label'>
                            <input
                                class="ad-type-input-box"
                                type="radio"
                                name="all"
                                id="all"
                                value="all"
                            >
                            <span><?php esc_html_e( 'All', 'cldoctor' ); ?></span>
                        </label>
                    </li>
                    <?php foreach ( $listing_types as $key => $listing_type ): ?>
                        <li class="ad-type-<?php echo esc_attr( $key ) ?>" data-type="<?php echo esc_attr( $key ) ?>">
                            <label class="ad-type-label" for="<?php echo esc_attr( $key ); ?>">
                                <input
                                    class="ad-type-input-box"
                                    type="radio"
                                    name="<?php echo esc_attr( 'filters[ad_type]' ) ?>"
                                    id="<?php echo esc_attr( $key ); ?>"
                                    value="<?php echo esc_attr( $key ); ?>"
                                >
                                <span><?php echo esc_html( $listing_type ); ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="form-fields-wrapper">
            <?php if ( $search_by_listing_location == 'yes' ): ?>
                <?php if ( 'local' === Functions::location_type() ): ?>
                    <div class="rtin-loc-space rtin-input-item">
                        <div class="form-group">
                            <i class="icon-location"></i>
                            <?php if ( $style == 'suggestion' ): ?>
                                <div class="rtcl-search-input-button cldoctor-search-style-2 rtin-location">
                                    <input type="text" data-type="location" class="rtcl-autocomplete rtcl-location"
                                        placeholder="<?php echo esc_attr( $loc_text ); ?>"
                                        value="<?php echo esc_attr( $selected_location ? $selected_location->name : '' ); ?>">
                                    <input type="hidden" name="rtcl_location"
                                        value="<?php echo esc_attr( $selected_location ? $selected_location->slug : '' ); ?>">
                                </div>
                            <?php elseif ( $style == 'standard' ): ?>
                                <div class="rtcl-search-input-button cldoctor-search-style-2 rtin-location">
                                    <?php
                                    $loc_args = [
                                        'show_option_none'  => $loc_text,
                                        'option_none_value' => '',
                                        'taxonomy'          => rtcl()->location,
                                        'name'              => 'rtcl_location',
                                        'id'                => 'rtcl-location-search-' . wp_rand(),
                                        'class'             => 'form-control rtcl-location-search',
                                        'selected'          => get_query_var( 'rtcl_location' ),
                                        'hierarchical'      => true,
                                        'value_field'       => 'slug',
                                        'depth'             => Functions::get_location_depth_limit(),
                                        'orderby'           => $orderby,
                                        'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
                                        'show_count'        => false,
                                        'hide_empty'        => false,
                                    ];
                                    if ( '_rtcl_order' === $orderby ) {
                                        $args['orderby']  = 'meta_value_num';
                                        $args['meta_key'] = '_rtcl_order'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
                                    }
                                    wp_dropdown_categories( $loc_args );
                                    ?>
                                </div>
                            <?php elseif ( $style == 'dependency' ): ?>
                                <div class="rtcl-search-input-button cldoctor-search-style-2 rtin-location">
                                    <?php
                                    Functions::dropdown_terms( [
                                        'show_option_none' => $loc_text,
                                        'taxonomy'         => rtcl()->location,
                                        'name'             => 'l',
                                        'class'            => 'form-control',
                                        'selected'         => $selected_location ? $selected_location->term_id : 0
                                    ] );
                                    ?>
                                </div>
                            <?php else: ?>
                                <div class="rtcl-search-input-button rtcl-search-input-location">
                                    <span class="search-input-label location-name">
                                        <?php echo esc_html( $selected_location ? $selected_location->name : $loc_text ); ?>
                                    </span>
                                    <input type="hidden" class="rtcl-term-field" name="rtcl_location"
                                        value="<?php echo esc_attr( $selected_location ? $selected_location->slug : '' ); ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="rtin-loc-space rtin-input-item">
                        <div class="form-group">
                            <div class="rtcl-search-input-button cldoctor-search-style-2 rtin-location rtcl-geo-address-field">
                                <input type="text" name="geo_address" autocomplete="off"
                                    value="<?php echo esc_attr( $geo_address ); ?>"
                                    placeholder="<?php esc_attr_e( "Select a location", "cldoctor" ); ?>"
                                    class="form-control rtcl-geo-address-input"/>
                                <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
                                <input type="hidden" class="latitude" name="center_lat"
                                    value="<?php echo esc_attr( $center_lat ); ?>">
                                <input type="hidden" class="longitude" name="center_lng"
                                    value="<?php echo esc_attr( $center_lng ); ?>">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ( $search_by_geo_location == 'yes' ): ?>
                <div class="search-item search-geo-address rtin-input-item">
                    <div class="input-group rtcl-geo-address-field">
                        <input id="rtcl-geo-address-search" type="text" name="geo_address" autocomplete="off"
                                value="<?php echo esc_attr( $geo_address ); ?>"
                                placeholder="<?php esc_attr_e( "Enter location", "cldoctor" ) ?>"
                                class="form-control rtcl-geo-address-input"/>
                        <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
                        <input type="hidden" class="latitude" name="center_lat"
                                value="<?php echo esc_attr( $center_lat ); ?>">
                        <input type="hidden" class="longitude" name="center_lng"
                                value="<?php echo esc_attr( $center_lng ); ?>">
                    </div>
                </div>
                <?php if ( $search_by_radius_distance == 'yes' ):
                    $default_radius = apply_filters( 'cldoctor_widget_default_radius', '' );
                    $rs_data = RtclOptions::radius_search_options();
                    ?>
                    <div class="search-item search-radius rtin-input-item">
                        <div class="input-group">
                            <div class="rtcl-search-input-button">
                                <input type="number" class="form-control" name="distance"
                                    value="<?php echo esc_attr( '' !== $distance ? $distance : $default_radius ); ?>"
                                    placeholder="<?php esc_attr_e( "Radius", "cldoctor" ); ?>">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif ?>

            <?php if ( $search_by_listing_category == 'yes' ): ?>
                <div id='cldoctor-rtcl-category' class="rtin-cat-space rtin-input-item" data-style="<?php echo esc_attr( $style ); ?>">
                    <div class="form-group">
                        <i class="icon-select-category"></i>
                        <?php if ( $style == 'suggestion' || $style == 'standard' ): ?>
                            <div class="rtcl-search-input-button cldoctor-search-style-2 rtin-category">
                                <?php
                                $cat_args = [
                                    'show_option_none'  => $cat_text,
                                    'option_none_value' => '',
                                    'taxonomy'          => rtcl()->category,
                                    'name'              => 'rtcl_category',
                                    'id'                => 'rtcl-category-search-' . wp_rand(),
                                    'class'             => 'form-control rtcl-category-search rtcl-category-search-ajax',
                                    'selected'          => get_query_var( 'rtcl_category' ),
                                    'hierarchical'      => true,
                                    'value_field'       => 'slug',
                                    'depth'             => Functions::get_category_depth_limit(),
                                    'orderby'           => $orderby,
                                    'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
                                    'show_count'        => false,
                                    'hide_empty'        => false,
                                ];
                                if ( '_rtcl_order' === $orderby ) {
                                    $args['orderby']  = 'meta_value_num';
                                    $args['meta_key'] = '_rtcl_order'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
                                }
                                wp_dropdown_categories( $cat_args );
                                ?>
                            </div>
                        <?php elseif ( $style == 'dependency' ): ?>
                            <div class="rtcl-search-input-button cldoctor-search-style-2 cldoctor-search-dependency rtin-category">
                                <?php
                                Functions::dropdown_terms( [
                                    'show_option_none'  => $cat_text,
                                    'option_none_value' => - 1,
                                    'taxonomy'          => rtcl()->category,
                                    'name'              => 'c',
                                    'class'             => 'form-control rtcl-category-search',
                                    'selected'          => $selected_category ? $selected_category->term_id : 0
                                ] );
                                ?>
                            </div>
                        <?php else: ?>
                            <div class="rtcl-search-input-button rtcl-search-input-category">
                                <span class="search-input-label category-name">
                                    <?php echo esc_html( $selected_category ? $selected_category->name : $cat_text ); ?>
                                </span>
                                <input type="hidden" name="rtcl_category" class="rtcl-term-field"
                                    value="<?php echo esc_attr( $selected_category ? $selected_category->slug : '' ); ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ( $search_by_keyword == 'yes' ): ?>
                <div class="keyword-and-button-wrap rtin-input-item">
                    <div class="rtin-key-space rtin-input-item">
                        <div class="form-group">
                            <div class="rtcl-search-input-button rtin-keyword position-relative">
                                <input type="text" data-type="listing" name="q" class="rtcl-autocomplete form-control"
                                    placeholder="<?php esc_attr_e( 'Keyword Search...', 'cldoctor' ); ?>"
                                    value="<?php echo esc_attr( $search_q ); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $search_by_price == 'yes' ): ?>
            <div class="search-item price-item-box rtin-input-item">
                <div class="price-range">
                    <?php
                    $currency  = Functions::get_currency_symbol();
                    $data_form = '';
                    $data_to   = '';
                    if ( null !== $price_min ) {
                        $data_form .= sprintf( "data-from=%s", $price_min );
                    }
                    if ( null !== $price_max ) {
                        $data_to .= sprintf( "data-to=%s", $price_max );
                    }
                    ?>
                    <input type="number"
                        class="ion-rangeslider" <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_to ); ?>
                        data-min="<?php echo isset( $price_range_min ) && ! empty( $price_range_min ) ? esc_attr( $price_range_min ) : 0; ?>"
                        data-max="<?php echo isset( $price_range_max ) && ! empty( $price_range_max ) ? esc_attr( $price_range_max ) : 8000; ?>"
                        data-prefix="<?php echo esc_html( $currency ) ?>"
                        placeholder="<?php echo esc_attr("Price Range: {$price_range_min} - {$price_range_max}"); ?>"/>
                    <input type="hidden" class="min-volumn" name="filters[price][min]"
                        value="<?php echo null !== $price_min ? absint( $price_min ) : ''; ?>">
                    <input type="hidden" class="max-volumn" name="filters[price][max]"
                        value="<?php echo null !== $price_max ? absint( $price_max ) : ''; ?>">
                </div>
            </div>
			<?php endif; ?>
            
            <div class="advanced-search-btn-wrapper">
                <button type="submit" class="rtin-search-btn button-style-2">
                    <i class="icon-search"></i>
                    <?php echo esc_html( $btntext ); ?>
                </button>
            </div>
        </div>
    </form>
</div>