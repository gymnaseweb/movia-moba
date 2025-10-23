<?php
$title = get_field('title');
$text = get_field('text');

$attrs = get_block_wrapper_attributes(['class' => 'sec_propos_home']);
?>
<section <?php echo $attrs; ?>>
    <div class="max_container">
        <div class="div_propos_home">
            <span><?php echo esc_html($title); ?></span>
            <p><?php echo esc_html($text); ?></p>
            <div class="ta_right"><a class="orange_btn" href="#">En savoir plus sur nous</a></div>
        </div>
    </div>
</section>