<?php
/**
 * Block Name: Icons with descriptions
 */

// get image field (array)
$id = 'iconsDescriptions-' . $block['id'];

$bg_color = get_field('background_color') ?: '#f7f7f9';

?>
<div id="<?php echo $id; ?>" class="iconsDescriptions alignfull" style="background-color: <?php echo $bg_color; ?>">
    <div class="container">
        <div class="row">
            <?php
                if( have_rows('icons') ){
                    while ( have_rows('icons') ){ the_row();

                        $icon = get_sub_field('icon');
                        $title = get_sub_field('title');
                        $decription = get_sub_field('description');

                        ?>
                            <div class="col-lg">
                                <div class="iconsDescriptions__object">
                                    <?php 
                                        if($icon) echo '<div class="iconsDescriptions__object--icon">' . wp_get_attachment_image($icon, 'full') . '</div>'; 
                                        if($title || $decription){
                                            echo '<div class="iconsDescriptions__object--content">';
                                                if($title) echo '<h3 class="iconsDescriptions__object--content__title">' . $title . '</h3>';
                                                if($decription) echo '<p class="iconsDescriptions__object--content__text">' . $decription . '</p>';
                                            echo '</div>';
                                        }
                                    ?>
                                </div>
                            </div>

                        <?php
                    }
                }
            ?>
        </div>
    </div>
</div>
