<?php
/**
 * The template for displaying all single posts
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
?>

<div class="wrapper mt-lg-3" id="single-wrapper">

	<div class="container" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part('global-templates/left-sidebar-check'); ?>

			<main class="site-main" id="main">

				<?php
				while (have_posts()) {
					the_post();
					get_template_part('loop-templates/content', 'single');
				}


				?>

			</main><!-- #main -->

			<!-- Do the right sidebar check -->
			<?php get_template_part('global-templates/right-sidebar-check'); ?>

		</div><!-- .row -->
		<?php

		$shortcode = get_field('shortcode', 'option');
		if (!empty($shortcode) && !ctype_space($shortcode)) {
			?>
			<div style="margin: 3rem 0 0;">

				<?php echo do_blocks("$shortcode"); ?>
			</div>
		<?php } ?>
	</div><!-- #content -->

</div><!-- #single-wrapper -->

<?php

get_footer();
