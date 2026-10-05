<?php
/**
 * Listing meta
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 use Rtcl\Helpers\Functions;
 use RadiusTheme\Cldoctor\Listing_Functions;

if ( ! $listing ) {
	global $listing;
}

if ( empty( $listing ) ) {
	return;
}

$rtcl_view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! empty( $rtcl_view ) && in_array( $rtcl_view, [ 'grid', 'list' ], true ) ) {
	$view = $rtcl_view;
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
$show_doctor_meet_time = ! empty( $archive_settings['display_options'] ) && in_array( 'show_doctor_meet_time', $archive_settings['display_options'] );
$show_hospital_time = ! empty( $archive_settings['display_options'] ) && in_array( 'show_hospital_time', $archive_settings['display_options'] );
$doctor_meet_time = get_post_meta( $listing->get_id(), 'doctor_meet_time', true );
$hospital_time = get_post_meta( $listing->get_id(), 'hospital_time', true );

if ($view == 'list') {
?>
	<ul class="rtcl-listing-meta-data">
		<?php if ( !empty($doctor_meet_time && $show_doctor_meet_time) ):
			?>
			<li class="rt-availability">
				<i class="icon-clock"></i>
                <?php echo esc_html( $doctor_meet_time ); ?>
			</li>
		<?php endif; ?>
		<?php if ( !empty($hospital_time && $show_hospital_time) ):
			?>
			<li class="rt-availability">
				<i class="icon-clock"></i>
                <div class="availability">
				    <?php echo esc_html( $hospital_time ); ?>
                </div>
			</li>
		<?php endif; ?>
		<?php if ( $listing->can_show_user() ) : ?>
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
		if ( $listing->has_location() ) :
			?>
			<li class="rt-location">
				<i class="icon-location-pointer"></i>
				<?php $listing->the_locations( true, true ); ?>
			</li>
		<?php endif; ?>
		<?php if ( $listing->can_show_views() ) : ?>
			<li class="rt-views">
				<i class="icon-eye"></i>
				<?php /* translators: %s is the number of views */
			echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'cldoctor' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
			</li>
		<?php endif; ?>
		<?php if ( $listing->can_show_category() ) : 
			if ( $view == 'grid' ) {
		?>
			<li class="rt-category">
				<i class="fa-solid fa-tags"></i>
				<?php Listing_Functions::cldoctor_listing_categories(); ?>
			</li>
		<?php } endif; ?>
		<?php if ( $listing->can_show_date() ) : ?>
			<li class="rt-date-time">
				<i class="icon-clock"></i>
				<?php $listing->the_time(); ?>
			</li>
		<?php endif; ?>
	</ul>
<?php } else { 
	
	if ( ! $listing->can_show_user() && ! $listing->can_show_location() && ! $listing->can_show_views() && ! $listing->can_show_category() && $show_doctor_meet_time ) {
		return;
	}
	
	?>
	<ul class="rtcl-listing-meta-data">
		<?php if ( !empty($doctor_meet_time && $show_doctor_meet_time) ):
			?>
            <li class="rt-availability">
				<i class="icon-clock"></i>
				<?php echo esc_html( $doctor_meet_time ); ?>
			</li>
		<?php endif; ?>
		<?php if ( !empty($hospital_time && $show_hospital_time) ):
			?>
            <li class="rt-availability">
				<i class="icon-clock"></i>
                <div class="availability">
                    <span class="pulse"></span>
		            <?php echo esc_html( $hospital_time ); ?>
                </div>
			</li>
		<?php endif; ?>
		<?php if ( $listing->can_show_user() ) : ?>
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
		if ( $listing->has_location() && $listing->can_show_location() ) :
			?>
			<li class="rt-location">
				<i class="icon-location-pointer"></i>
				<?php $listing->the_locations( true, true ); ?>
			</li>
		<?php endif; ?>

		<?php if ( $listing->can_show_views() ) : ?>
			<li class="rt-views">
				<i class="icon-eye"></i>
				<?php /* translators: %s is the number of views */
			echo esc_html( sprintf( _n( '%s view', '%s views', $listing->get_view_counts(), 'cldoctor' ), number_format_i18n( $listing->get_view_counts() ) ) ); ?>
			</li>
		<?php endif; ?>
		
		<?php if ( $listing->can_show_date() ) : ?>
			<li class="rt-date-time">
				<i class="icon-clock"></i>
				<?php $listing->the_time(); ?>
			</li>
		<?php endif; ?>
	</ul>
<?php } ?>