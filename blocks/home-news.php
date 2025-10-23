<?php
$attrs = get_block_wrapper_attributes(['class' => 'sec_actual_home']);
$q = new WP_Query([
  'post_type'           => 'news',
  'posts_per_page'      => 3,
  'no_found_rows'       => true,
  'ignore_sticky_posts' => true,
  'orderby'             => 'date',
  'order'               => 'DESC',
]);
?>
<section <?php echo $attrs; ?>>
  <div class="max_container ">
    <div class="div_actual_home">
      <div class="div_actual_left">
        <span>Actualités</span>
        <p>Derniers articles ou mémoires</p>
      </div>

      <div class="div_actual_right">
        <?php if ( $q->have_posts() ) : ?>
          <?php while ( $q->have_posts() ) : $q->the_post();
            $img = get_field('news_image', get_the_ID());
            $src = is_array($img) ? ($img['sizes']['large'] ?? $img['url']) : '';
            $alt = is_array($img) ? ($img['alt'] ?? '') : '';
            ?>
            <div>
              <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($alt); ?>">
              <p><?php echo esc_html(get_the_title()); ?></p>
            </div>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        <?php endif; ?>
      </div>

      <div class="div_actual_btn">
        <a class="orange_btn" href="/">Voir toutes nos publications et outils</a>
      </div>
    </div>
  </div>
</section>
