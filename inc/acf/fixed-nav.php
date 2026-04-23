<?php
/**
 * Side icon navigation
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function side_icon_navigation(){
    $settings = get_field('side_nav', 'options');

    if($settings){

        $display = $settings['display'];
        $nav = $settings['navigation'];

        if($display && $nav){
            ?>
                <nav class="sideFixedNav">
                    <ul class="sideFixedNav__list">
                        <?php 
                            foreach ($nav as $key => $value) {

                                $icon = $value['icon'];
                                $title = $value['title'];
                                $link = $value['link'];

                                if($icon && $title && $link){

                                    $target = $value['open_in_new_cart'] ? '_blank' : '_self';

                                    ?>
                                        <li class="sideFixedNav__list--item">
                                            <a href="<?php echo esc_url($link); ?>" target="<?php echo $target; ?>" title="<?php echo esc_attr($title); ?>" class="sideFixedNav__list--link">
                                                <span class="sideFixedNav__list--link-img"><?php echo wp_get_attachment_image($icon); ?></span>
                                            </a>
                                        </li>
                                    <?php
                                }

                            }
                        ?>
                    </ul>
                </nav>

            <?php
        }

    }

}

add_action('wp_footer', 'side_icon_navigation');

