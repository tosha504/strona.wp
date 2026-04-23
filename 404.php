<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Understrap
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;
get_header();
?>
<div class="wrapper sticky-footer" id="error-404-wrapper">
	<div class="container" id="content" tabindex="-1">
		<div class="row">
			<div class="col-md-12 content-area" id="primary">
				<main class="site-main" id="main">
					<section class="error-404 not-found text-center">
						<h1 class="error-404--title">
							<?php echo __('This page is gone', 'thenewlook'); ?>
							<span>404</span>
						</h1>
						<p class="error-404--text">
							<?php echo __("Sorry, the page you asked for couldn't be found.", 'thenewlook'); ?>
						</p>
						<a href="<?php
						echo
							get_home_url(); ?>" class="btn btn-primary"><?php echo __('Back to homepage', 'thenewlook'); ?></a>
					</section><!-- .error-404 -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .row -->
	</div><!-- #content -->
</div><!-- #error-404-wrapper -->
<?php
get_footer();
