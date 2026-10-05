<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RadiusTheme\Cldoctor\Listing_Functions;
use Rtcl\Controllers\Hooks\TemplateHooks;

global $listing;

if (!class_exists('RtclPro')) return;

$listing_type = Listing_Functions::get_listing_type( $listing );
?>
<div class="item-img bg--gradient-50">
    <div class="cldoctor-listing-actions-buttons">
        <?php 
            if ( $listing->has_category() && $listing->can_show_category() ):
                Listing_Functions::cldoctor_listing_categories();
            endif; 
        ?>
        <?php if ( $listing->can_show_ad_type() && ! empty( $listing_type ) ): ?>
            <span class="listing-type-badge">
                <?php echo wp_kses_post( sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'cldoctor' ) ), $listing_type['label'] ) ); ?>
            </span>
        <?php endif; ?>
        <?php TemplateHooks::loop_item_badges(); ?>
    </div>

    <div class="listing-thumb">
        <a href="<?php the_permalink(); ?>" class="rtcl-media grid-view-img"><?php echo wp_kses_post( $listing->get_the_thumbnail( 'cldoctor-size1' ) ); ?></a>
	</div>

    <ul class="meta-tags">
        <?php if ( Functions::is_enable_favourite() ) { ?>
            <?php if ( is_user_logged_in() ) { ?>
                <li class="meta-item meta-favourite">
                    <?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
                </li>
            <?php } else { ?>
                <li class="meta-item meta-favourite">
                    <?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>