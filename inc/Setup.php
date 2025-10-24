<?php
namespace Movia;

class Setup {
  public static function init() {
    load_theme_textdomain('movia', get_template_directory() . '/languages');
    add_theme_support('editor-styles');
    add_editor_style('assets/css/main.css');
    add_theme_support('html5', ['style','script','gallery','caption','comment-list','comment-form']);
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
  }

  public static function assets() {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('movia-main-style', get_theme_file_uri('assets/css/main.css'), [], $ver);
    wp_enqueue_script('jquery');
    // wp_enqueue_script('movia-main-script', get_theme_file_uri('assets/js/main.js'), [], $ver, true);
  }
}
