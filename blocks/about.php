<?php
$heading  = trim((string) get_field('about_heading'));
$text     = (string) get_field('about_text');
$cta_text = trim((string) get_field('about_cta_text'));
$cta_url  = esc_url((string) get_field('about_cta_url'));

// If you want plain text only (safest):
$render_text = wpautop( esc_html( $text ) );

// If you prefer limited formatting, swap the line above for this allowlist:
// $render_text = wpautop( wp_kses( $text, [
//   'a' => ['href'=>[], 'title'=>[], 'rel'=>[], 'target'=>[]],
//   'strong' => [], 'em' => [], 'br' => [], 'p' => []
// ]) );
?>
<section class="about">
  <?php if ($heading): ?><p class="about__eyebrow"><?php echo esc_html($heading); ?></p><?php endif; ?>
  <div class="about__text"><?php echo $render_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
  <?php if ($cta_text && $cta_url): ?><p class="about__cta"><a class="btn btn--secondary" href="<?php echo $cta_url; ?>"><?php echo esc_html($cta_text); ?></a></p><?php endif; ?>
</section>
