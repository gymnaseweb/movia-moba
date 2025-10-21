<?php
namespace Movia;

require_once __DIR__ . '/inc/Setup.php';
require_once __DIR__ . '/inc/Blocks.php';
require_once __DIR__ . '/inc/Lockdown.php';

add_action('after_setup_theme', [Setup::class, 'init']);
add_action('wp_enqueue_scripts', [Setup::class, 'assets']);
add_action('acf/init', [Blocks::class, 'register']);

// Security/lockdown hooks:
add_action('admin_menu', [Lockdown::class, 'hide_site_editor']);
add_filter('allowed_block_types_all', [Lockdown::class, 'allowed_blocks'], 10, 2);
add_action('init', [Lockdown::class, 'remove_editor_unfiltered_html']); // important

add_action('wp_enqueue_scripts', function () {
  wp_register_script(
    'gsap',
    'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js',
    [],
    '3.12.2',
    ['in_footer' => true, 'strategy' => 'defer']
  );
  wp_enqueue_script(
    'movia-main',
    get_theme_file_uri('assets/js/main.js'),
    [],
    null,
    ['in_footer' => true, 'strategy' => 'defer']
  );

  wp_enqueue_script(
    'movia-header',
    get_theme_file_uri('assets/js/header-menu.js'),
    [],
    null,
    ['in_footer' => true, 'strategy' => 'defer']
  );
});

add_filter('register_post_type_args', function ($args, $post_type) {
  if ($post_type !== 'event') return $args;

  $args['template'] = [
    ['acf/event-single', ['align' => 'full'], []],
  ];
  $args['template_lock'] = 'all'; 

  $args['show_in_rest'] = true;

  return $args;
}, 10, 2);
