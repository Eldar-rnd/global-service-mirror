<?php
/**
 * Plugin Name: GS A11y (MU)
 * Description: Accessibility helpers: skip link and reduced motion safeguards.
 * Version: 0.4.0
 */
if (!defined('ABSPATH')) exit;

define('GS_A11Y_VER', '0.4.0');
define('GS_A11Y_URL', content_url('mu-plugins/gs-a11y'));

add_action('wp_enqueue_scripts', function() {
  if (is_admin()) return;
  wp_enqueue_style('gs-a11y', GS_A11Y_URL . '/assets/gs-a11y.css', [], GS_A11Y_VER);
}, 90);

add_action('wp_body_open', function() {
  if (is_admin()) return;
  echo '<a class="gs-skip" href="#gsMain">Перейти к содержимому</a>';
}, 1);
