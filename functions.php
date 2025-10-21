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
