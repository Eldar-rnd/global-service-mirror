<?php
/**
 * Plugin Name: GS Catalog Page (MU)
 * Description: Virtual page /catalog/ (frontend-only). Renders catalog hub with city selector, search, categories and brands, linking to demo landing pages.
 * Version: 0.3.4
 */
if (!defined('ABSPATH')) { exit; }

define('GS_CP_VER', '0.3.4');
define('GS_CP_PATH', __DIR__ . '/gs-catalog-page');
define('GS_CP_URL', content_url('mu-plugins/gs-catalog-page'));


function gs_cp_city_from_cookie(array $allowed, string $fallback): string {
  if (empty($_COOKIE['gs_city'])) return $fallback;
  $raw = (string)$_COOKIE['gs_city'];
  // Cookie may be URL encoded
  $raw = rawurldecode($raw);
  $raw = trim($raw);
  if ($raw === '') return $fallback;
  foreach ($allowed as $c) {
    if ((string)$c === $raw) return $raw;
  }
  return $fallback;
}

function gs_cp_disabled(): bool {
  // Emergency switch: create empty file wp-content/gs-disable-catalog-page to disable this feature.
  return file_exists(WP_CONTENT_DIR . '/gs-disable-catalog-page');
}

function gs_cp_is_front(): bool {
  if (is_admin()) return false;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return false;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return false;
  return true;
}

function gs_cp_is_catalog_request(): bool {
  if (!gs_cp_is_front() || gs_cp_disabled()) return false;

  $uri = $_SERVER['REQUEST_URI'] ?? '/';
  $path = parse_url((string)$uri, PHP_URL_PATH);
  $path = is_string($path) ? $path : '/';
  $path = '/' . trim($path, '/') . '/';
  return ($path === '/catalog/' || $path === '/catalog');
}

function gs_cp_demo_data(): array {
  return [
    'defaultCity' => gs_cp_city_from_cookie(['Ростов-на-Дону','Краснодар','Воронеж'], 'Ростов-на-Дону'),
    'cities' => ['Ростов-на-Дону', 'Краснодар', 'Воронеж'],
    'categories' => [
      [
        'title' => 'Бытовая техника',
        'slug'  => 'bytovaya-tehnika',
        'children' => [
          ['title' => 'Кофемашины', 'slug' => 'kofemashiny', 'brands' => ['DeLonghi','Philips','Saeco','Jura','Krups']],
          ['title' => 'Пылесосы', 'slug' => 'pylesosy', 'brands' => ['Dyson','Samsung','Philips','Bosch']],
          ['title' => 'Робот‑пылесосы', 'slug' => 'robot-pylesosy', 'brands' => ['Xiaomi','Roborock','iRobot','Ecovacs']],
          ['title' => 'Стиральные машины', 'slug' => 'stiralnye-mashiny', 'brands' => ['LG','Samsung','Bosch','Electrolux']],
          ['title' => 'Посудомоечные машины', 'slug' => 'posudomoyki', 'brands' => ['Bosch','Electrolux','Hansa','Beko']],
        ],
      ],
      [
        'title' => 'Цифровая техника',
        'slug'  => 'cifrovaya-tehnika',
        'children' => [
          ['title' => 'Смартфоны', 'slug' => 'smartfony', 'brands' => ['Apple','Samsung','Xiaomi','Honor']],
          ['title' => 'Ноутбуки', 'slug' => 'noutbuki', 'brands' => ['Lenovo','HP','ASUS','Acer']],
          ['title' => 'Планшеты', 'slug' => 'planshety', 'brands' => ['Apple','Samsung','Xiaomi']],
          ['title' => 'Телевизоры', 'slug' => 'televizory', 'brands' => ['Samsung','LG','Sony','Hisense']],
        ],
      ],
      [
        'title' => 'Инструмент',
        'slug'  => 'instrument',
        'children' => [
          ['title' => 'Электроинструмент', 'slug' => 'elektroinstrument', 'brands' => ['Makita','Bosch','DeWalt','Metabo']],
          ['title' => 'Аккумуляторы и зарядки', 'slug' => 'akku-zaryadki', 'brands' => ['Makita','Bosch','DeWalt']],
        ],
      ],
    ],
  ];
}

add_filter('template_include', function($template) {
  if (!gs_cp_is_catalog_request()) return $template;
  $t = GS_CP_PATH . '/templates/catalog-page.php';
  return file_exists($t) ? $t : $template;
}, 50);

add_filter('body_class', function($classes) {
  if (gs_cp_is_catalog_request()) $classes[] = 'gs-catalog-page';
  return $classes;
}, 20);

add_action('wp_enqueue_scripts', function() {
  if (!gs_cp_is_catalog_request()) return;

  wp_enqueue_style('gs-cp', GS_CP_URL . '/assets/gs-catalog-page.css', [], GS_CP_VER);
  wp_enqueue_script('gs-cp', GS_CP_URL . '/assets/gs-catalog-page.js', [], GS_CP_VER, true);

  $payload = gs_cp_demo_data();
  wp_add_inline_script('gs-cp', 'window.GS_CATALOG_PAGE=' . wp_json_encode($payload, JSON_UNESCAPED_UNICODE) . ';', 'before');
}, 30);

// Dev-only safety: avoid accidental indexing on dev
add_filter('pre_get_document_title', function($title){
  if (!gs_cp_is_catalog_request()) return $title;
  return 'Каталог ремонта — выбор техники и бренда | Global Service';
}, 20);

add_action('wp_head', function(){
  if (!gs_cp_is_catalog_request()) return;
  echo "\n<meta name=\"description\" content=\"Выберите город, технику и бренд — получите релевантную страницу ремонта.\">\n";
  $canon = esc_url(home_url('/catalog/'));
  echo "\n<link rel=\"canonical\" href=\"{$canon}\">\n";
}, 2);

add_action('wp_head', function() {
  if (!gs_cp_is_catalog_request()) return;
  echo "\n<meta name=\"robots\" content=\"noindex, nofollow\">\n";
}, 1);
