<?php
namespace Movia;

class Lockdown {
  public static function hide_site_editor() {
    if (!current_user_can('manage_options')) {
      remove_submenu_page('themes.php', 'site-editor.php');
    }
  }

  // Use the modern, non-deprecated filter and gate by post type.
  public static function allowed_blocks($allowed, $context) {
    // Default allow-list (pages, etc.)
    $default = [
      'acf/hero',
      'acf/home-about',
      'acf/home-services',
      'acf/home-events',
    ];

    // If we don't have a post context, keep default.
    if (empty($context->post)) {
      return $default;
    }

    // Events CPT: only allow the Event single block (and anything else you want there).
    if ($context->post->post_type === 'event') {
      return [
        'acf/event-single',
      ];
    }

    // Everything else
    return $default;
  }

  public static function remove_editor_unfiltered_html() {
    $role = get_role('editor');
    if ($role && $role->has_cap('unfiltered_html')) {
      $role->remove_cap('unfiltered_html');
    }
  }
}
