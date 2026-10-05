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

get_header();

$blog_style = Options::$options['blog_style'];
$cols = Options::$options['blog_grid'];
$cols2 = Options::$options['blog_medium_grid'];

?>
<main id="primary" class="site-main content-area">
	<div class="container">
		<div class="row">
			<?php
			if ( Options::$layout == 'left-sidebar' ) {
				get_sidebar();
			}
			?>
			<div class="<?php Helper::the_layout_class(); ?>">
				<div class="main-content blog-layout-<?php echo esc_attr( $blog_style ); ?>">
					<?php if ( have_posts() ): ?>
						<?php
							if ($blog_style == 2) {
								echo '<div class="row row-cols-lg-'.esc_attr( $cols ).' row-cols-md-'.esc_attr( $cols2 ).' row-cols-1">';
							} else {
								echo '<div class="row row-cols-1">';
							}
							while ( have_posts() ): the_post();
								echo '<div class="col">';
									get_template_part( 'template-parts/content' );
								echo '</div>';
							endwhile;
						?>
						</div>
					<?php else: ?>
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					<?php endif; ?>
				</div>
				<?php get_template_part( 'template-parts/pagination' ); ?>
			</div>
			<?php
			if ( Options::$layout == 'right-sidebar' ) {
				get_sidebar();
			}
			?>
		</div>
	</div>
    </main>

<?php get_footer(); ?>