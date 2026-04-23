<?php
/**
 * Mobile menu helpers
 *
 * @package Thenewlook
 */

defined('ABSPATH') || exit;

function tnl_mobile_menu_add_toggle_class(array $items, stdClass $args): array
{
    if (($args->theme_location ?? '') !== 'mobile') {
        return $items;
    }

    foreach ($items as &$item) {
        $classes = is_array($item->classes) ? $item->classes : [];
        $is_mega = (bool) get_field('enable_mega_menu', $item);

        if ($is_mega) {
            continue;
        }

        if (in_array('menu-item-has-children', $classes, true)) {
            $item->classes[] = 'submenu--toggle';
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'tnl_mobile_menu_add_toggle_class', 10, 2);
