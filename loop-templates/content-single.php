<?php
/**
 * Single post partial template
 *
 * @package Thenewlook
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="singlePost">
		<div class="singlePost__thumbnail">
			<?php echo get_product_primary_category(get_the_ID()); ?>
			<div class="singlePost__thumbnail__meta d-flex gap-5">
				<div class="singlePost__thumbnail__meta__column ">
					<?php echo post_date(); ?>
				</div>
				<div class="singlePost__thumbnail__meta__column ">
					<?php echo reading_time(); ?>
				</div>
			</div>
			<?php the_title('<h1 class="post-title">', '</h1>'); ?>

			<?php
			if (get_field('display_sidebar') != 0) {
				echo get_the_post_thumbnail('', 'blog-single');
			} else {
				echo get_the_post_thumbnail('', 'blog-single-full');
			} ?>
		</div>
		<div class="singlePost__content">
			<div class="entry-content">
				<?php
				the_content();
				understrap_post_nav(get_the_ID());
				understrap_link_pages();
				?>
			</div><!-- .entry-content -->
		</div>
	</div>
</article><!-- #post-## -->