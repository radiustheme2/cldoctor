<?php
/**
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Models\Listing;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use RadiusTheme\Cldoctor\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks;
use RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingFrontendHooks as RtrbFrontendHooks;

?>

<div class="rtcl rtcl-listings-sc-wrapper rtcl-elementor-widget">
	<div class="rtcl-listings-wrapper">
		<?php
		$class  = '';
		$class .= ! empty( $view ) ? 'rtcl-' . $view . '-view ' : 'rtcl-list-view ';
		$class .= ! empty( $style ) ? 'rtcl-' . $style . '-view ' : 'rtcl-style-1-view ';
		$columns_desktop = ! empty( $instance['rtcl_listings_list_column'] ) ? $instance['rtcl_listings_list_column'] : '2';
		$columns_ex_tablet = ! empty( $instance['rtcl_listings_list_column_tablet_extra'] ) ? $instance['rtcl_listings_list_column_tablet_extra'] : '1';
		$columns_tablet = ! empty( $instance['rtcl_listings_list_column_tablet'] ) ? $instance['rtcl_listings_list_column_tablet'] : '1';
		$columns_mobile = ! empty( $instance['rtcl_listings_list_column_mobile'] ) ? $instance['rtcl_listings_list_column_mobile'] : '1';
		$class.= " row row-cols-xl-{$columns_desktop} row-cols-lg-{$columns_ex_tablet} row-cols-md-{$columns_tablet} row-cols-sm-{$columns_mobile} row-cols-1";

		?>
		<div class="rtcl-listings <?php echo esc_attr( $class ); ?>">
			<?php

			while ( $the_loops->have_posts() ) :
				$the_loops->the_post();
				$_id                 = get_the_ID();
				$post_meta           = get_post_meta( $_id );
				$listing             = new Listing( $_id );
				$listing_title       = null;
				$listing_meta        = null;
				$listing_description = null;
				$img                 = null;
				$labels              = null;
				$time                = null;
				$location            = null;
				$category            = null;
				$price               = null;
				$img_position_class  = '';
				$types               = null;
				$phone               = get_post_meta( $_id, 'phone', true );
				$custom_field 		= null;

				$general_listing_label = Functions::get_option( 'rtcl_general_listing_label_settings' );
				$phone_label = !empty( $general_listing_label['phone_button_label'] ) ? $general_listing_label['phone_button_label'] : 'Call For Details';
				$button_label = esc_html__( 'Listing Details', 'cldoctor' );
				$phone = get_post_meta( $listing->get_id(), 'phone', true );
				$phone_url = str_replace(' ', '', $phone);

				$doctor_meet_time = get_post_meta( $listing->get_id(), 'doctor_meet_time', true );
				$hospital_time = get_post_meta( $listing->get_id(), 'hospital_time', true );
				$doctor_designation = get_post_meta( $listing->get_id(), 'doctor_designation', true );
				$hospital_slogan = get_post_meta( $listing->get_id(), 'hospital_slogan', true );
				?>
				<div class="col">
					<div <?php Functions::listing_class( [ 'rtcl-widget-listing-item', $img_position_class ] ); ?>>
						<!-- Thumbnail Image Box -->
						<div class="item-img bg--gradient-50">
							<div class="cldoctor-listing-actions-buttons">
								<?php 
									if ( $listing->has_category() && $instance['rtcl_show_category'] ):
										Listing_Functions::cldoctor_listing_categories();
									endif; 
								?>
								<?php 
									if ( $instance['rtcl_show_types'] && $listing->get_ad_type() ) {
									$listing_type = Listing_Functions::get_listing_type( $listing );
									?>
										<span class="listing-type-badge">
											<?php echo wp_kses_post( sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'cldoctor' ) ), $listing_type['label'] ) ); ?>
										</span>
								<?php } ?>
								
								<?php 
									if ( $instance['rtcl_show_labels'] ) {
										$labels = $listing->badges() ? $listing->badges() : '';
										echo wp_kses_post( $labels ); 
									}
								?>
							</div>
							<?php 
							if ( $instance['rtcl_show_image'] ) {
								$image_size    = $instance['rtcl_thumb_image_size'];
								$the_thumbnail = $listing->get_the_thumbnail( $image_size );
								if ( $the_thumbnail ) { ?>
							<div class='listing-thumb'>
								<?php 
									if ( rtcl()->has_pro() ) {
										TemplateHooks::sold_out_banner();
									}
								?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php echo wp_kses_post($the_thumbnail); ?></a>
							</div>
							<?php }
								} ?>
							<ul class="meta-tags">
								<?php if ( rtcl()->has_pro() ) { 
									if ( ! empty( $instance['rtcl_show_compare'] ) ){
									?>
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
									<?php }
									}
									if ( rtcl()->has_pro() ) {
										if ( ! empty( $instance['rtcl_show_quick_view'] ) ) :
											?>
											<li class="rtin-el-button">
												<a class="rtcl-quick-view" href="#" title="<?php esc_attr_e( 'Quick View', 'cldoctor' ); ?>"  data-listing_id="<?php echo absint( $_id ); ?>">
													<i class="icon-pl-eye"></i>
												</a>
											</li>
											<?php
										endif;
									} 
								?>
							</ul>
						</div>
						
						<!-- All Meta Info Box -->
						<div class="item-content">
							<div class="item-content-left">
								<div class="title-excerpt-box">
									<div class="title-price-box">
										<?php if ( $instance['rtcl_show_title'] ) { ?>
											<h3 class="listing-title rtcl-listing-title"><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title() ?></a></h3>
										<?php } ?>
										<?php if (!empty( $doctor_designation && $instance['rtcl_show_designation'] )) { ?>
										<div class="doctor-designation">
											<?php echo esc_html( $doctor_designation ); ?>
										</div>
										<?php } ?>
										<?php if (!empty( $hospital_slogan && $instance['rtcl_show_designation'] )) { ?>
										<div class="doctor-designation">
											<?php echo esc_html( $hospital_slogan ); ?>
										</div>
										<?php } ?>
									</div>
								</div>							
								<?php if ( $instance['rtcl_show_description'] ) { ?>
									<div class="rtcl-short-description">
										<?php 
											if (!empty($instance['rtcl_content_limit'])) {
												Listing_Functions::cldoctor_listing_excerpt($instance['rtcl_content_limit']); 
											} else {
												Listing_Functions::cldoctor_listing_excerpt($instance['rtcl_content_limit']); 
											}
										?>
									</div>
								<?php } ?>
								<div class="all-meta-info-box">
									<ul class="rtcl-listing-meta-data">
										<?php if ( !empty( $phone ) ) { ?>
											<li class="meta-phone">
												<a href="tel:<?php echo esc_attr( $phone_url ); ?>"><i class="icon-phone-2"></i> <?php echo esc_html( $phone ); ?></a>
											</li>
										<?php } ?>
										<?php
										if ( $instance['rtcl_show_location'] ) :
											?>
											<li class="rt-location">
												<i class="icon-location-pointer"></i>
												<?php $listing->the_locations( true, true ); ?>
											</li>
										<?php endif; ?>
									</ul>
								</div>
								<?php if (!empty($instance['rtcl_show_custom_fields'])){ ?>
									<div class="custom-flelds-box">
										<?php TemplateHooks::loop_item_listable_fields(); ?>
									</div>
								<?php } ?>

                                <div class="appointment-box">
                                    <?php if ($instance['rtcl_show_appointment']) {
                                        if ( class_exists( RtrbFrontendHooks::class ) ) {
                                            rtrb_cl_booking_button();
                                        }
                                    } else { ?>
                                        <a href="<?php the_permalink(); ?>"><?php echo esc_html( $button_label ); ?></a>
                                    <?php } ?>
                                </div>

							</div>
							<div class="item-content-right">
								<div class="all-meta-info-box">
									<ul class="rtcl-listing-meta-data">
										<?php if ( !empty($doctor_meet_time && $instance['rtcl_show_meet_time']) ): ?>
											<li class="meet-time">
												<i class="icon-clock"></i>
												<?php echo esc_html( $doctor_meet_time ); ?>
											</li>
										<?php endif; ?>
										<?php if ( !empty($hospital_time && $instance['rtcl_show_meet_time']) ): ?>
											<li class="meet-time">
												<i class="icon-clock"></i>
												<?php echo esc_html( $hospital_time ); ?>
											</li>
										<?php endif; ?>
										<?php if ( $instance['rtcl_show_user'] ) : ?>
											<li class="author">
												<i class="icon-ei_user"></i>
												<?php esc_html_e( 'by ', 'cldoctor' ); ?>
												<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
													<a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
												<?php else : ?>
													<?php $listing->the_author(); ?>
												<?php endif; ?>
												<?php do_action( 'rtcl_after_author_meta', $listing->get_owner_id() ); ?>
											</li>
										<?php endif; ?>
										<?php if ( $instance['rtcl_show_views'] ) : ?>
											<li class="rt-views">
												<i class="icon-eye"></i>
												<?php /* translators: %s is the number of views */
												echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'cldoctor' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
											</li>
										<?php endif; ?>

										<?php if ( $instance['rtcl_show_date'] ) { ?>
											<li class="date-time">
												<i class="icon-clock"></i>
												<?php $listing->the_time(); ?>
											</li>
										<?php } ?>

										<?php if ( $instance['rtcl_show_price'] ) { ?>
											<li class="meta-price">
												<?php echo wp_kses_post($listing->get_price_html()); ?>
											</li>
										<?php } ?>
									</ul>
								</div>			
							</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		<?php if ( ! empty( $instance['rtcl_listing_pagination'] ) ) { ?>
			<?php Pagination::pagination( $the_loops, true ); ?>
		<?php } ?>
	</div>
</div>
