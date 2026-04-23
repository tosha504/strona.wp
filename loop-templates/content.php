<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
$img = get_the_post_thumbnail('', 'post-content');
?>

<div class="col-lg-4">

	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

		<div class="loopPostContent">

			<?php if ($img) { ?>

				<div class="loopPostContent__thumbnail">
					<a href="<?php echo esc_url(get_permalink()); ?>">
						<?php echo $img ?>
					</a>
					<?php echo get_product_primary_category(get_the_ID()); ?>
				</div>

			<?php } ?>

			<div class="loopPostContent__content">

				<?php

				the_title(
					sprintf('<h3 class="loopPostContent__content--title"><a href="%s" rel="bookmark">', esc_url(get_permalink())),
					'</a></h3>'
				);


				echo '<p class="loopPostContent__content--text">' . get_the_excerpt() . '</p>';

				echo '<div class="loopPostContent__content__btnArea"><a href="' . esc_url(get_permalink()) . '" class="btn btn-secondary loopContent">' . __('View post', 'thenewlook') . '</a></div>';
				?>


			</div>

		</div>





	</article><!-- #post-## -->

</div>