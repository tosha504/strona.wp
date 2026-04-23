<?php
/**
 * Block Name: Image with background image
 */

// get image field (array)
$id = 'imageBg-img-' . $block['id'];
// variable acf
$bg_image = get_field('background_image');
$bg_height = get_field('background_height');
$display_square = get_field('display_square');
$image = get_field('image');
$position_image = get_field('position_image');


?>
<div id="<?php echo $id; ?>" class="imageBg-img alignfull">

    <div class="imageBg-img__bgImage <?php echo $bg_height; ?>" <?php if($bg_image) echo 'style="background-image: url(' . $bg_image . ')"'; ?>>
        <?php 
            if($display_square == true){

                $size_square = get_field('size_square');
                $bg_square = get_field('background_square') ?: '#fff';
                $position_square = get_field('position_square');

                echo '<div class="imageBg-img__bgImage--square ' . $size_square . ' ' . $position_square .'" style="background-color: ' . $bg_square . ' ;"></div>';

            }
        ?>
    </div>

    <div class="imageBg-img__ImageBox <?php echo $position_image; ?>">
        <div class="imageBg-img__ImageBox--image">
            <?php if($image) echo wp_get_attachment_image($image, 'full'); ?>
        </div>
    </div>

</div>