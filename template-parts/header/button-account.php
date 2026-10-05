<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 use RadiusTheme\Cldoctor\Options;
 use Rtcl\Helpers\Link;

$login_icon_title = is_user_logged_in() ? Options::$options['header_login_text'] : Options::$options['header_logout_text'];

?>
<?php if ( class_exists( 'Rtcl' ) && Options::$options['header_login_icon'] ): ?>
    <a class="header-login-icon" data-toggle="tooltip" title="<?php echo esc_html( $login_icon_title ); ?>" href="<?php echo esc_url( Link::get_my_account_page_link() ); ?>">
        <?php if (is_user_logged_in()){ ?>
        <i class="icon-ei_user"></i>
        <?php } else { ?>
            <i class="icon-ei_user"></i>
        <?php } echo esc_html( $login_icon_title ); ?>
    </a>
<?php endif;
