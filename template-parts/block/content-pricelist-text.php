<?php
/**
 * Block Name: Single price list & content
 */

$id = 'priceListText-' . $block['id'];

$settings = get_field('settings');
$bg_color = $settings['background_color'] ?: '#f7f7f9';
$grid = $settings['grid'] ?: 'col-lg-8';
$text_align_content = $settings['text_align_content'] ?: 'text-left';
$text_align_price_list = $settings['text_align_price_list'] ?: 'text-left';

$price_list = get_field('price_list');
$id_price = $price_list['price_list'];

$content = get_field('content');
$title = $content['title'];
$title_style = $content['title_style'] ?: 'h3';
$text = $content['text'];

?>
<div id="<?php echo $id; ?>" class="priceListText alignfull" style="background-color: <?php echo $bg_color; ?>">
    <div class="container">
        <div class="row">

            <div class="col-12 <?php echo $grid . ' ' . $text_align_content; ?>">
                <div class="priceListText__content">
                    <?php
                        if($title) echo '<' . $title_style . ' class="priceListText__content--title">' . $title . '</' . $title_style . '>';
                        if($text) echo '<div class="priceListText__content__text">' . $text . '</div>';
                    ?>
                </div>
            </div>

            <div class="col-12 col-lg">
                <div class="priceListText__price <?php echo $text_align_price_list; ?>">
                    <?php if($id_price) echo single_price_list($id_price); ?>
                </div>
            </div>

        </div>
    </div>
</div>