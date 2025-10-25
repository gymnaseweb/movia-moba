<?php
$attrs = get_block_wrapper_attributes(['class' => 'sec_nos_serv_home']);
?>
<section <?php echo $attrs; ?>>
  <div class="max_container ">
    <div class="div_nos_serv_home">
    <div class="bg" aria-hidden="true"></div>   <!-- blue rectangle behind -->
    <div class="content">                        <!-- all your real content -->
        <?php
          $title       = get_field('title');
          $description = get_field('text');
        ?>

        <?php if ($title !== ''): ?>
          <span class="nos_serv_title"><?php echo esc_html($title); ?></span>
        <?php endif; ?>

      <div class="mid_nos_serv_home">
        <?php if ($description !== ''): ?>
          <p><?php echo esc_html($description); ?></p>
        <?php endif; ?>

        <div class="nos_serv_boxes">
          <?php for ($i = 1; $i <= 4; $i++): ?>
            <?php
              $group      = get_field("service_{$i}");
              $item_title = is_array($group) && !empty($group['title']) ? $group['title'] : '';
              $item_svg   = is_array($group) && !empty($group['svg'])   ? $group['svg']   : '';
            ?>
            <?php if ($item_title !== '' || $item_svg !== ''): ?>
              <div class="nos_serv_box">
                <?php echo $item_svg; ?>
                <?php if ($item_title !== ''): ?>
                  <p><?php echo esc_html($item_title); ?></p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
      </div>

      <div class="ta_right">
        <a data-theme="light" data-btn-hover href="/services" class="btn w-inline-block">
          <div class="btn__bg"></div>
          <div class="btn__circle-wrap">
            <div class="btn__circle">
              <div class="before__100"></div>
            </div>
          </div>
          <div class="btn__text">
            <p class="btn-text-p">Voir tous nos services</p>
          </div>
        </a>
      </div>
    </div>
  </div>
  </div>
</section>
