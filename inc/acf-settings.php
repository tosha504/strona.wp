<?php
/**
 * Theme options ACF
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// sync
function my_acf_json_save_point( $path ) {
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
    
}
add_filter('acf/settings/save_json', 'my_acf_json_save_point');


function my_acf_json_load_point( $paths ) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'my_acf_json_load_point');


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=>  __('Theme General Settings', 'thenewlook'),
		'menu_title'	=> __('Theme Settings', 'thenewlook'),
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'install_themes',
		'redirect'		=> false
	));

}

$acf_functions = array(
	'inc/acf/mega-menu.php', 
	'inc/acf/price-list.php', 
	'inc/acf/blog-switch-category.php', 
	'inc/acf/services.php', 
	'inc/acf/fixed-nav.php', 
);

foreach ( $acf_functions as $file ) {
	require_once get_theme_file_path( $file );
}


function link_acf($link = null, $class = null){
	if(!$link) return;

	$link_url = $link['url'];
	$link_title = $link['title'];
	$link_target = $link['target'] ? $link['target'] : '_self';

	$result = '<a class="' . $class . '" href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '">' . esc_html( $link_title ) . '</a>';
	
	return $result;
}

function get_product_primary_category($id){
	$category_link_tomek = get_field('category_link_tomek', $id);


	$display_category_name = get_field('display_category_name', $id);
	$result = '';
	if (!empty($display_category_name) && !empty($category_link_tomek[0])) {
		$result .= '<div class="primaryCat"><a href="' . get_category_link($category_link_tomek[0]) . '" class="primaryCat--link">' . $display_category_name . '</a></div>';
	}
// 	$cat = get_field('primary_category', $id);
// 	if(!$cat){
// 		$cats = wp_get_post_categories($id);
// 		if(!$cats) return;
// 		$cat = $cats[0];
// 	}
// 	$result = '<div class="primaryCat"><a href="' . get_category_link($cat) . '" class="primaryCat--link">' . get_cat_name($cat) . '</a></div>';
	return $result;

}

function google_analytics_script(){
	$settings  = get_field('analytics', 'options');
	
	if(!$settings) return;
	
	$ga = $settings['google_analytics'];
	
	if($ga){
		?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga); ?>"></script>
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());

				gtag('config', '<?php echo esc_attr($ga); ?>');
			</script>
		<?php
	}
	
}

add_action('wp_head', 'google_analytics_script', 1);