<?php
/**
 * Plugin Name: GS Error Pages (MU)
 * Description: Friendly 404 page for frontend stage.
 * Version: 0.4.6
 */
if (!defined('ABSPATH')) exit;

define('GS_ERR_VER', '0.4.6');
define('GS_ERR_PATH', __DIR__ . '/gs-error-pages');
define('GS_ERR_URL', content_url('mu-plugins/gs-error-pages'));

function gs_err_disabled(): bool {
  return file_exists(WP_CONTENT_DIR . '/gs-disable-error-pages');
}

add_action('wp_enqueue_scripts', function(){
  if (gs_err_disabled()) return;
  if (is_admin()) return;
  if (function_exists('is_404') && is_404()) {
    wp_enqueue_style('gs-error-pages', GS_ERR_URL . '/assets/gs-error.css', [], GS_ERR_VER);
  }
}, 40);

add_filter('template_include', function($template){
  if (gs_err_disabled()) return $template;
  if (is_admin()) return $template;
  if (function_exists('is_404') && is_404()) {
    $t = GS_ERR_PATH . '/templates/404.php';
    return file_exists($t) ? $t : $template;
  }
  return $template;
}, 80);