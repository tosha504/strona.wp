<?php
/**
 * Mega menu
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// display shortcode
// add_filter('walker_nav_menu_start_el', function($item_output, $item) {

//     if (!is_object($item) || !isset($item->object)) {
//         return $item_output;
//     }

// 	$shortcode = get_field('shortcode_menu', $item->ID);
//     if ($shortcode) {
//         $item_output = $item_output . do_shortcode( $shortcode );
//     }

//     return $item_output;
// }, 20, 2);

//add class to item menu
function my_wp_nav_menu_objects($items, $args)
{

    foreach ($items as &$item) {
        $shortcode = get_field('shortcode_menu', $item);
        if ($shortcode) {
            $item->classes[] = 'is-megamenu';
        }
    }
    return $items;
}
// add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);

//post type mega menu
function megamenu_post_type()
{
    $labels = array(
        'name'          => __('Mega menu', 'thenewlook'),
        'singular_name' => __('Mega menu', 'thenewlook'),
        'menu_name'     => __('Mega menu', 'thenewlook'),
    );
    $args   = array(
        'label'               => 'Mega menu',
        'rewrite'             => array(
            'slug' => 'mega-menu'
        ),
        'description'         => 'Mega menu',
        'labels'              => $labels,
        'taxonomies'          => array(),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_icon'           => 'dashicons-editor-ol',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
    );
    register_post_type('megamenu', $args);
}
// add_action( 'init', 'megamenu_post_type', 0 );

//add column with shorcode
function add_mege_menu_columns($columns)
{
    $column_meta = array('mega_menu' => 'shortcode');
    $columns     = array_slice($columns, 0, 6, true) + $column_meta + array_slice($columns, 6, NULL, true);
    return $columns;
}

function mega_menu_columns($column)
{
    global $post;
    switch ($column) {
        case 'mega_menu':
            $hits  = '[mega_menu id="';
            $hits .= $post->ID;
            $hits .= '"]';
            echo $hits;
            break;
    }
}

add_filter('manage_megamenu_posts_columns', 'add_mege_menu_columns');
add_action('manage_posts_custom_column', 'mega_menu_columns');

//mega-menu construct
function mega_menu($atts, $content = null)
{
    $atts = shortcode_atts(
        array(
            'id' => '',
        ),
        $atts
    );
    if ($atts['id']) {

        $html = mega_menu_display($atts['id']);
    }

    return $html;
}
add_shortcode('mega_menu', 'mega_menu');

//mega-menu-shortcode
function mega_menu_display($id)
{
    ob_start();
    ?>
    <div class="mega_menu">
        <div class="container d-block">
            <div class="row">
                <?php
                if (have_rows('menu_mm', $id)) {
                    ?>
                    <div class="col-lg">
                        <div class="megaMenu">
                            <div class="row">
                                <?php
                                while (have_rows('menu_mm', $id)) {
                                    the_row();
                                    repeat_megamenu();
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                mega_menu_adds($id);
                ?>
            </div>
        </div>
    </div>
    <?php

    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function mega_menu_adds($id)
{
    $adds = get_field('adds_mm', $id);
    if ($adds && get_field('display_addds', $id) == true) {
        $title         = $adds['title_ads_mm'];
        $description   = $adds['description_ads_mm'];
        $related_posts = $adds['related_posts_ads_mm'];
        $link          = $adds['button_ads_mm'];
        ?>
        <div class="col-lg-3 pl-0">
            <div class="megaMenu__adds h-100">
                <div class="megaMenu__adds--header">
                    <?php
                    if ($title)
                        echo '<span class="megaMenu__adds--header__title d-block">' . $title . '</span>';
                    if ($description)
                        echo '<p class="megaMenu__adds--header__description">' . $description . '</p>';
                    ?>
                </div>
                <div class="megaMenu__adds--content">
                    <?php
                    if ($related_posts) {
                        echo '<ul class="megaMenu__adds--content__list">';
                        foreach ($related_posts as $post) {
                            ?>
                            <li class="megaMenu__adds--content__list--item d-flex">
                                <div class="row align-items-center">
                                    <div class="col-3 pr-0">
                                        <a href="<?php echo get_permalink($post) ?>" title="<?php echo get_the_title($post) ?>">
                                            <?php echo get_the_post_thumbnail($post, 'thumbnail'); ?>
                                        </a>
                                    </div>
                                    <div class="col-9 pl-2">
                                        <a href="<?php echo get_permalink($post) ?>" title="<?php echo get_the_title($post) ?>">
                                            <span> <?php echo get_the_title($post) ?> </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                        echo '</ul>';
                    }
                    ?>
                </div>
                <?php
                if ($link) {
                    $link_url    = $link['url'];
                    $link_title  = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <div class="megaMenu__adds--footer">
                        <a class="btn btn-primary" href="<?php echo esc_url($link_url); ?>"
                            target="<?php echo esc_attr($link_target); ?>">
                            <?php echo esc_html($link_title); ?>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
}

function repeat_megamenu()
{
    $icon         = get_sub_field('icon_mm');
    $primary_link = get_sub_field('primary_link_mm');
    $description  = get_sub_field('description');
    ?>
    <div class="col-lg">
        <div class="megaMenu__item">
            <div class="megaMenu__item--header">
                <?php
                if ($icon)
                    echo '<div class="megaMenu__item--header__icon">' . wp_get_attachment_image($icon) . '</div>';
                if ($primary_link) {
                    $primary_link_url    = $primary_link['url'];
                    $primary_link_title  = $primary_link['title'];
                    $primary_link_target = $primary_link['target'] ? $primary_link['target'] : '_self';
                    ?>
                    <span class="megaMenu__item--header__link">
                        <a href="<?php echo esc_url($primary_link_url); ?>"
                            target="<?php echo esc_attr($primary_link_target); ?>">
                            <?php echo esc_html($primary_link_title); ?>
                        </a>
                    </span>
                    <?php
                }
                if ($description)
                    echo '<p class="megaMenu__item--header__description">' . $description . '</p>';
                ?>
            </div>
            <div class="megaMenu__item--submenu">
                <?php
                if (have_rows('submenu_mm')) {
                    echo '<ul class="megaMenu__item--submenu__list">';
                    while (have_rows('submenu_mm')) {
                        the_row();
                        $sublink = get_sub_field('link_submenu_mm');
                        if ($sublink) {
                            $sublink_url    = $sublink['url'];
                            $sublink_title  = $sublink['title'];
                            $sublink_target = $sublink['target'] ? $link['target'] : '_self';
                            $sublink_style  = get_sub_field('style_as_a_button') ? 'btn btn-primary' : '';
                            ?>
                            <li>
                                <a class="megaMenu__item--submenu__list--item <?php echo $sublink_style ?>"
                                    href="<?php echo esc_url($sublink_url); ?>" target="<?php echo esc_attr($sublink_target); ?>">
                                    <?php echo esc_html($sublink_title); ?>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    echo '</ul>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}
