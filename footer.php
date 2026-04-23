<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
?>

<?php if (class_exists('ACF')) { ?>

	<div class="wrapper" id="wrapper-footer">
		<footer class="site-footer border-bottom" id="colophon">
			<div class="container">
				<div class="row ">
					<?php
					if (have_rows('footer_settings', 'options')) {
						while (have_rows('footer_settings', 'options')) {
							the_row();
							$footer_logo     = (get_sub_field('footer_logo'));
							$slogan          = !empty(get_sub_field('slogan')) ? "<p>" . get_sub_field('slogan') . "</p>" : '';
							$adress          = !empty(get_sub_field('adress')) ? "<p>" . get_sub_field('adress') . "</p>" : '';
							$header_settings = get_field('header_settings', 'option');
							$header_email    = trim((string) ($header_settings['email'] ?? ''));
							$header_phone    = trim((string) ($header_settings['phone'] ?? ''));
							$phone_href      = preg_replace('/[^0-9+]/', '', $header_phone);
							?>
							<div class="col-lg">
								<div class="footer__logo">
									<?php echo wp_get_attachment_image($footer_logo, 'full') . $slogan;
									if ($header_email !== ''): ?>
										<p>
											<a class="mail" href="mailto:<?php echo esc_attr($header_email); ?>">
												<span class=""><?php echo esc_html($header_email); ?></span>
											</a>
										</p>
									<?php endif; ?>

									<?php if ($header_phone !== '' && $phone_href !== ''): ?>
										<p>
											<a class="phone" href="tel:<?php echo esc_attr($phone_href); ?>">
												<span class=""><?php echo esc_html($header_phone); ?></span>
											</a>
										</p>
									<?php endif;
									echo $adress; ?>
								</div>
							</div>
							<?php
							if (have_rows('footer_nav', 'options')) {
								while (have_rows('footer_nav', 'options')) {
									the_row();
									$title_nav = get_sub_field('title_footer_menu');
									$menu      = get_sub_field('select_menu', 'options');
									?>
									<div class="col-lg">
										<div class="footer__navigation">
											<?php
											if ($title_nav)
												echo '<p class="footer__navigation--title">' . $title_nav . '</p>';
											if ($menu) {
												wp_nav_menu(
													array(
														'menu'  => $menu,
														'depth' => 0,
													)
												);
											}
											?>
										</div>
									</div>
									<?php
								}
							}
						}
					}
					?>
				</div>
			</div>

		</footer><!-- #colophon -->
		<footer class="copyrights">
			<div class="container">
				<div class="row">
					<div class="col-12 text-right">
						<span>@ <?php echo date("Y"); ?> <a href="<?php echo get_site_url(); ?>">
								<?php echo get_bloginfo('name'); ?></a></span>
					</div>
				</div>
			</div>
		</footer>

	</div><!-- wrapper end -->

<?php } ?>

<div id="goTop"></div>

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>