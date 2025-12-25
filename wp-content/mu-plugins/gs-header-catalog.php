<?php
/**
 * Plugin Name: GS Header Catalog Enhancer (MU)
 * Description: Makes header "Каталог" lead to /catalog/ and adds a small adjacent button that opens the catalog overlay.
 * Version: 0.2.9
 */
if (!defined('ABSPATH')) { exit; }

define('GS_HC_VER', '0.2.9');
define('GS_HC_DIR', __DIR__ . '/gs-header-catalog');
define('GS_HC_URL', content_url('mu-plugins/gs-header-catalog'));

function gs_hc_is_front(): bool {
  if (is_admin()) return false;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return false;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return false;
  return true;
}

add_action('wp_enqueue_scripts', function() {
  if (!gs_hc_is_front()) return;

  wp_enqueue_style('gs-hc', GS_HC_URL . '/assets/gs-header-catalog.css', [], GS_HC_VER);
  wp_enqueue_script('gs-hc', GS_HC_URL . '/assets/gs-header-catalog.js', [], GS_HC_VER, true);

  $cfg = [
    'catalogUrl' => home_url('/catalog/'),
    'label' => 'Каталог',
  ];
  wp_add_inline_script('gs-hc', 'window.GS_HEADER_CATALOG=' . wp_json_encode($cfg, JSON_UNESCAPED_UNICODE) . ';', 'before');
}, 25);
