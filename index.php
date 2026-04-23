<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

?>


<div class="wrapper" id="index-wrapper">

	<header class="entry-header">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<?php echo '<h1 class="entry-title">' . __('Blog', 'thenewlook') . '</h1>'; ?>
				</div>
			</div>
		</div>
	</header><!-- .entry-header -->

	<div class="container" id="content" tabindex="-1">


		<div class="row">

			<?php get_template_part('global-templates/left-sidebar-check'); ?>

			<main class="site-main" id="main">

				<div class="site-main__nav">
					<div class="row align-items-center">
						<div class="col-lg-5">
							<?php echo '<h2 class="site-main__nav--title">' . __('Latest posts', 'thenewlook') . '</h2>'; ?>
						</div>
						<div class="col-lg-7">
							<?php do_action('blog_switch_category'); ?>
						</div>
					</div>
				</div>

				<div class="site-main__posts">
					<?php echo '<div class="blogLoader">' . loader() . '</div>'; ?>
					<div id="blogResult">

						<?php

						if (have_posts()) {
							echo '<div class="row">';
							// Start the Loop.
							while (have_posts()) {
								the_post();
								get_template_part('loop-templates/content', get_post_format());
							}

							echo '</div>';
						} else {
							get_template_part('loop-templates/content', 'none');
						}

						wp_reset_postdata();

						?>

					</div>

				</div>

			</main><!-- #main -->

			<!-- The pagination component -->

			<!-- Do the right sidebar check -->
			<?php get_template_part('global-templates/right-sidebar-check'); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #index-wrapper -->

<?php
get_footer();
