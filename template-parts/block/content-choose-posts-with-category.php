<?php
/**
 * Block Name: Choose posts
 */
$id           = 'choosePosts-' . $block['id'];
$choose_posts = get_field('choose_posts_with_category');
$args         = array(
    'post_type'      => 'post',
    'category_name'  => $choose_posts[0]->slug,
    'posts_per_page' => -1,
    'order'          => 'DESC',
);
$query        = new WP_Query($args);
?>
<div class="row">
    <?php
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $img = get_the_post_thumbnail($query->ID, 'post-content');
            ?>
            <div class="col-lg-4">
                <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                    <div class="loopPostContent">
                        <?php
                        if ($img) { ?>
                            <div class="loopPostContent__thumbnail">
                                <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>">
                                    <?php echo $img ?>
                                </a>
                                <?php
                                echo get_product_primary_category(get_the_ID()); ?>
                            </div>
                        <?php } ?>
                        <div class="loopPostContent__content">
                            <?php
                            echo sprintf('<h3 class="loopPostContent__content--title"><a href="%s" rel="bookmark">' . get_the_title(get_the_ID()), esc_url(get_permalink())),
                                '</a></h3>';
                            echo '<p class="loopPostContent__content--text">' . get_the_excerpt(get_the_ID()) . '</p>';
                            echo '<div class="loopPostContent__content__btnArea"><a href="' . esc_url(get_permalink(get_the_ID())) . '" class="btn btn-outline-primary loopContent">' . __('View post', 'thenewlook') . '</a></div>';
                            ?>
                        </div>
                    </div>
                </article><!-- #post-## -->
            </div>
        <?php }
        wp_reset_postdata(); // Reset the post data
    } ?>
</div>