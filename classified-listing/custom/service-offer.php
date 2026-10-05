<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\ClProperty\Listing_Functions;
use Rtcl\Helpers\Functions;

global $listing;

$service_offers = get_post_meta($listing->get_id(), "service_offers", true);

$section_title = '';
$listing_form  = $listing->getForm();
if ( ! empty( $listing_form ) ) {
	foreach ( $listing_form->getSections() as $section ) {
		if ( isset( $section['id'] ) && strpos( $section['id'], 'section_' ) !== false ) {
			foreach ( ( $section['columns'][0]['fields'] ?? [] ) as $field_id ) {
				$field = $listing_form->getFieldByUuid( $field_id );
				if ( ! empty( $field ) && ( $field['name'] ?? '' ) === 'service_offers' ) {
					$section_title = $section['title'];
					break 2;
				}
			}
		}
	}
}

if (!empty($service_offers)) { ?>

<div class="single-details service-offer">
	<h3 class="title"><?php echo esc_html( $section_title ?: __( 'Offered Services', 'cldoctor' ) ); ?></h3>
    <div class="rt-accordion-wrapper">
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="row">
            <div class="col-xl-6">
	            <?php
	            $count = 0;
	            foreach ($service_offers as $offer) {
	            $count++;

                if ($count % 2 !== 0) { // Odd items
	                $title = $offer['service_title'] ?? '';
	                $desc = $offer['service_information'] ?? '';
	                $show = ($count === 1) ? 'show' : '';
	                $expand = ($count === 1) ? 'true' : 'false';
	                $collapsed = ($count === 1) ? '' : 'collapsed';
	            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading<?php echo esc_attr($count); ?>">
                        <button class="accordion-button <?php echo esc_attr($collapsed); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo esc_attr($count); ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo esc_attr($count); ?>">
                            <div class="icon circle-radius d-flex justify-content-center align-items-center">
                                <i class="icon-medicine"></i>
                            </div>
	                        <?php echo esc_html($title); ?>
                        </button>
                    </h2>
                    <div id="flush-collapse<?php echo esc_attr($count); ?>" class="accordion-collapse collapse <?php echo esc_attr($show); ?>" aria-labelledby="flush-heading<?php echo esc_attr($count); ?>" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
	                        <?php echo wp_kses_post($desc); ?>
                        </div>
                    </div>
                </div>
		            <?php
	            }
	            }
	            ?>
            </div>
            <div class="col-xl-6">
	            <?php
	            $count = 0;
	            foreach ($service_offers as $offer) {
	            $count++;

	            if ($count % 2 === 0) { // Even items
	            $title = $offer['service_title'] ?? '';
	            $desc = $offer['service_information'] ?? '';
	            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading<?php echo esc_attr($count); ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo esc_attr($count); ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo esc_attr($count); ?>">
                            <div class="icon circle-radius d-flex justify-content-center align-items-center">
                                <i class="icon-medicine"></i>
                            </div>
	                        <?php echo esc_html($title); ?>
                        </button>
                    </h2>
                    <div id="flush-collapse<?php echo esc_attr($count); ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo esc_attr($count); ?>" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
	                        <?php echo wp_kses_post($desc); ?>
                        </div>
                    </div>
                </div>
                <?php
	                }
	            }
	            ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php }