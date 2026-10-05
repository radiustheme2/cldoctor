<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RadiusTheme\Cldoctor\Options;
use RadiusTheme\Cldoctor\Listing_Functions;

?>
</div><!-- #content -->
<?php
    $footer_style = Options::$footer_style ? Options::$footer_style : 1;
    get_template_part( 'template-parts/footer/footer', $footer_style ); 
    if ( class_exists( 'Rtcl' ) && class_exists( 'RtclPro' ) ) {
        if (Functions::is_enable_favourite()){ 
            Listing_Functions::logout_user_favourite(); 
        }
    }
?>

</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>