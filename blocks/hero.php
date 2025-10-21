<?php
$img_id    = get_field('image');
$cta_text  = trim((string) get_field('text'));

?>
<section class="hero" aria-label="<?php echo esc_attr__('Homepage hero', 'movia'); ?>">
  <div class="hero__media">
    <?php if ($img_id) {
      echo wp_get_attachment_image($img_id, 'full', false, [
        'class' => 'image',
        'decoding' => 'async',
        'loading'  => 'eager',
        'alt'      => ''
      ]);
    } ?>
  </div>

  <div class="hero__overlay">
    <?php if ($cta_text): ?>
      <a><?php echo esc_html($cta_text); ?></a>
    <?php endif; ?>
  </div>
</section>
