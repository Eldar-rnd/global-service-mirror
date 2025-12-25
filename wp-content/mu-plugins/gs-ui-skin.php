<?php
/**
 * Plugin Name: GS UI Skin (MU)
 * Description: Global UI skin tweaks (frontend). Softens the pure-black background to a light neutral tone. Safe, CSS-only.
 * Version: 0.5.4
 */
if (!defined('ABSPATH')) exit;

define('GS_UIS_VER', '0.5.4');
define('GS_UIS_URL', content_url('mu-plugins/gs-ui-skin'));

add_action('wp_enqueue_scripts', function() {
  if (is_admin()) return;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return;

  wp_enqueue_style('gs-ui-skin', GS_UIS_URL . '/assets/gs-ui-skin.css', [], GS_UIS_VER);
}, 5);
