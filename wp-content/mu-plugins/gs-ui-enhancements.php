<?php
/**
 * Plugin Name: GS UI Enhancements (MU)
 * Description: Frontend UX helpers: persist selected city (cookie) and add mobile bottom bar for quick navigation.
 * Version: 0.5.4
 */
if (!defined('ABSPATH')) exit;

define('GS_UIE_VER', '0.5.4');
define('GS_UIE_URL', content_url('mu-plugins/gs-ui-enhancements'));

function gs_uie_is_front(): bool {
  if (is_admin()) return false;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return false;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return false;
  return true;
}

add_action('init', function() {
  if (!gs_uie_is_front()) return;

  // Persist city selection from query param.
  if (isset($_GET['gs_city'])) {
    $city = (string)wp_unslash($_GET['gs_city']);
    $city = trim($city);
    if ($city !== '') {
      // Store raw (utf-8) but cookie value must be ASCII-safe; urlencode.
      $val = rawurlencode($city);
      setcookie('gs_city', $val, [
        'expires'  => time() + 60*60*24*30,
        'path'     => '/',
        'secure'   => is_ssl(),
        'httponly' => false,
        'samesite' => 'Lax',
      ]);
      $_COOKIE['gs_city'] = $val;
    }
  }
}, 1);

add_action('wp_enqueue_scripts', function() {
  if (!gs_uie_is_front()) return;

  wp_enqueue_style('gs-uie', GS_UIE_URL . '/assets/gs-ui-enhancements.css', [], GS_UIE_VER);
  wp_enqueue_script('gs-uie', GS_UIE_URL . '/assets/gs-ui-enhancements.js', [], GS_UIE_VER, true);

  $city = '';
  if (!empty($_COOKIE['gs_city'])) {
    $city = rawurldecode((string)$_COOKIE['gs_city']);
  }
  wp_add_inline_script('gs-uie', 'window.GS_CTX=' . wp_json_encode([
    'city' => $city,
  ], JSON_UNESCAPED_UNICODE) . ';', 'before');
}, 50);
