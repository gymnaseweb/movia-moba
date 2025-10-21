<?php
namespace Movia;

class Lockdown {
  public static function hide_site_editor() {
    if (!current_user_can('manage_options')) {
      remove_submenu_page('themes.php', 'site-editor.php');
    }
  }

  public static function allowed_blocks($allowed, $context) {
    return [
      'acf/hero',
      'acf/about',
    ];
  }

  public static function remove_editor_unfiltered_html() {
    $role = get_role('editor');
    if ($role && $role->has_cap('unfiltered_html')) {
      $role->remove_cap('unfiltered_html');
    }
  }
}
