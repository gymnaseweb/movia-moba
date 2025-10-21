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
    acf_register_block_type([
      'name'            => 'home-about',
      'title'           => __('Home - About', 'movia-moba'),
      'category'        => 'layout',
      'icon'            => 'slides',
      'mode'            => 'edit',
      'render_template' => get_stylesheet_directory() . '/blocks/home-about.php',
      'align'           => 'full',
      'supports'        => [
        'align' => ['wide','full'],
        'anchor' => false,
        'customClassName' => false,
        'multiple' => false,
      ],
    ]);
    acf_register_block_type([
      'name'            => 'home-services',
      'title'           => __('Home - Services', 'movia-moba'),
      'category'        => 'layout',
      'icon'            => 'slides',
      'mode'            => 'edit',
      'render_template' => get_stylesheet_directory() . '/blocks/home-services.php',
      'align'           => 'full',
      'supports'        => [
        'align' => ['wide','full'],
        'anchor' => false,
        'customClassName' => false,
        'multiple' => false,
      ],
    ]);
    acf_register_block_type([
      'name'            => 'home-events',
      'title'           => __('Home - Events', 'movia-moba'),
      'category'        => 'layout',
      'icon'            => 'slides',
      'mode'            => 'edit',
      'render_template' => get_stylesheet_directory() . '/blocks/home-events.php',
      'align'           => 'full',
      'supports'        => [
        'align' => ['wide','full'],
        'anchor' => false,
        'customClassName' => false,
        'multiple' => false,
      ],
    ]);

    acf_register_block_type([
      'name'            => 'event-single',
      'title'           => __('Event – Single', 'movia'),
      'category'        => 'layout',
      'icon'            => 'calendar',
      'mode'            => 'preview',
      'render_template' => get_theme_file_path('blocks/event-single.php'),
      'supports'        => [
        'align' => ['full', 'wide'],
      ],
      'align'           => 'full',
    ]);
  }
}
