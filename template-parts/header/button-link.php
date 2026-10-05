<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Link;
use RadiusTheme\Cldoctor\Options;

if (!empty(Options::$options['header_btn_url'])){
	$button_url = Options::$options['header_btn_url'];
} else {
	if ( class_exists( 'Rtcl' ) ) {
		$button_url = Link::get_listing_form_page_link();
	}
}
?>
<?php if ( Options::$options['header_btn'] && Options::$options['header_btn_txt'] ): ?>
    <div class="header-btn-area">
        <a class="button-style-1 btn-anim" href="<?php echo esc_url( $button_url ); ?>">
            <div class="icon d-flex justify-content-center align-items-center">
                <i class="icon-plus"></i>
            </div>
            <span><?php echo esc_html( Options::$options['header_btn_txt'] ); ?></span>
        </a>
    </div>
<?php endif; ?>

