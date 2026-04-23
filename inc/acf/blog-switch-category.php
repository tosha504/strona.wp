<?php
/**
 * Blog switch category
 *
 * @package Thenewlook
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function blog_switch_category(){
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $categories = get_field('select_categories', 'options');
    if(!$categories) return;
    ?>
        <div class="switchCategory">

            <ul class="switchCategory__list">
                <li class="switchCategory__list--item">
                    <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="switchCategory__list--item--link active" data-id="all">
                        <?php echo __('All posts', 'thenewlook'); ?>
                    </a>
                </li>
                <?php 
                    foreach ($categories as $key => $value) {
                        ?>
                            <li class="switchCategory__list--item">
                                <a href="<?php echo esc_url( get_category_link($value) ); ?>" class="switchCategory__list--item--link" data-id="<?php echo $value ?>">
                                    <?php echo get_cat_name($value); ?>
                                </a>
                            </li>
                        <?php
                    }
                ?>
            </ul>

        </div>
    <?php
}
add_action('blog_switch_category', 'blog_switch_category');


function api_blog_switch_category(){

    if( isset( $_REQUEST['data-id'] ) ){
        $id = $_REQUEST['data-id'];
    }else{
        die();
    }

    $args = array(
        'posts_per_page' => -1,
    );

    if($id != 'all') $args['cat'] = $id;

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ){ 
        echo '<div class="row">';
            while ( $query->have_posts() ) { $query->the_post(); 
                get_template_part( 'loop-templates/content', get_post_format() );
            } 
        echo '</div>';
    }else {
        get_template_part( 'loop-templates/content', 'none' );
    }

    wp_reset_postdata();

    die();
    ob_clean();

}

add_action('wp_ajax_api_blog_switch_category', 'api_blog_switch_category');
add_action('wp_ajax_nopriv_api_blog_switch_category', 'api_blog_switch_category');


function wpsites_no_limit_posts( $query ) {
    if( $query->is_main_query() && !is_admin() && is_home() ) {
        $query->set( 'posts_per_page', '-1' );   
    }   
}
add_action( 'pre_get_posts', 'wpsites_no_limit_posts' );