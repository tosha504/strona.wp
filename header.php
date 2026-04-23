<?php

/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Thenewlook
 */

defined('ABSPATH') || exit;

$header_settings = get_field('header_settings', 'option');
$header_settings = is_array($header_settings) ? $header_settings : [];

$header_email     = trim((string) ($header_settings['email'] ?? ''));
$header_phone     = trim((string) ($header_settings['phone'] ?? ''));
$header_top_links = !empty($header_settings['top_links']) && is_array($header_settings['top_links'])
	? $header_settings['top_links']
	: [];
$header_cta_links = !empty($header_settings['cta_links']) && is_array($header_settings['cta_links'])
	? $header_settings['cta_links']
	: [];

$has_header_topbar = $header_email !== '' || $header_phone !== '' || !empty($header_top_links);

$sanitize_class_list = static function ($class_string): string {
	$class_string = is_string($class_string) ? trim($class_string) : '';

	if ($class_string === '') {
		return '';
	}

	$classes = preg_split('/\s+/', $class_string);
	$classes = array_filter($classes);
	$classes = array_map('sanitize_html_class', $classes);

	return implode(' ', $classes);
};

$phone_href = preg_replace('/[^0-9+]/', '', $header_phone);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
		rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>
	<?php do_action('wp_body_open'); ?>

	<div class="site" id="page">
		<div class="overlay-megaMenu" aria-hidden="true"></div>
		<?php if ($has_header_topbar): ?>
			<div class="headerTopBar">
				<div class="container p-xl-0">
					<div class="headerTopBar__inner">
						<div class="headerTopBar__left">
							<?php if ($header_email !== ''): ?>
								<a class="headerTopBar__contact headerTopBar__contact--email"
									href="mailto:<?php echo esc_attr($header_email); ?>">
									<span class="headerTopBar__contactText"><?php echo esc_html($header_email); ?></span>
								</a>
							<?php endif; ?>

							<?php if ($header_phone !== '' && $phone_href !== ''): ?>
								<a class="headerTopBar__contact headerTopBar__contact--phone"
									href="tel:<?php echo esc_attr($phone_href); ?>">
									<span class="headerTopBar__contactText"><?php echo esc_html($header_phone); ?></span>
								</a>
							<?php endif; ?>
						</div>

						<?php if (!empty($header_top_links)): ?>
							<div class="headerTopBar__right">
								<?php foreach ($header_top_links as $top_link_row): ?>
									<?php
									$link = $top_link_row['link'] ?? null;
									$icon = !empty($top_link_row['icon']) ? absint($top_link_row['icon']) : 0;

									if (empty($link) || empty($link['url']) || empty($link['title'])) {
										continue;
									}

									$link_url    = $link['url'];
									$link_title  = $link['title'];
									$link_target = !empty($link['target']) ? $link['target'] : '_self';
									$link_rel    = $link_target === '_blank' ? 'noopener noreferrer' : '';
									?>
									<a class="headerTopBar__metaLink" href="<?php echo esc_url($link_url); ?>"
										target="<?php echo esc_attr($link_target); ?>" rel="<?php echo esc_attr($link_rel); ?>">
										<?php if ($icon): ?>
											<span class="headerTopBar__metaLinkIcon" aria-hidden="true">
												<?php echo wp_get_attachment_image($icon, 'full', false, ['alt' => '', 'class' => 'headerTopBar__metaLinkIconImage']); ?>
											</span>
										<?php endif; ?>
										<span class="headerTopBar__metaLinkText"><?php echo esc_html($link_title); ?></span>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div id="wrapper-navbar">
			<?php if (is_singular('post')): ?>
				<div id="progressBar"></div>
			<?php endif; ?>

			<a class="skip-link sr-only sr-only-focusable" href="#content">
				<?php esc_html_e('Skip to content', 'thenewlook'); ?>
			</a>



			<nav id="main-nav" class="navbar navbar-expand-xl position-relative" aria-labelledby="main-nav-label">
				<h2 id="main-nav-label" class="sr-only">
					<?php esc_html_e('Main Navigation', 'thenewlook'); ?>
				</h2>

				<div class="container p-xl-0">
					<div class="col-6 col-xl-2">
						<?php the_custom_logo(); ?>
					</div>

					<div class="col-xl-10 position-static d-none d-xl-flex align-items-center justify-content-end">
						<?php
						wp_nav_menu([
							'theme_location'  => 'primary',
							'container_class' => 'desktop-menu d-none d-xl-block',
							'menu_class'      => 'navbar-nav',
							'fallback_cb'     => '',
							'menu_id'         => 'main-menu',
							'depth'           => 0,
						]);
						?>

						<?php if (!empty($header_cta_links)): ?>
							<div class="headerActions">
								<?php foreach ($header_cta_links as $cta_row): ?>
									<?php
									$link = $cta_row['link'] ?? null;

									if (empty($link) || empty($link['url']) || empty($link['title'])) {
										continue;
									}

									$link_url    = $link['url'];
									$link_title  = $link['title'];
									$link_target = !empty($link['target']) ? $link['target'] : '_self';
									$link_rel    = $link_target === '_blank' ? 'noopener noreferrer' : '';
									$link_class  = trim($sanitize_class_list($cta_row['class'] ?? ''));

									if ($link_class === '') {
										$link_class = 'nav-link';
									}
									?>
									<a class="<?php echo esc_attr($link_class); ?>" href="<?php echo esc_url($link_url); ?>"
										target="<?php echo esc_attr($link_target); ?>" rel="<?php echo esc_attr($link_rel); ?>">
										<?php echo esc_html($link_title); ?>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="col-6 justify-content-end d-flex d-xl-none">
						<a href="#open-menu" class="fixedMenu__toggler" aria-controls="mobile-menu-panel"
							aria-expanded="false">
							<span class="fixedMenu__toggler--line"></span>
							<span class="fixedMenu__toggler--line"></span>
							<span class="fixedMenu__toggler--line"></span>
						</a>
					</div>
				</div>
			</nav>
		</div>

		<div class="overlay--fixedMenu"></div>

		<div class="fixedMenu d-xl-none" id="mobile-menu-panel">
			<div class="fixedMenu__header">
				<a href="#close-menu" class="fixedMenu__toggler fixedMenu__toggler--close"
					aria-label="<?php echo esc_attr__('Close menu', 'thenewlook'); ?>">
					<span class="fixedMenu__toggler--title"><?php echo esc_html__('Close', 'thenewlook'); ?></span>
					<span class="fixedMenu__closeIcon" aria-hidden="true">
						<span class="fixedMenu__closeLine"></span>
						<span class="fixedMenu__closeLine"></span>
					</span>
				</a>
			</div>

			<div class="fixedMenu__body">
				<?php
				wp_nav_menu([
					'theme_location'  => 'mobile',
					'container_class' => 'mobile-menu d-xl-block',
					'menu_class'      => 'navbar-nav justify-content-end',
					'fallback_cb'     => '',
					'menu_id'         => 'mobile-menu',
					'depth'           => 0,
				]);
				?>
			</div>

			<div class="fixedMenu__footer">
				<?php if (!empty($header_cta_links)): ?>
					<div class="fixedMenu__actions">
						<?php foreach ($header_cta_links as $cta_row): ?>
							<?php
							$link = $cta_row['link'] ?? null;

							if (empty($link) || empty($link['url']) || empty($link['title'])) {
								continue;
							}

							$link_url    = $link['url'];
							$link_title  = $link['title'];
							$link_target = !empty($link['target']) ? $link['target'] : '_self';
							$link_rel    = $link_target === '_blank' ? 'noopener noreferrer' : '';
							$link_class  = trim($sanitize_class_list($cta_row['class'] ?? ''));

							if ($link_class === '') {
								$link_class = 'nav-link';
							}
							?>
							<a class="<?php echo esc_attr($link_class); ?>" href="<?php echo esc_url($link_url); ?>"
								target="<?php echo esc_attr($link_target); ?>" rel="<?php echo esc_attr($link_rel); ?>">
								<?php echo esc_html($link_title); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>