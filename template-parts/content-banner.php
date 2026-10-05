<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RadiusTheme\Cldoctor\Helper;
use RadiusTheme\Cldoctor\Options;

?>
<section class="breadcrumbs-banner">
   <div class="container">
      <div class="breadcrumbs-area">
        <h1 class="heading-title"><?php Helper::the_title(); ?></h1>
		<?php
			if ( class_exists('Rtcl' ) && Options::$has_breadcrumb ):
				do_action( 'cldoctor_breadcrumb' );
			endif;
		?>
      </div>
   </div>
</section>
