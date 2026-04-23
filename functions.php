<?php
/**
 * UnderStrap functions and definitions
 *
 * @package Thenewlook
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;
// UnderStrap's includes directory.
$understrap_inc_dir = 'inc';
// Array of files to include.
$understrap_includes = array(
	'/custom-functions.php',                // custom functions
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	// '/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	// '/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567.
	'/editor.php',                          // Load Editor functions.
	'/block-editor.php',                    // Load Block Editor functions.
	'/deprecated.php',                      // Load deprecated functions.
	'/mobile-menu.php',                     // mobile menu
	'/mega-menu/class-tnl-mega-menu-manager.php',
);
// Load Jetpack compatibility file if Jetpack is activiated.
if (class_exists('Jetpack')) {
	$understrap_includes[] = '/jetpack.php';
}
if (class_exists('ACF')) {
	$understrap_includes[] = '/acf-settings.php';
}
// Include files.
foreach ($understrap_includes as $file) {
	require_once get_theme_file_path($understrap_inc_dir . $file);
}
require_once get_template_directory() . '/inc/Assets.php';
\Theme\Assets::init();
// add_filter('script_loader_tag', function (string $tag, string $handle, string $src): string {
// 	if (in_array($handle, ['vite-client', 'vite-entry'], true)) {
// 		return '<script type="module" src="' . esc_url($src) . '"></script>';
// 	}
// 	return $tag;
// }, 10, 3);
// add_action('admin_menu', function () {
// 	add_submenu_page(
// 		'tools.php',
// 		'Patterny WP',
// 		'Patterny WP',
// 		'manage_options',
// 		'aw-patterns-list',
// 		'aw_render_patterns_list_page'
// 	);
// });

// function aw_render_patterns_list_page(): void
// {
// 	if (!current_user_can('manage_options')) {
// 		return;
// 	}

// 	$patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();

// 	echo '<div class="wrap">';
// 	echo '<h1>Lista zarejestrowanych patternów</h1>';

// 	if (empty($patterns)) {
// 		echo '<p>Brak zarejestrowanych patternów.</p>';
// 		echo '</div>';
// 		return;
// 	}

// 	echo '<table class="widefat striped">';
// 	echo '<thead>
//             <tr>
//                 <th style="width:30%;">Slug</th>
//                 <th style="width:30%;">Title</th>
//                 <th style="width:20%;">Categories</th>
//                 <th style="width:20%;">Source</th>
//             </tr>
//           </thead>';
// 	echo '<tbody>';

// 	foreach ($patterns as $pattern) {
// 		$name       = $pattern['name'] ?? '';
// 		$title      = $pattern['title'] ?? '';
// 		$categories = !empty($pattern['categories']) ? implode(', ', $pattern['categories']) : '';
// 		$source     = $pattern['source'] ?? '';

// 		echo '<tr>';
// 		echo '<td><code>' . esc_html($name) . '</code></td>';
// 		echo '<td>' . esc_html($title) . '</td>';
// 		echo '<td>' . esc_html($categories) . '</td>';
// 		echo '<td>' . esc_html($source) . '</td>';
// 		echo '</tr>';
// 	}

// 	echo '</tbody>';
// 	echo '</table>';
// 	echo '</div>';
// }

add_action('admin_menu', function () {
	add_submenu_page(
		'tools.php',
		'Moje wzorce WP',
		'Moje wzorce WP',
		'manage_options',
		'aw-user-patterns',
		'aw_render_user_patterns_page'
	);
});

function aw_render_user_patterns_page(): void
{
	if (!current_user_can('manage_options')) {
		return;
	}

	$patterns = get_posts([
		'post_type'      => 'wp_block',
		'post_status'    => ['publish', 'draft', 'private'],
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	]);

	echo '<div class="wrap">';
	echo '<h1>Wzorce zapisane w panelu WordPress</h1>';

	if (empty($patterns)) {
		echo '<p>Nie znaleziono wzorców typu wp_block.</p>';
		echo '</div>';
		return;
	}

	echo '<table class="widefat striped">';
	echo '<thead>
        <tr>
            <th style="width:8%;">ID</th>
            <th style="width:32%;">Tytuł</th>
            <th style="width:15%;">Status</th>
            <th style="width:45%;">Kod do użycia</th>
        </tr>
    </thead>';
	echo '<tbody>';

	foreach ($patterns as $pattern) {
		$id     = (int) $pattern->ID;
		$title  = get_the_title($id);
		$status = $pattern->post_status;

		$snippet = 'echo do_blocks(\'<!-- wp:block {"ref":' . $id . '} /-->\');';

		echo '<tr>';
		echo '<td><code>' . esc_html((string) $id) . '</code></td>';
		echo '<td>' . esc_html($title) . '</td>';
		echo '<td>' . esc_html($status) . '</td>';
		echo '<td><code>' . esc_html($snippet) . '</code></td>';
		echo '</tr>';
	}

	echo '</tbody>';
	echo '</table>';
	echo '</div>';
}