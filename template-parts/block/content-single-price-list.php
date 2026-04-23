<?php
/**
 * Block Name: Single price list
 */

$id = 'singlePriceList-' . $block['id'];

// variables
$settings = get_field('settings');
$price_list = $settings['price_list'];
$text_align = $settings['text_align'] ?: 'text-left';

if (!$price_list) return;

?>
<div id="<?php echo $id; ?>" class="singlePriceList">
    <div class="singlePriceList__item <?php echo $text_align; ?>">
        <?php echo single_price_list($price_list); ?>
    </div>
</div>