<?php
$attrs = get_block_wrapper_attributes(['class' => 'sec_nos_camp_home']);

$q = new WP_Query([
  'post_type'           => 'event',
  'posts_per_page'      => 3,
  'no_found_rows'       => true,
  'ignore_sticky_posts' => true,
  'orderby'             => 'date',
  'order'               => 'DESC',
]);
?>
<section <?php echo $attrs; ?>>
  <div class="max_container div_nos_camp_home">
    <div class="div_event_left">
      <span>Nos campagnes <br>et événements</span>
      <p>Mobiliser pour faire bouger les choses</p>
    </div>

    <div class="div_event_right">
      <?php if ($q->have_posts()): ?>
        <?php while ($q->have_posts()): $q->the_post();
          $img = get_field('event_image', get_the_ID());
          $src = is_array($img) ? ($img['sizes']['large'] ?? $img['url']) : '';
          $alt = is_array($img) ? ($img['alt'] ?? '') : '';
        ?>
          <div>
            <a href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($alt); ?>">
                <p><?php echo esc_html(get_the_title()); ?></p>
            </a>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php endif; ?>
    </div>
  </div>
</section>
