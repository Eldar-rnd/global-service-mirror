<?php
/**
 * Plugin Name: GS UI Skin (MU)
 * Description: Global UI skin tweaks (frontend). Provides light neutral base, design tokens and safe accessibility primitives.
 * Version: 0.5.5
 */
if (!defined('ABSPATH')) exit;

define('GS_UIS_VER', '0.5.5');
define('GS_UIS_URL', content_url('mu-plugins/gs-ui-skin'));

add_action('wp_enqueue_scripts', function() {
  if (is_admin()) return;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return;

  if (!apply_filters('gs_ui_skin_enabled', true)) return;

  // Enqueue late so tokens win over theme/plugin defaults.
  wp_enqueue_style('gs-ui-skin', GS_UIS_URL . '/assets/gs-ui-skin.css', [], GS_UIS_VER);

  // Fallback: ensure background is never pure black even if other CSS loads later.
  $critical = "html{background:var(--gs-bg,#F6F7F9);}body{background-color:var(--gs-bg,#F6F7F9)!important;color:var(--gs-text,rgba(17,19,22,.92));color-scheme:light;}";
  wp_add_inline_style('gs-ui-skin', $critical);
}, 999);
