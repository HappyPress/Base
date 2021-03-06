<?php
/**
 * The template used for displaying page content in page-builderboxed.php
 *
 * @package HappyBase
 * @since HappyBase 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(array(
			'before' => '<div class="page-links">' . esc_html__('Pages:', 'happybase'),
			'after' => '</div>',
			'link_before' => '<span class="page-numbers">',
			'link_after' => '</span>',
		)); ?>
	</div><!-- /.entry-content -->
</article><!-- /#post -->
