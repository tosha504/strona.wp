<?php

/**
 * Custom functions
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

function add_image_size_new($name, $width = 0, $height = 0, $crop = false)
{
    global $_wp_additional_image_sizes;

    $_wp_additional_image_sizes[$name] = array(
        'width'  => absint($width),
        'height' => absint($height),
        'crop'   => $crop,
    );
}
add_image_size_new('hero-mobile', 750, 750, true);
add_image_size_new('post-content', 400, 250, true);
add_image_size_new('blog-single', 900, 500, true);
add_image_size_new('blog-single-full', 1210, 600, true);


function loader($class = null)
{
    $result =
        '<div class="theme-loader ' . esc_attr($class) . '">
            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
            <path d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                <animateTransform attributeType="xml"
                attributeName="transform"
                type="rotate"
                from="0 25 25"
                to="360 25 25"
                dur="0.6s"
                repeatCount="indefinite"/>
                </path>
            </svg>
        </div>';

    return $result;
}

function reading_time($id = null)
{

    if ($id == null)
        $id = get_the_ID();

    $content     = get_post_field('post_content', $id);
    $word_count  = str_word_count(strip_tags($content));
    $readingtime = ceil($word_count / 200);

    $totalreadingtime =
        '<span class="meta-post reading-time">' .
        file_get_contents((get_template_directory_uri() . '/public/icons/clock.svg')) .
        sprintf(_n('%s minute', '%s minutes', $readingtime, 'thenewlook'), $readingtime) .
        '</span>';

    return $totalreadingtime;
}

function post_date($id = null)
{
    if ($id == null)
        $id = get_the_ID();

    $result =
        '<span class="meta-post date">' .
        file_get_contents((get_template_directory_uri() . '/public/icons/calendar.svg')) .
        get_the_date('j M Y') .
        '</span>';

    return $result;
}

function style_title_edit_post_gutenberg()
{

    ?>
    <style>
        .wp-block.wp-block-post-title {
            background-color: #cabdd9;
            padding: 20px;
            font-size: 32px;
            margin-bottom: 60px;
            font-weight: bold;
        }
    </style>
    <?php
}

add_action('admin_head', 'style_title_edit_post_gutenberg');

// enable captcha js
add_action('wp_print_scripts', function () {
    if (!empty(get_field('enable_recaptcha'))) {
        wp_dequeue_script('google-recaptcha');
        wp_dequeue_script('wpcf7-recaptcha');
    }
});
