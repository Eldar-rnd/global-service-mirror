<?php
/**
 * Plugin Name: GS Static Pages (MU)
 * Description: Virtual marketing pages for frontend stage: /services/, /filialy/, /garantiya/, /contacts/, /search/. Also normalizes header links if they are placeholders.
 * Version: 0.5.4
 */
if (!defined('ABSPATH')) { exit; }

define('GS_SP_VER', '0.5.4');
define('GS_SP_PATH', __DIR__ . '/gs-static-pages');
define('GS_SP_URL', content_url('mu-plugins/gs-static-pages'));

function gs_sp_disabled(): bool {
  // Emergency switch: create empty file wp-content/gs-disable-static-pages
  return file_exists(WP_CONTENT_DIR . '/gs-disable-static-pages');
}

function gs_sp_is_front(): bool {
  if (is_admin()) return false;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return false;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return false;
  return true;
}

function gs_sp_norm_path(): string {
  $uri = $_SERVER['REQUEST_URI'] ?? '/';
  $path = parse_url((string)$uri, PHP_URL_PATH);
  $path = is_string($path) ? $path : '/';
  $path = '/' . trim($path, '/') . '/';
  return $path;
}

function gs_sp_route(): array {
  // path => template
  return [
    '/services/'   => 'services.php',
    '/filialy/'    => 'filialy.php',
    '/garantiya/'  => 'garantiya.php',
    '/contacts/'   => 'contacts.php',
    '/search/'     => 'search.php',
  ];
}

function gs_sp_current_template(): ?string {
  if (!gs_sp_is_front() || gs_sp_disabled()) return null;
  $path = gs_sp_norm_path();
  $routes = gs_sp_route();
  return $routes[$path] ?? null;
}

function gs_sp_current_slug(): ?string {
  $path = gs_sp_norm_path();
  return trim($path, '/');
}

add_filter('template_include', function($template) {
  $t = gs_sp_current_template();
  if (!$t) return $template;
  $full = GS_SP_PATH . '/templates/' . $t;
  return file_exists($full) ? $full : $template;
}, 50);

add_filter('body_class', function($classes) {
  $t = gs_sp_current_template();
  if ($t) {
    $classes[] = 'gs-static-page';
    $classes[] = 'gs-static-page--' . gs_sp_current_slug();
  }
  return $classes;
}, 20);

add_action('wp_enqueue_scripts', function() {
  $t = gs_sp_current_template();
  if (!$t) return;

  wp_enqueue_style('gs-sp', GS_SP_URL . '/assets/gs-static-pages.css', [], GS_SP_VER);
  wp_enqueue_script('gs-sp', GS_SP_URL . '/assets/gs-static-pages.js', [], GS_SP_VER, true);

  wp_add_inline_script('gs-sp', 'window.GS_STATIC_PAGES=' . wp_json_encode([
    'autoOpen' => !file_exists(WP_CONTENT_DIR . '/gs-disable-auto-open'),
    'routes' => [
      'Каталог'   => '/catalog/',
      'Услуги'    => '/services/',
      'Филиалы'   => '/filialy/',
      'Гарантия'  => '/garantiya/',
      'Контакты'  => '/contacts/',
      'Поиск'     => '/search/',
    ],
  ], JSON_UNESCAPED_UNICODE) . ';', 'before');
}, 30);

add_filter('pre_get_document_title', function($title){
  $t = gs_sp_current_template();
  if (!$t) return $title;
  $slug = gs_sp_current_slug();
  $map = [
    'services'  => 'Услуги ремонта | Global Service',
    'filialy'   => 'Филиалы и адреса | Global Service',
    'garantiya' => 'Гарантия на ремонт | Global Service',
    'contacts'  => 'Контакты | Global Service',
    'search'    => 'Поиск | Global Service',
  ];
  return $map[$slug] ?? $title;
}, 20);

add_action('wp_head', function(){
  if (!gs_sp_current_template()) return;
  $slug = gs_sp_current_slug();
  $desc_map = [
    'services'  => 'Диагностика, ремонт и запчасти. Согласование стоимости до ремонта, гарантия.',
    'filialy'   => 'Адреса, график и контакты филиалов. Быстрый выбор ближайшего сервиса.',
    'garantiya' => 'Понятные условия гарантии на работы и детали. Как обратиться и что нужно.',
    'contacts'  => 'Телефоны, мессенджеры, обратная связь. Быстрая заявка на ремонт.',
    'search'    => 'Поиск по технике и брендам. Быстрый переход в каталог ремонта.',
  ];
  $desc = esc_attr($desc_map[$slug] ?? 'Информация о сервисе.');
  echo "\n<meta name=\"description\" content=\"{$desc}\">\n";
  $canon = esc_url(home_url('/' . $slug . '/'));
  echo "\n<link rel=\"canonical\" href=\"{$canon}\">\n";
}, 2);

add_action('wp_head', function() {
  if (!gs_sp_current_template()) return;

  // Default (dev/stage): noindex,follow. To allow indexing of static pages, create empty file:
  // wp-content/gs-allow-index-static
  $allow = file_exists(WP_CONTENT_DIR . '/gs-allow-index-static');
  $robots = $allow ? 'index,follow' : 'noindex,follow';
  echo "\n<meta name=\"robots\" content=\"{$robots}\">\n";
}, 1);

