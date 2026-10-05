<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RadiusTheme\Cldoctor\Options;

$comments_number = get_comments_number();
/* translators: %s is the number of comments */
$comments_text   = sprintf( _n( '%s Comment', '%s Comments', $comments_number, 'cldoctor' ), number_format_i18n( $comments_number ) );
$has_thumbnail   = has_post_thumbnail() ? 'has-thumbnail' : 'has-no-thumbnail';
$post_class      = $has_thumbnail . ' post-each';
$has_entry_meta1 = Options::$options['blog_date'];
$has_entry_meta2 = ( Options::$options['blog_cat_visibility'] && has_category() ) || Options::$options['blog_author_name'] || Options::$options['blog_comment_num'];
$length          = Options::$options['excerpt_length'];
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	<?php if ( has_post_thumbnail() ): ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'cldoctor-size1' ); ?></a>
        </div>
	<?php endif; ?>
    <div class="post-content-area">
	    <?php if ( $has_entry_meta1 ): ?>
            <ul class="post-meta top">
			    <?php if ( Options::$options['blog_date'] ): ?>
                    <li class="meta-date">
                        <i class="icon-pl-calendar"></i>
                        <span class="updated published">
                            <?php the_time( get_option( 'date_format' ) ); ?>
                        </span>
                    </li>
			    <?php endif; ?>
            </ul>
	    <?php endif; ?>
        <?php if ( ! empty( get_the_title() ) ): ?>
            <h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
		<?php endif; if ($length > 0) { ?>
            <p class="entry-summary"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $length ) ); ?></p>
        <?php } if ( $has_entry_meta2 ): ?>
            <ul class="post-meta bottom">
			    <?php if ( Options::$options['blog_author_name'] || Options::$options['blog_cat_visibility'] && has_category() ): ?>
                    <li class="meta-author">
                        <span class="vcard author">
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author(); ?></a>
                        </span>
                        <?php esc_html_e( 'in', 'cldoctor' ); ?>
                        <?php the_category( ', ' ); ?>
                    </li>
			    <?php endif; ?>

                <?php if ( Options::$options['blog_comment_num'] ): ?>
                    <li class="meta-comments">
                        <i class="icon-comment"></i>
                        <?php echo esc_html( $comments_text ); ?>
                    </li>
			    <?php endif; ?>
            </ul>
	    <?php endif; ?>
	    <?php if ( Options::$options['blog_button'] ): ?>
            <a class="button-style-2" href="<?php the_permalink(); ?>">
			    <?php esc_html_e( 'Read More', 'cldoctor' ); ?>
                <i class="fa-solid fa-arrow-right rtin-button-icon"></i>
            </a>
	    <?php endif; ?>
    </div>
</article>