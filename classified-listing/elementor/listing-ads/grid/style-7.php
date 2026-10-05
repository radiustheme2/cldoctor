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
use RadiusTheme\Cldoctor\Helper;
use Rtrs\Modules\Review\Helpers\ReviewFns;
use RadiusTheme\Cldoctor\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks;
use RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingFrontendHooks as RtrbFrontendHooks;


$filter_control = $instance['rtcl_enable_filter'];

if ($filter_control) {
	$isotopeWrapper = ' isotope-items'; 
	$container = ' featuredContainer'; 
} else {
	$container = '';
	$isotopeWrapper = ''; 
}

$cldoctor_excerpt_length = !empty( $instance['rtcl_content_limit'] ) ? $instance['rtcl_content_limit'] : 20;

?>

<div class="rtcl rtcl-listings-sc-wrapper rtcl-elementor-widget <?php echo esc_attr($isotopeWrapper); ?>">
	<?php if ($filter_control) { ?>
	<div class="isotope-classes-tab d-flex justify-content-center flex-wrap">
		<a href="#" class="current nav-item" data-filter="*"><?php esc_html_e( 'All', 'cldoctor' ); ?></a>
		<?php 
			$term_ids = $instance['rtcl_listings_by_categories'];
			if ( $term_ids ) {
				foreach ( $term_ids as $term_id ) {
					$term = get_term( $term_id, 'rtcl_category' );
					if ( empty( $term->slug ) ) {
						continue;
					}
					echo wp_kses_post( "<a class=\"nav-item\" data-filter=\".rtcl_category-" . esc_attr( $term->slug ) . "\">" . esc_html( $term->name ) . "</a>" );
				}
			}
		?>
	</div>
	<?php } ?>
	<div class="rtcl-listings-wrapper">
		<?php
		$class  = '';
		$class .= ! empty( $view ) ? 'rtcl-' . $view . '-view ' : 'rtcl-list-view ';
		$class .= ! empty( $style ) ? 'rtcl-' . $style . '-view ' : 'rtcl-style-1-view ';

		$class .= ! empty( $instance['rtcl_listings_column'] ) ? 'columns-' . $instance['rtcl_listings_column'] . ' ' : ' columns-1';
		$class .= ! empty( $instance['rtcl_listings_column_tablet'] ) ? 'tab-columns-' . $instance['rtcl_listings_column_tablet'] . ' ' : ' tab-columns-2';
		$class .= ! empty( $instance['rtcl_listings_column_mobile'] ) ? 'mobile-columns-' . $instance['rtcl_listings_column_mobile'] . ' ' : ' mobile-columns-2';
		$class .= $container;

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
				$listing_button = !empty( $generalSettings['listing_booking_button'] ) ? $generalSettings['listing_booking_button'] : 'call';

				$doctor_meet_time = get_post_meta( $listing->get_id(), 'doctor_meet_time', true );
				$hospital_time = get_post_meta( $listing->get_id(), 'hospital_time', true );
				$doctor_designation = get_post_meta( $listing->get_id(), 'doctor_designation', true );
				$hospital_slogan = get_post_meta( $listing->get_id(), 'hospital_slogan', true );

				if ( class_exists( ReviewFns::class ) ) {
					$average_rating = ReviewFns::getAvgRatings( $_id );
					$rating_count   = ReviewFns::getTotalRatings( $_id );
				} else {
					$average_rating = $listing->get_average_rating();
					$rating_count   = $listing->get_rating_count();
				}

				?>

				<div <?php Functions::listing_class( [ 'rtcl-widget-listing-item', 'listing-item', $img_position_class ] ); ?>>
					<!-- Thumbnail Image Box -->
					<div class="item-img bg--gradient-50">
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
							<a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php echo wp_kses_post( $the_thumbnail ); ?></a>
						</div>
						<?php }
                        } ?>
					</div>
					
					<!-- All Meta Info Box -->
					<div class="item-content">
						<div class="style-7-item-header">
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
										echo wp_kses_post($labels); 
									}
								?>
							</div>
							<ul class="meta-tags">
								<?php if ( $instance['rtcl_show_favourites'] ) { ?>
									<?php if ( is_user_logged_in() ) { ?>
										<li class="meta-item meta-favourite">
											<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
										</li>
									<?php } else { ?>
										<li class="meta-item meta-favourite">
											<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
										</li>
									<?php } ?>
								<?php } if ( rtcl()->has_pro() ) { 
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
						<div class="title-excerpt-box">
							<?php if ( $instance['rtcl_show_title'] ) { ?>
								<h3 class="listing-title rtcl-listing-title"><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title() ?></a></h3>
							<?php } if (!empty( $doctor_designation && $instance['rtcl_show_designation'] )) { ?>
							<div class="doctor-designation">
								<?php echo esc_html( $doctor_designation ); ?>
							</div>
							<?php } if (!empty( $hospital_slogan && $instance['rtcl_show_designation'] )) { ?>
								<div class="doctor-designation">
									<?php echo esc_html( $hospital_slogan ); ?>
								</div>
							<?php }
								if ( $instance['rtcl_show_description'] ) {
									$excerpt = Helper::cldoctor_excerpt($cldoctor_excerpt_length);
								?>
									<div class="rtcl-short-description"><?php echo wp_kses_post( wpautop( $excerpt ) ); ?></div>
								<?php }
							?>
						</div>
						<?php if ( ! empty( $instance['rtcl_show_reviews'] ) && $rating_count > 0 ) : ?>
						    <div class="item-rating">
							<?php
							echo wp_kses_post( Functions::get_rating_html( $average_rating, $rating_count ) );
							echo wp_kses_post( apply_filters( 'cldoctor_rating_count_format', '<span class="rating-review-count">(' . esc_html( $rating_count ) . ' ' . esc_html__( 'reviews', 'cldoctor' ) . ')</span>' ) );
							?>
						    </div>
						<?php endif; ?>

						<?php if ( class_exists( 'RtclPro' ) && !empty($instance['rtcl_show_custom_fields'])) { ?>
							<div class="custom-flelds-box">
								<?php TemplateHooks::loop_item_listable_fields(); ?> 
							</div>
						<?php } ?>
						<?php if ( !empty ( $instance['rtcl_show_user'] || $instance['rtcl_show_location'] || $instance['rtcl_show_views'] || $instance['rtcl_show_meet_time'] )): ?>
					    <hr class="listing-card-divider">
						<div class="all-meta-info-box">
							<ul class="rtcl-listing-meta-data">
								<?php
								if ( !empty($doctor_meet_time && $instance['rtcl_show_meet_time']) ) :
									$is_247 = ( false !== stripos( $doctor_meet_time, '24/7' ) );
									?>
									<li class="rt-location">
										<i class="icon-clock"></i>
										<?php if ( $is_247 ) : ?>
											<span class="badge-24-available"><?php echo esc_html( $doctor_meet_time ); ?></span>
										<?php else : ?>
											<?php echo esc_html( $doctor_meet_time ); ?>
										<?php endif; ?>
									</li>
								<?php endif; ?>
								<?php
									if ( !empty($hospital_time && $instance['rtcl_show_meet_time']) ) :
										$is_247_h = ( false !== stripos( $hospital_time, '24/7' ) );
										?>
										<li class="rt-location">
											<i class="icon-clock"></i>
											<?php if ( $is_247_h ) : ?>
												<span class="badge-24-available"><?php echo esc_html( $hospital_time ); ?></span>
											<?php else : ?>
												<?php echo esc_html( $hospital_time ); ?>
											<?php endif; ?>
										</li>
									<?php endif; ?>
								<?php if ( $instance['rtcl_show_user'] ) : ?>
									<li class="author">
										<i class="icon-user-2"></i>
										<?php esc_html_e( 'by ', 'cldoctor' ); ?>
										<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
											<a href="<?php echo esc_url( $listing->get_the_author_url() ); ?>"><?php $listing->the_author(); ?></a>
										<?php else : ?>
											<?php $listing->the_author(); ?>
										<?php endif; ?>
										<?php do_action( 'rtcl_after_author_meta', $listing->get_owner_id() ); ?>
									</li>
								<?php endif; ?>
								<?php
								if ( $instance['rtcl_show_location'] ) :
									?>
									<li class="rt-location">
										<i class="icon-location-pointer"></i>
										<?php $listing->the_locations( true, true ); ?>
									</li>
								<?php endif; ?>
								<?php if ( $instance['rtcl_show_views'] ) : ?>
									<li class="rt-views">
										<i class="icon-eye"></i>
										<?php /* translators: %s is the number of views */
										echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'cldoctor' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
									</li>
								<?php endif; ?>
							</ul>
						</div>
						<?php endif; ?>
						<div class="price-time-box">
							<?php 
								if ( $instance['rtcl_show_price'] ) {
									echo wp_kses_post( $listing->get_price_html() ); 
								}
							?>
							<?php if ( $instance['rtcl_show_date'] ) { ?>
								<div class="date-time">
									<i class="icon-clock"></i>
									<?php $listing->the_time(); ?>
								</div>
							<?php } ?>
						</div>
					</div>
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
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		<?php if ( ! empty( $instance['rtcl_listing_pagination'] ) ) { ?>
			<?php Pagination::pagination( $the_loops, true ); ?>
		<?php } ?>
	</div>
</div>

<?php
if ( $filter_control ) {
	if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		?>
		<script>jQuery( '.featuredContainer' ).isotope();</script>
		<?php
	}
}