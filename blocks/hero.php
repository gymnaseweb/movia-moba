<?php
$d = get_field('hero_image_desktop');
$m = get_field('hero_image_mobile');
$t = trim((string) get_field('hero_lead_text'));

$wrapper = !$is_preview
  ? get_block_wrapper_attributes(['class' => 'sec_banner_home resource-wrapper'])
  : 'class="sec_banner_home resource-wrapper"';
?>
<section <?php echo $wrapper; ?>>

  <!-- SECTION 1: BIG (fullscreen) hero wrapper -->
  <section class="scaling-element-header">
    <div class="scaling-element__big-box">
      <div class="scaling-hero__before"></div>
      <div data-flip-element="wrapper" class="scaling-hero__wrapper">
        <!-- Flip TARGET: put your whole hero inside so it scales together -->
        <div data-flip-element="target" class="scaling-hero">
          <div class="hero_max_container">
            <div class="div_banner_home">

              <?php if (!empty($d['id'])) {
                echo wp_get_attachment_image($d['id'], 'full', false, [
                  'alt'      => esc_attr($d['alt'] ?? ''),
                  'loading'  => 'eager',
                  'decoding' => 'async',
                  'sizes'    => '100vw',
                  'class'    => 'banner_img_desktop'
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

              <div class="banner_overlay">
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

            </div>
          </div>
        </div>
        <!-- /Flip TARGET -->
      </div>
    </div>
  </section>

  <!-- SECTION 2: SMALL destination card -->
  <section class="scaling-element-video">
    <div class="scaling-element__small-box">
      <div class="scaling-hero__before"></div>
      <div data-flip-element="wrapper" class="scaling-hero__wrapper"></div>
    </div>
  </section>

</section>
