<?php
/**
 * Plugin Name: GS Homepage (MU)
 * Description: Frontend-only homepage for the build stage. Uses Smart Search/Catalog overlay and avoids fake claims.
 * Version: 0.4.6
 */
if (!defined('ABSPATH')) exit;

define('GS_HOME_VER', '0.4.6');
define('GS_HOME_PATH', __DIR__ . '/gs-homepage');
define('GS_HOME_URL', content_url('mu-plugins/gs-homepage'));

function gs_home_disabled(): bool {
  // Emergency switch: create empty file wp-content/gs-disable-homepage
  return file_exists(WP_CONTENT_DIR . '/gs-disable-homepage');
}

add_action('wp_enqueue_scripts', function(){
  if (gs_home_disabled()) return;
  if (is_admin()) return;
  // Enqueue only on front page/home.
  if (!is_front_page() && !is_home()) return;
  if (function_exists('gs_ld_is_demo_request') && gs_ld_is_demo_request()) return;

  wp_enqueue_style('gs-homepage', GS_HOME_URL . '/assets/gs-home.css', [], GS_HOME_VER);
}, 30);

add_filter('template_include', function($template){
  if (gs_home_disabled()) return $template;
  if (is_admin()) return $template;
  if (!is_front_page() && !is_home()) return $template;
  if (function_exists('gs_ld_is_demo_request') && gs_ld_is_demo_request()) return $template;

  $t = GS_HOME_PATH . '/templates/home.php';
  return file_exists($t) ? $t : $template;
}, 60);

add_filter('pre_get_document_title', function($title){
  if (gs_home_disabled()) return $title;
  if (!is_front_page() && !is_home()) return $title;
  if (function_exists('gs_ld_is_demo_request') && gs_ld_is_demo_request()) return $title;

  return 'Global Service — ремонт техники, диагностика и гарантия';
}, 20);