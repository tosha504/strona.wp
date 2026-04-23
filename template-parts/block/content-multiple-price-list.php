<?php
/**
 * Block Name: Multiple price list
 */

$id = 'multiPricelist-' . $block['id'];

// variables
$settings      = get_field('settings');
$title_section = $settings['title_section'];
$columns       = $settings['columns'];
$text_align    = $settings['text_align'] ?: 'text-left';
$list          = get_field('price_list');

?>
<div id="<?php echo $id; ?>" class="multiPricelist">
    <div class="row justify-content-center">
        <div class="col-12">
            <?php if ($title_section)
                echo '<h3 class="multiPricelist--title">' . $title_section . '</h3>'; ?>
        </div>

        <?php
        foreach ($list as $key => $value) {
            echo '<div class="col-12 p-lg-0 mb-4 ' . $columns . ' ' . $text_align . '">' . single_price_list($value) . '</div>';
        }
        ?>

    </div>
</div>