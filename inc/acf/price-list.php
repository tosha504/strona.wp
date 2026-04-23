<?php

/**
 * Price list
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

//post type Price list
function price_list_post_type()
{
	$labels = array(
		'name'          => __('Price list', 'thenewlook'),
		'singular_name' => __('Price list', 'thenewlook'),
		'menu_name'     => __('Price list', 'thenewlook'),
	);
	$args   = array(
		'label'               => 'Price list',
		'rewrite'             => array(
			'slug' => 'price-list'
		),
		'description'         => 'Price list',
		'labels'              => $labels,
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_icon'           => 'dashicons-editor-spellcheck',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type('pricelist', $args);
}
add_action('init', 'price_list_post_type', 1);


function single_price_list($id)
{

	if (!$id)
		return;

	// variables
	$title        = get_the_title($id);
	$color        = get_field('color', $id) ?: '#2056DE';
	$icon         = get_field('icon', $id);
	$desc         = get_field('description', $id);
	$related_text = get_field('related_text', $id);

	$price_g             = get_field('price', $id);
	$text_affer_price    = get_field('text_affer_price_lg', $id);
	$text_affer_price_sm = get_field('text_affer_price', $id);
	$first_button        = get_field('first_button', $id);

	$list             = get_field('list', $id);
	$optional_list    = get_field('optional_list', $id);
	$secondary_button = get_field('secondary_button', $id);

	$content =
		'<div class="priceList h-100" style="background-color: ' . esc_attr($color) . '; border: 1px solid ' . esc_attr($color) . ';">' .
		($related_text ? '<span class="priceList--relatedText">' . esc_attr($related_text) . '</span>' : '') .
		'<div class="wrap">' .
		($icon ? '<div class="priceList__icon-wrap"><div class="priceList__icon">' . wp_get_attachment_image($icon) . '</div>' : '') .
		($text_affer_price ? '<p class="priceList--textAfterPrice--lg promoted-text">' . $text_affer_price . '</p></div>' : '') .
		'<h6 class="priceList--title">' . esc_attr($title) . '</h6>' .
		($desc ? '<p class="priceList--desc">' . esc_attr($desc) . '</p>' : '') .
		($price_g ? single_price_list_price($price_g) : '') .

		($text_affer_price_sm ? '<small class="priceList--textAfterPrice">' . $text_affer_price_sm . '</small>' : '') .
		($first_button ? '<div class="priceList__btn">' . link_acf($first_button, 'btn btn-primary') . '</div>' : '') .
		($list ? '<div class="priceList__listContent">' . single_price_list_list($list) . '</div>' : '') .
		($optional_list ? '<div class="priceList__listContent optional">' . single_price_list_list($optional_list, false) . '</div>' : '') .
		($secondary_button ? '<div class="priceList__btn">' . link_acf($secondary_button, 'btn btn-outline-primary') . '</div>' : '') .

		'</div></div>';

	return $content;
}

function single_price_list_price($price_g)
{

	$before_price      = $price_g['before_price'];
	$price             = $price_g['price'];
	$after_price       = $price_g['after_price'];
	$before_sale_price = $price_g['before_sale_price'] ? '<del>' . esc_attr($price_g['before_sale_price']) . '</del></br>' : '';

	$result =
		'<p class="priceList__price">' . $before_sale_price .
		($before_price ? '<span class="priceList__price--before">' . esc_attr($before_price) . ' </span>' : '') .
		($price ? '<span class="priceList__price--price">' . esc_attr($price) . ' zł </span>' : '') .
		($after_price ? '<span class="priceList__price--after">' . esc_attr($after_price) . '</span>' : '') .
		'</p>';

	return $result;
}

function single_price_list_list($list, $status = true)
{

	$result = '<ul class="priceList__list">';

	foreach ($list as $key => $value) {
		$content = $value['content_list'];

		if ($status === true) {
			$stat = $value['status'];
		}

		if ($content) {
			$result .=
				'<li class="priceList__list__item d-flex">' .
				($status ? '<span class="priceList__list__item--status ' . $stat . '"></span>' : '') .
				'<p class="priceList__list__item--text">' . $content . '</p>';
			'</li>';
		}
	}

	$result .= '</ul>';

	return $result;
}
