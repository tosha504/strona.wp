<?php
/**
 * Services
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

//post type Services
function services_post_type()
{
	$labels = array(
		'name'          => __('Services', 'thenewlook'),
		'singular_name' => __('Services', 'thenewlook'),
		'menu_name'     => __('Services', 'thenewlook'),
	);
	$args   = array(
		'label'               => 'Services',
		'rewrite'             => array(
			'slug' => 'services'
		),
		'description'         => 'Services',
		'labels'              => $labels,
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_icon'           => 'dashicons-thumbs-up',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type('services', $args);
}
add_action('init', 'services_post_type');


function single_services($id, $btn_style = 'link')
{

	if (!$id)
		return;

	// variables
	$title       = get_field('title', $id);
	$icon        = get_field('icon', $id);
	$price_g     = get_field('price', $id);
	$desription  = get_field('desription', $id);
	$button      = get_field('link', $id);
	$badge_color = get_field('badge_color', $id) ?? '#000';
	$badge       = !empty(get_field('badge', $id)) ? "<p class='servicesItem__badge' style='background-color: " . $badge_color . "'>" . esc_attr(get_field('badge', $id)) . "</p>" : '';
	$content     = ($icon ? '<div class="servicesItem__icon">' . wp_get_attachment_image($icon) . $badge . '</div>' : '') .
		($title ? '<h6 class="servicesItem--title">' . esc_attr($title) . '</h6>' : '') .
		($price_g ? single_services_price($price_g) : '') .
		'<p class="servicesItem--text">' . $desription . '</p>' .
		($button ? '<div class="servicesItem__btn">' . link_acf($button, $btn_style) . '</div>' : '');

	return $content;

}


function single_services_price($price_g)
{

	$before_price = $price_g['before_price'];
	$price        = $price_g['price'];
	$after_price  = $price_g['after_price'];

	$result =
		'<p class="servicesItem__price">' .
		($before_price ? '<span class="servicesItem__price--before">' . esc_attr($before_price) . ' </span>' : '') .
		($price ? '<span class="servicesItem__price--price">' . esc_attr($price) . ' zł </span>' : '') .
		($after_price ? '<span class="servicesItem__price--after">' . esc_attr($after_price) . '</span>' : '') .
		'</p>';

	return $result;

}