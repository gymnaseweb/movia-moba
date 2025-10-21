<?php
namespace Movia;

class Blocks {
  public static function register() {
    if (!function_exists('acf_register_block_type')) return;

acf_register_block_type([
  'name'            => 'hero',
  'title'           => __('Hero (Full Screen)', 'movia-moba'),
  'category'        => 'layout',
  'icon'            => 'slides',
  'mode'            => 'edit',
  'render_template' => get_stylesheet_directory() . '/blocks/hero.php',
  'align'           => 'full',
  'supports'        => [
    'align' => ['wide','full'],
    'anchor' => false,
    'customClassName' => false,
    'multiple' => false,
  ],
]);


  }
}
