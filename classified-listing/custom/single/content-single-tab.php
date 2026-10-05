<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Options;
use RtclMarketplace\Hooks\ActionHooks;
use RadiusTheme\Cldoctor\Listing_Functions;
use RtclBooking\Helpers\Functions as BookingFunctions;
use RtclClaimListing\Helpers\Functions as ClaimFunctions;

defined('ABSPATH') || exit;

global $listing;
global $current_user;
$listing_id = $listing->get_id();

$add_type = $listing->get_ad_type();

?>

<div class="tabs-wrapper">
     <div class="doctor-details-tabs">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                 <button class="nav-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="false">
                    <i class="icon-doctor"></i>
                    <?php echo esc_html( $add_type ); ?>
                    <?php esc_html_e( 'Details', 'cldoctor' ); ?>
                 </button>
              </li>
              <?php if (Functions::get_option_item('rtcl_single_listing_settings', 'enable_review_rating', false, 'checkbox')) { ?>
              <li class="nav-item" role="presentation">
                 <button class="nav-link" id="pills-feedback-tab" data-bs-toggle="pill" data-bs-target="#pills-feedback" type="button" role="tab" aria-controls="pills-feedback" aria-selected="false">
                    <i class="icon-nimbus_marketing"></i>
                    <?php esc_html_e( 'Feedback', 'cldoctor' ); ?>
                 </button>
              </li>
              <?php } ?>
              <?php
                if (Options::$options['listing_details_article_tab']){
                 $args = array(
                    'author'        =>  $owner_id = $listing->get_owner_id(),
                    'orderby'       =>  'post_date',
                    'order'         =>  'ASC',
                    'posts_per_page' => -1 // no limit
                 );
                 $the_query = new WP_Query( $args );
                 if ( $the_query->have_posts() ) :
              ?>
              <li class="nav-item" role="presentation">
                 <button class="nav-link" id="pills-articles-tab" data-bs-toggle="pill" data-bs-target="#pills-articles" type="button" role="tab" aria-controls="pills-articles" aria-selected="false">
                    <i class="icon-article"></i>
                    <?php esc_html_e( 'Articles', 'cldoctor' ); ?>
                 </button>
              </li>
              <?php endif; } ?>
        </ul>
     </div>

     <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">
           <div class="listing-details-content-wrapper details-tab-content">
              <div class="about-hospital single-details">
                 <h3 class="title">
                    <?php echo esc_html( $add_type ); ?>
                    <?php esc_html_e( 'Information', 'cldoctor' ); ?>
                 </h3>
                 <?php $listing->the_content(); ?>
              </div>
               <?php
                Helper::get_custom_listing_template( 'service-offer' );
                Helper::get_custom_listing_template( 'form-builder-cfg' );
               ?>
              <?php
                 $feature_img = '';
                 $images = $listing->get_images();
                 unset($images[0]);
                 if (empty($images)) {
                       $feature_img = 'feature-img-not-set';
                 }
                 if (!empty($images)) {
              ?>
                 <div class="single-details-gallery single-details">
                    <h3 class="title">
                       <?php esc_html_e( 'Gallery', 'cldoctor' ); ?>
                    </h3>
                    <div class="gallery-images d-flex gap-3 flex-wrap">
                       <?php do_action('cldoctor_listing_galley'); ?>
                    </div>
                 </div>
              <?php } ?>
           </div>
        </div>
        <?php if (Functions::get_option_item('rtcl_single_listing_settings', 'enable_review_rating', false, 'checkbox')) { ?>
           <div class="tab-pane fade" id="pills-feedback" role="tabpanel" aria-labelledby="pills-feedback-tab">
              <div class="listing-details-content-wrapper feedback-tab-content single-details">
                 <h3 class="doctor-name">
                    <?php esc_html_e( 'Patient Feedback', 'cldoctor' ); ?>
                 </h3>
                 <div class="rtcl-single-listing-details review-box">
                    <div class="rtcl-main-content-wrapper">
                       <!-- Review  -->
                       <?php do_action( 'rtcl_single_listing_review' ) ?>
                    </div>
                 </div>
              </div>
           </div>
        <?php } ?>
        <?php
        if (Options::$options['listing_details_article_tab']){
           if ( $the_query->have_posts() ):
        ?>
           <div class="tab-pane fade" id="pills-articles" role="tabpanel" aria-labelledby="pills-articles-tab">
              <div class="listing-details-content-wrapper article-tab-content">
                 <h3 class="doctor-name">
                    <?php esc_html_e( 'Articles', 'cldoctor' ); ?>
                 </h3>
                 <div class="blog-thumb-wrapper">
                    <?php while ( $the_query->have_posts() ) : $the_query->the_post();
                       $comments_number = get_comments_number();
                       /* translators: %s is the number of comments */
                       $comments_text   = sprintf( _n( '%s Comment', '%s Comments', $comments_number, 'cldoctor' ), number_format_i18n( $comments_number ) );
                    ?>
                       <div class="rt-card rt-card--style-5 d-flex align-items-center flex-column flex-sm-row">
                          <div class="item-img align-self-start">
                             <?php the_post_thumbnail( 'cldoctor-size1' ); ?>
                          </div>

                          <div class="item-content">
                             <ul class="meta-list d-flex align-items-center flex-wrap mb-2">
                                <li>
                                   <i class="icon-clock"></i>
                                   <?php the_time( get_option( 'date_format' ) ); ?>
                                </li>
                             </ul>

                             <h3 class="title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                             </h3>

                             <ul class="meta-list d-flex align-items-center flex-wrap mb-0">
                                <li class="meta-author">
                                   <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"class="fn">
                                      <i class="icon-user-3"></i>
                                      <?php the_author(); ?>
                                   </a>
                                </li>
                                <li>
                                   <a href="<?php the_permalink(); ?>">
                                      <i class="icon-comment-2"></i>
                                      <?php echo esc_html( $comments_text ); ?>
                                   </a>
                                </li>
                             </ul>
                          </div>
                       </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                 </div>
              </div>
           </div>
        <?php endif; } ?>
     </div>
</div>
