<?php
$d = get_field('hero_image_desktop');
$m = get_field('hero_image_mobile');
$t = trim((string) get_field('hero_lead_text'));

$wrapper = !$is_preview
  ? get_block_wrapper_attributes(['class' => 'sec_banner_home'])
  : 'class="sec_banner_home"';
?>
<section <?php echo $wrapper; ?>>
  <div class="max_container div_banner_home">
    <?php if (!empty($d['id'])) {
      echo wp_get_attachment_image($d['id'], 'full', false, [
        'alt'      => esc_attr($d['alt'] ?? ''),
        'loading'  => 'eager',
        'decoding' => 'async',
        'sizes'    => '100vw'
      ]);
    } ?>
    <?php if (!empty($m['id'])) {
      echo wp_get_attachment_image($m['id'], 'full', false, [
        'class'    => 'banner_img_mob',
        'alt'      => esc_attr($m['alt'] ?? ($d['alt'] ?? '')),
        'loading'  => 'eager',
        'decoding' => 'async',
        'sizes'    => '100vw'
      ]);
    } ?>

    <div class="banner_title">
      <p>A</p>
      <p>B</p>
    </div>

    <div class="mm_logo_animate">
      <div class="connecting-line" id="connectingLine">
        <div class="arrow" id="arrow"></div>
      </div>
      <div class="text-group" id="movia">
        <span class="character">M</span>
        <span class="character">O</span>
        <span class="character">V</span>
        <span class="character">I</span>
        <span class="character circle-char" id="circleA">A</span>
      </div>
      <div class="text-group" id="moba">
        <span class="character">M</span>
        <span class="character">O</span>
        <span class="character circle-char" id="circleB">B</span>
        <span class="character">A</span>
      </div>
    </div>

    <div class="banner_text">
      <?php if ($t !== ''): ?><p><?php echo esc_html($t); ?></p><?php endif; ?>
      <a class="orange_btn" href="/contact">Nous contacter</a>
    </div>
  </div>
</section>
