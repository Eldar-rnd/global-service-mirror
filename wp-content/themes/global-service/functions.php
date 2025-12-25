<?php
if (!defined('ABSPATH')) { exit; }
define('GS_THEME_VERSION', '0.1.2');

add_action('after_setup_theme', function () {
  load_theme_textdomain('global-service', get_template_directory() . '/languages');
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
  register_nav_menus([
    'primary' => __('Primary Menu', 'global-service'),
    'footer'  => __('Footer Menu', 'global-service'),
  ]);
});

add_action('wp_enqueue_scripts', function () {
  $uri = get_template_directory_uri();
  wp_enqueue_style('gs-main', $uri . '/assets/css/main.css', [], GS_THEME_VERSION);
  wp_enqueue_script('gs-main', $uri . '/assets/js/main.js', [], GS_THEME_VERSION, true);

  $boot = [
    'siteUrl' => home_url('/'),
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'cities'  => [
      ['slug' => 'rostov-na-donu', 'name' => 'Ростов-на-Дону'],
      ['slug' => 'krasnodar',      'name' => 'Краснодар'],
      ['slug' => 'voronezh',       'name' => 'Воронеж'],
    ],
    'defaultCity' => 'rostov-na-donu',
  ];
  wp_add_inline_script('gs-main', 'window.GS_BOOT=' . wp_json_encode($boot, JSON_UNESCAPED_UNICODE) . ';', 'before');
});

// Defer main script
add_filter('script_loader_tag', function ($tag, $handle) {
  if ($handle !== 'gs-main') return $tag;
  return (false === strpos($tag, ' defer')) ? str_replace(' src=', ' defer src=', $tag) : $tag;
}, 10, 2);

function gs_inline_svg($name, $attrs = []) {
  $name = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', (string)$name);
  $path = get_template_directory() . '/assets/svg/' . $name . '.svg';
  if (!file_exists($path)) $path = get_template_directory() . '/assets/svg/default.svg';
  if (!file_exists($path)) return '';
  $svg = file_get_contents($path);
  if (!is_string($svg)) return '';

  if (!empty($attrs) && preg_match('/<svg\s[^>]*>/', $svg)) {
    $inject = '';
    foreach ($attrs as $k => $v) {
      $k = preg_replace('/[^a-zA-Z0-9\-_:]/', '', (string)$k);
      $v = esc_attr((string)$v);
      $inject .= ' ' . $k . '="' . $v . '"';
    }
    $svg = preg_replace('/<svg\s([^>]*)>/', '<svg $1' . $inject . '>', $svg, 1);
  }

  $svg = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $svg);
  return $svg;
}