<?php
/**
 * @author    RadiusTheme
 *
 * @version       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;

$col_class = 'row-cols-' . $settings['rtcl_col_mobile'];
$col_class .= ' row-cols-xs-' . $settings['rtcl_col_mobile'];
$col_class .= ' row-cols-sm-' . $settings['rtcl_col_sm'];
$col_class .= ' row-cols-md-' . $settings['rtcl_col_md'];
$col_class .= ' row-cols-lg-' . $settings['rtcl_col_lg'];
$col_class .= ' row-cols-xl-' . $settings['rtcl_col_xl'];

?>

<div class="rtcl rtcl-categories-elementor rtcl-categories rtcl-categories-grid rt-el-listing-cat-box-2 <?php echo esc_attr( $settings['rtcl_equal_height'] ? 'rtcl-equal-height' : '' ); ?>">
    <div class="row <?php echo esc_attr( $col_class ); ?> rtcl-no-margin">
		<?php
		$i = 0;
		foreach ( $terms as $trm ) {
			$count = 0;
			if ( ! empty( $settings['rtcl_hide_empty'] ) || ! empty( $settings['rtcl_show_count'] ) ) {
				$count = Functions::get_listings_count_by_taxonomy(
					$trm->term_id,
					rtcl()->category,
					! empty( $settings['rtcl_pad_counts'] ) ? 1 : 0
				);

				if ( ! empty( $settings['rtcl_hide_empty'] ) && 0 == $count ) {
					continue;
				}
			}

			$content_alignemnt = ! empty( $settings['rtcl_content_alignment'] ) ? $settings['rtcl_content_alignment'] : null;
			?>
			<div class="col cat-item-wrap equal-item">
				<div class="cat-details text-<?php echo esc_attr( $settings['rtcl_cat_box_style_2_alignment'].' '.$content_alignemnt );?>">
					<div class="cat-details-inner">
						<?php 
							$view_post = sprintf(
							/* translators: %s: Category term */
								__( 'View all posts in %s', 'cldoctor' ),
								$trm->name
							); 
						?>
						<div class="rtin-head-area">
							<?php
							if ( $settings['rtcl_show_image'] ) {
								$icon_image_html = '';
								if ( 'image' === $settings['rtcl_icon_type'] ) {
									$image_size = isset( $settings['rtcl_icon_image_size_size'] ) ? $settings['rtcl_icon_image_size_size'] : 'medium';
									if ( 'custom' === $image_size ) {
										$image_size = isset( $settings['rtcl_icon_image_size_custom_dimension'] ) ? $settings['rtcl_icon_image_size_custom_dimension'] : 'medium';
									}
									$image_id         = get_term_meta( $trm->term_id, '_rtcl_image', true );
									$image_attributes = wp_get_attachment_image_src( (int) $image_id, $image_size );
									$image            = isset( $image_attributes[0] ) && ! empty( $image_attributes[0] ) ? $image_attributes[0] : '';
									if ( '' !== $image ) {
										echo "<div class='image icon'>";
										$icon_image_html .= '<a href="' . esc_url( get_term_link( $trm ) ) . '" class="rtcl-responsive-container" title="' . esc_attr( $view_post ) . '">';
										$icon_image_html .= '<img src="' . esc_url( $image ) . '" class="rtcl-responsive-img" />';
										$icon_image_html .= '</a>';
										echo wp_kses_post( $icon_image_html );
										echo '</div>';
									}
								}

								if ( 'icon' === $settings['rtcl_icon_type'] ) {
									$icon_id = get_term_meta( $trm->term_id, '_rtcl_icon', true );
									if ( $icon_id ) {
										echo "<div class='icon'>";
										printf(
											'<a href="%s" title="%s"><span class="rtcl-icon rtcl-icon-%s"></span></a>',
											esc_url( get_term_link( $trm ) ),
											esc_attr( $view_post ),
											esc_attr( $icon_id )
										);
										echo '</div>';
									}
								}

							}

							if ( $settings['rtcl_show_category_title'] ) {
								printf(
									"<h3 class='rtcl-category-title'><a href='%s' title='%s'>%s</a></h3>",
									esc_url( get_term_link( $trm ) ),
									esc_attr( $view_post ),
									esc_html( $trm->name )
								);
							}

							if ( ! empty( $settings['rtcl_show_count'] ) ) {
								$ads_text = null;
								if ( ! empty( $settings['display_text_after_count'] ) ) {
									$ads_text = $settings['display_text_after_count'];
								}
								printf( "<div class='views'>%d <span class='ads-count'> %s</span></div>", absint( $count ), esc_html( $ads_text ) );
							} ?>
						</div>
						<?php if ( $settings['rtcl_show_sub_category'] || $settings['rtcl_description'] && $trm->description ) { ?>
							<div class="box-body">
								<?php
								if ( $settings['rtcl_show_sub_category'] ) {
									$child_args  = [
										'taxonomy'   => 'rtcl_category',
										'parent'     => $trm->term_id,
										'number'     => $settings['rtcl_sub_category_limit'],
										'hide_empty' => false,
										'orderby'    => 'count',
										'order'      => 'DESC',
									];
									$child_terms = get_terms( $child_args );
									if ( $settings['rtcl_show_sub_category'] && ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) {
										?>
										<ul class="rtin-sub-cats">
											<?php
											foreach ( $child_terms as $child_trm ) {
												$count = Functions::get_listings_count_by_taxonomy(
													$child_trm->term_id,
													rtcl()->category,
													! empty( $settings['rtcl_pad_counts'] ) ? 1 : 0
												);
												?>
												<li>
													<a href="<?php echo esc_url( get_term_link( $child_trm ) ); ?>"> 
														<i class="rtcl-icon rtcl-icon-angle-right"></i>
														<span><?php echo esc_html( $child_trm->name . ' (' . $count . ')' ); ?></span> 
													</a>
												</li>
											<?php } ?>
										</ul>
										<?php
									}
								} ?>

								<?php if ( $settings['rtcl_description'] && $trm->description ) { ?>
									<div class="description-section">
										<?php
										$word_limit = $settings['rtcl_content_limit'] ? wp_trim_words( $trm->description, $settings['rtcl_content_limit'] ) : $trm->description;
										printf( '<p>%s</p>', esc_html( $word_limit ) );
										?>
									</div>
									<?php
								}
								?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
