<?php
if (!defined('ABSPATH')) exit;

/**
 * Adds stable classes for mega-menu styling without depending on theme/plugin updates.
 * Marks certain top-level items (Shop, Questions) as mega dropdowns.
 */
class LM_Walker_Nav_Menu extends Walker_Nav_Menu {
  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    if (isset($item->title) && $depth === 0) {
      $title = trim(wp_strip_all_tags($item->title));
      if (in_array($title, ['Shop','Questions'], true)) {
        $item->classes[] = 'lm-mega';
      }
    }
    parent::start_el($output, $item, $depth, $args, $id);
  }
}
