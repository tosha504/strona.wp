<?php
/**
 * Block Name: Faq
 */

$id = 'blockFaq-' . $block['id'];

// variables
$settings = get_field('settings');
$title = $settings['title'];
$content = $settings['content'];
$bg = $settings['background_color'] ?: '#fff';
$text_align = $settings['text_align'] ?: 'text-left';

$faqs = get_field('content');


?>
<div id="<?php echo $id; ?>" class="blockFaq alignfull" style="background-color: <?php echo esc_attr($bg); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12 <?php echo esc_attr($text_align); ?>">
                <?php 
                    if($title) echo '<h3 class="blockFaq--title">' . $title . '</h3>'; 
                    if($content) echo '<p class="blockFaq--text">' . $content . '</p>'; 
                ?>
            </div>

            <div class="col-12">
                <?php 
                    if($faqs){
                        echo '<ul class="blockFaq__faqs">';
                            foreach ($faqs as $key => $value) {
                                $que = $value['question'];
                                $answ = $value['answer'];

                                if($que && $answ){
                                    echo    '<li class="blockFaq__faqs__item">' . 
                                                '<a href="#faq-' . ($key + 1) . '" class="blockFaq__faqs__item--que"><span><strong>' . $que . '</strong></span></a>' . 
                                                '<p class="blockFaq__faqs__item--answ">' . $answ . '</p>' .
                                            '</li>';
                                }

                            }
                        echo '</ul>';
                    }
                ?>
            </div>

        </div>
    </div>
</div>