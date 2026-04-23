<?php
/**
 * Block Name: Choose posts
 */

$id = 'choosePosts-' . $block['id'];

$choose_posts = get_field('choose_posts');
$single_post  = get_field('single_post');



?>
<div class="row">
    <?php foreach ($choose_posts as $key => $post) {
        $img = get_the_post_thumbnail($post->ID, 'post-content');
        ?>
        <div class="col-lg-<?php echo $single_post === true ? "12" : "4"; ?>">

            <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

                <div class="loopPostContent">

                    <?php

                    if ($img) { ?>

                        <div class="loopPostContent__thumbnail">
                            <a href="<?php echo esc_url(get_permalink($post)); ?>">
                                <?php echo $img ?>
                            </a>
                            <?php echo get_product_primary_category(get_the_ID()); ?>
                        </div>

                    <?php } ?>

                    <div class="loopPostContent__content">

                        <?php
                        echo sprintf('<h3 class="loopPostContent__content--title"><a href="%s" rel="bookmark">' . $post->post_title, esc_url(get_permalink($post))),
                            '</a></h3>';

                        echo '<p class="loopPostContent__content--text">' . get_the_excerpt($post) . '</p>';

                        echo '<div class="loopPostContent__content__btnArea"><a href="' . esc_url(get_permalink($post)) . '" class="btn btn-outline-primary loopContent">' . __('View post', 'thenewlook') . '</a></div>';
                        ?>

                    </div>

                </div>

            </article><!-- #post-## -->

        </div>
    <?php } ?>
</div>