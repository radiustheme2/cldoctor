<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Options;

$socials = Helper::socials();
$widget_areas = Options::$options['f2_widgets_area'];

?>

<!--=====================================-->
<!--=        Footer 1 Area Start        =-->
<!--=====================================-->
<footer class="footer footer-style-2">
    <?php if (!empty(Options::$options['f2_newsletter_title'] || Options::$options['f2_newsletter_sub_title'] || Options::$options['f2_newsletter_shortcode'] )) { ?>
        <div class="container">
            <div class="subscription-area">
                <div class="row justify-content-between align-items-center">
                    <?php if (!empty(Options::$options['f2_newsletter_title'] || Options::$options['f2_newsletter_sub_title'] )) { ?>
                    <div class="col-md-12 col-xl-5">
                        <div class="title-wrapper mb-4 mb-xl-0">
                            <h2 class="section-title wow animate__fadeInUp" data-wow-duration="1200ms" data-wow-delay="300ms">
                            <?php echo esc_html( Options::$options['f2_newsletter_title'] ); ?>
                            </h2>
                            <p class="subtitle wow animate__fadeInUp" data-wow-duration="1200ms" data-wow-delay="400ms">
                                <?php echo esc_html( Options::$options['f2_newsletter_sub_title'] ); ?>
                            </p>
                        </div>
                    </div>
                    <?php } if (!empty( Options::$options['f2_newsletter_shortcode'] )) { ?>
                    <div class="col-md-12 col-xl-5 wow animate__fadeInUp" data-wow-duration="1200ms" data-wow-delay="500ms">
                        <?php echo do_shortcode( Options::$options['f2_newsletter_shortcode'] ); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ( is_active_sidebar( 'footer-widget-2-1' ) || is_active_sidebar( 'footer-widget-2-2' ) || is_active_sidebar( 'footer-widget-2-3' ) || is_active_sidebar( 'footer-widget-2-4' ) || is_active_sidebar( 'footer-widget-2-5' ) || is_active_sidebar( 'footer-widget-2-6' ) ) { ?>
    <div class="footer-top">
        <div class="container">
            <div class="row justify-content-between footer-widget-area">
                <?php for ( $i = 1; $i <= $widget_areas; $i++ ) { ?>
                <div class="col-lg-<?php echo esc_attr(Options::$options['f2_area'.$i.'_column']); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer-widget-2-'.esc_attr($i) ); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="footer-bottom">
        <div class="container">
            <div class="copyright-area">
                <div class="copyright-text">
                    <p class="footer-copyright mb-0"><?php echo wp_kses_post( Options::$options['copyright_text'] ); ?></p>
                </div>
				<?php if ( $socials ): ?>
					<div class="social-btn">
						<ul class="social-list d-flex align-items-center">
                            <li class="social-label"><?php esc_html_e( 'Follow Social:', 'cldoctor' ); ?></li>
							<?php foreach ( $socials as $social ): ?>
							<li class="social-item">
								<a href="<?php echo esc_url( $social['url'] ); ?>" class="<?php echo esc_attr( $social['class'] ); ?> circle-radius d-flex justify-content-center align-items-center" target="_blank">
									<i class="<?php echo esc_attr( $social['icon'] ); ?>"></i>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</footer>
<!--=====================================-->
<!--=          Footer Area End          =-->
<!--=====================================-->    