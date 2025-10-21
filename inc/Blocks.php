<?php
namespace Movia;

class Blocks {
  public static function register() {
    if (!function_exists('acf_register_block_type')) return;

    acf_register_block_type([
      'name'            => 'hero',
      'title'           => __('Hero (Full Screen)', 'movia'),
      'category'        => 'layout',
      'icon'            => 'slides',
      'mode'            => 'edit',
      'render_template' => get_template_directory() . '/blocks/hero.php',
      'supports'        => ['align'=>false, 'anchor'=>false, 'customClassName'=>false, 'multiple'=>false]
    ]);

    acf_register_block_type([
      'name'            => 'about',
      'title'           => __('About Text', 'movia'),
      'category'        => 'text',
      'icon'            => 'text-page',
      'mode'            => 'edit',
      'render_template' => get_template_directory() . '/blocks/about.php',
      'supports'        => ['align'=>false, 'customClassName'=>false]
    ]);
  }
}
