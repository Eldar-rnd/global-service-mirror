<?php
/**
 * Plugin Name: GS UI Skin (MU)
 * Description: Global UI skin tweaks (frontend). Softens the pure-black background to a light neutral tone. Safe, CSS-only.
 * Version: 0.5.9
 */
if (!defined('ABSPATH')) exit;

define('GS_UIS_VER', '0.5.9');
define('GS_UIS_URL', content_url('mu-plugins/gs-ui-skin'));


function gs_uis_disabled(): bool {
  // Emergency switch: create empty file wp-content/gs-disable-ui-skin
  return defined('WP_CONTENT_DIR') && file_exists(WP_CONTENT_DIR . '/gs-disable-ui-skin');
}

add_action('wp_enqueue_scripts', function() {
  if (is_admin()) return;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return;
  if (gs_uis_disabled()) return;

  // Enqueue late to override theme styles reliably.
  wp_enqueue_style('gs-ui-skin', GS_UIS_URL . '/assets/gs-ui-skin.css', [], GS_UIS_VER);
}, 999);

// Hard fallback: enforce light background even if some later CSS forces dark.
add_action('wp_head', function() {
  if (is_admin()) return;
  if (gs_uis_disabled()) return;
  echo "<style id='gs-ui-skin-force'>"
    . "html body{background-color:var(--gs-bg) !important;color-scheme:light;}"
    . "html body{background-image:"
    . "radial-gradient(980px 700px at 16% -12%, rgba(210,5,30,.08), transparent 60%),"
    . "radial-gradient(920px 640px at 92% -12%, rgba(17,19,22,.06), transparent 62%),"
    . "radial-gradient(760px 540px at 56% 118%, rgba(210,5,30,.05), transparent 62%) !important;}"
    . "</style>";
}, 9999);

add_filter('body_class', function($classes) {
  if (gs_uis_disabled()) return $classes;
  // Prefer explicit light skin class; remove known dark markers.
  $drop = ['gs-skin-dark','gs-dark','is-dark','theme-dark'];
  $classes = array_values(array_diff($classes, $drop));
  if (!in_array('gs-skin-light', $classes, true)) $classes[] = 'gs-skin-light';
  return $classes;
}, 50);
