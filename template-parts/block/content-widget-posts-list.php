<?php
/**
 * Block Name: Widget posts list
 */

$id = 'widgetPostList-' . $block['id'];

// variables
$settings = get_field('settings');
$data = $settings['data'];
$how_many = $settings['max_posts'];

$banner = get_field('banner');
$d_banner = $banner['display_banner'];

$args = array(
    'posts_per_page' => $how_many,
);

if($data == 'cat'){
    
    $cat = $settings['category'] ?: 1;
    $args['cat'] = $cat;

}elseif($data == 'tag'){

    $tag = $settings['tag'] ?: 1;
    $args['tag_id'] = $tag;
    
}

$query = new WP_Query( $args );
if ( $query->have_posts() ){ 
    ?>
        <div id="<?php echo $id; ?>" class="widgetPostList">

            <?php
                if($d_banner){
                    $bg = $banner['background_image'];
                    $title = $banner['title'];
                    $link = $banner['link'];

                    echo '<div class="widgetPostList__header" ' . ($bg ? 'style="background-image: linear-gradient(0deg, rgba(24, 24, 24, 0.6), rgba(24, 24, 24, 0.6)), url(' . wp_get_attachment_image_url($bg, 'medium') . ');"' : '' ) . '>';

                        if($link) echo '<a href="' . esc_url($link) . '">';

                            if($title) echo '<h4 class="widgetPostList__header--title">' . esc_attr($title) . '</h4>';

                        if($link) echo '</a>';

                    echo '</div>';

                }
            ?>


        
            <ul class="widgetPostList__list">
                <?php while ( $query->have_posts() ) { $query->the_post(); ?>

                    <li class="widgetPostList__list__item">
                        <div class="row align-items-center">
                            <div class="col-3 pr-0">
                                <a href="<?php echo get_permalink() ?>" class="widgetPostList__list__item--image" title="<?php echo get_the_title() ?>">
                                    <?php echo get_the_post_thumbnail('', 'thumbnail'); ?>
                                </a>
                            </div>
                            <div class="col-9 pl-2">
                                <a href="<?php echo get_permalink() ?>" class="widgetPostList__list__item--title" title="<?php echo get_the_title() ?>">
                                    <span> <?php echo get_the_title() ?> </span>
                                </a>
                            </div>
                        </div>
                    </li>

                <?php } ?>
            </ul>

        </div>
    <?php
    wp_reset_postdata();
}

