<?php
$attrs = get_block_wrapper_attributes(['class' => 'campagnes_page']);

$title       = get_the_title();
$description = get_field('event_description', get_the_ID());
$banner      = get_field('event_image', get_the_ID());
$sponsor     = get_field('event_sponsor', get_the_ID());

$banner_src  = (is_array($banner) && isset($banner['sizes']['2048x2048'])) ? $banner['sizes']['2048x2048'] : '';
$banner_alt  = (is_array($banner) && isset($banner['alt'])) ? $banner['alt'] : '';
$sponsor_src = (is_array($sponsor) && isset($sponsor['sizes']['medium'])) ? $sponsor['sizes']['medium'] : '';
$sponsor_alt = (is_array($sponsor) && isset($sponsor['alt'])) ? $sponsor['alt'] : '';

$back_url    = get_post_type_archive_link('event');
?>
<section <?php echo $attrs; ?>>

  <section class="sec_banner_camp">
    <div class="fixed_container div_banner_camp">
      <?php if ($banner_src !== ''): ?>
        <img src="<?php echo esc_url($banner_src); ?>" alt="<?php echo esc_attr($banner_alt); ?>">
      <?php endif; ?>
    </div>
  </section>

  <section class="sec_coll_beau">
    <div class="fixed_container div_coll_beau">
      <div>
        <?php $terms = get_the_terms(get_the_ID(), 'event_type'); ?>
        <div class="camp_event_tags">
          <img src="<?php echo esc_url(get_theme_file_uri('img/tag-icon.png')); ?>" alt="tag-icon">
          <ul>
            <?php if ($terms && !is_wp_error($terms)): ?>
              <?php foreach ($terms as $t): ?>
                <li>
                  <a href="<?php echo esc_url(get_term_link($t)); ?>">
                    <?php echo esc_html($t->name); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </div>
        <h2><?php echo esc_html($title); ?></h2>
      </div>
      <div>
        <?php if ($description !== ''): ?>
          <p><?php echo esc_html($description); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="sec_accord_camp">
    <div class="fixed_container div_accord_camp">
      <div>
        <?php if (have_rows('event_block', get_the_ID())): ?>
          <div class="fiche_accordion camp_accordion">
            <ul>
              <?php while (have_rows('event_block', get_the_ID())): the_row();
                $item_title   = get_sub_field('title');
                $item_content = get_sub_field('content'); ?>
                <li class="accordion_item">
                  <div class="accordion_title">
                    <h3><?php echo esc_html($item_title); ?></h3>
                    <img src="<?php echo esc_url(get_theme_file_uri('img/plus-icon.png')); ?>" alt="plus-icon">
                  </div>
                  <div class="div_panel">
                    <p><?php echo wp_kses_post($item_content); ?></p>
                  </div>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if ($sponsor_src !== ''): ?>
          <p>Possible grâce au financement</p>
          <p><img src="<?php echo esc_url($sponsor_src); ?>" alt="<?php echo esc_attr($sponsor_alt); ?>"></p>
        <?php endif; ?>
      </div>

      <div class="prix_grn_box">
        <p>Prix leaders en mobilité durable</p>
        <h2>Inscrivez votre organisation dès maintenant!</h2>
        <a href="/">Je m’inscris</a>
      </div>
    </div>
  </section>

  <section class="sec_prev_nxt">
    <div class="div_prev_nxt">
      <a href="<?php echo esc_url($back_url); ?>">Retour</a>
      <?php next_post_link('%link', 'Campagne suivante'); ?>
    </div>
  </section>

</section>
