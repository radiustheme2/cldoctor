<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Options;
use Rtcl\Helpers\Functions;

defined( 'ABSPATH' ) || exit;

get_header( 'listing' );
?>
<div class="listing-archive-wrapper content-area">
	<div class="container">

		<?php
         /**
          * Hook: rtcl_before_main_content.
          *
          * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
          */
         do_action( 'rtcl_before_main_content' );

          /**
          * Hook: rtcl_before_listing_loop.
            *
            * @hooked TemplateHooks::output_all_notices() - 10
            * @hooked TemplateHooks::listings_actions - 20
            *
            */
         do_action( 'rtcl_before_listing_loop' );
      ?>

		<div class="row">

			<?php if ( Options::$layout == 'left-sidebar' ) { ?>
				<div class="listing-archive-sidebar <?php Helper::the_listing_sidebar_class(); ?>">
					<?php 
						if ( Helper::has_sidebar() ) {
							/**
							 * Hook: rtcl_sidebar.
							 *
							 * @hooked rtcl_get_sidebar - 10
							 */
							do_action( 'rtcl_sidebar' );
						}
					?>
				</div>
			<?php } ?>	
		
			<div class="<?php Helper::the_listing_layout_class(); ?>">
				<?php
				if ( rtcl()->wp_query()->have_posts() ) {

					Functions::listing_loop_start();

					/**
					 * Prepend listings
					 */
					do_action( 'rtcl_listing_loop_prepend_data' );

					while ( rtcl()->wp_query()->have_posts() ) : rtcl()->wp_query()->the_post();

						/**
						 * Hook: rtcl_listing_loop.
						 */
						do_action( 'rtcl_listing_loop' );

						Functions::get_template_part( 'content', 'listing' );

					endwhile;

					Functions::listing_loop_end();

				} else {

					/**
					 * Prepend listings
					 */
					ob_start();
					do_action( 'rtcl_listing_loop_prepend_data' );
					$listing_loop_prepend_data = ob_get_clean();
					if ( $listing_loop_prepend_data ) {
						Functions::listing_loop_start();
						echo wp_kses_post( $listing_loop_prepend_data );
						Functions::listing_loop_end();
					}

					/**
					 * Hook: rtl_no_listings_found.
					 *
					 * @hooked no_listings_found - 10
					 */
					do_action( 'rtcl_no_listings_found' );
				}
				?>
				<?php 
					/**
					 * Hook: rtcl_after_listing_loop.
					 *
					 * @hooked TemplateHook::pagination() - 10
					 */
					do_action( 'rtcl_after_listing_loop' );
				?>
			</div>

			<?php if ( Options::$layout == 'right-sidebar' ) { ?>
				<div class="listing-archive-sidebar <?php Helper::the_listing_sidebar_class(); ?>">
					<?php 
						if ( Helper::has_sidebar() ) {
							/**
							 * Hook: rtcl_sidebar.
							 *
							 * @hooked rtcl_get_sidebar - 10
							 */
							do_action( 'rtcl_sidebar' );
						}
					?>
				</div>
			<?php } ?>	
		</div>
		<?php 
			/**
			 * Hook: rtcl_after_main_content.
			 *
			 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'rtcl_after_main_content' );
		?>
	</div>
</div>

<?php
get_footer( 'listing' );