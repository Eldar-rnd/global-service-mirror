<?php
/**
 * Plugin Name: GS Landing Demo (MU)
 * Description: Demo landing rendering for /?gs_city=...&gs_equipment=...&gs_brand=... with sticky offsets and mobile CTA bar.
 * Version: 0.5.5
 */

if (!defined('ABSPATH')) { exit; }

define('GS_LD_VER', '0.5.5');
define('GS_LD_PATH', __DIR__ . '/gs-landing-demo');
define('GS_LD_URL', content_url('mu-plugins/gs-landing-demo'));
function gs_ld_cookie(string $name): string {
  if (!isset($_COOKIE[$name])) return '';
  $v = (string)$_COOKIE[$name];
  $v = urldecode($v);
  $v = wp_strip_all_tags($v);
  return trim($v);
}


function gs_ld_is_demo_request() {
  if (is_admin() || wp_doing_ajax() || wp_is_json_request()) return false;
  return (is_front_page() || is_home()) && (isset($_GET['gs_equipment']) || isset($_GET['gs_brand']) || isset($_GET['gs_city']));
}

function gs_ld_ctx() {
  $get = function($k, $fallback) {
    $v = isset($_GET[$k]) ? trim((string)$_GET[$k]) : '';
    $v = wp_strip_all_tags($v);
    return $v !== '' ? $v : $fallback;
  };
  return [
    'city'      => $get('gs_city', (gs_ld_cookie('gs_city') !== '' ? gs_ld_cookie('gs_city') : 'Ростов-на-Дону')),
    'equipment' => $get('gs_equipment', 'кофемашины'),
    'brand'     => $get('gs_brand', 'DeLonghi'),
  ];
}

function gs_ld_title_from_ctx(array $ctx): string {
  $city = trim((string)($ctx['city'] ?? ''));
  $eq   = trim((string)($ctx['equipment'] ?? ''));
  $br   = trim((string)($ctx['brand'] ?? ''));
  $parts = [];
  if ($eq !== '') $parts[] = 'Ремонт ' . $eq;
  if ($br !== '') $parts[] = $br;
  if ($city !== '') $parts[] = 'в ' . $city;
  $main = trim(implode(' ', $parts));
  if ($main === '') $main = 'Ремонт техники';
  return $main . ' — цены, сроки, гарантия | Global Service';
}

function gs_ld_description_from_ctx(array $ctx): string {
  $city = trim((string)($ctx['city'] ?? ''));
  $eq   = trim((string)($ctx['equipment'] ?? ''));
  $br   = trim((string)($ctx['brand'] ?? ''));
  $s = 'Сервисный центр: диагностика, ремонт и гарантия.';
  if ($eq !== '' || $br !== '' || $city !== '') {
    $s = 'Ремонт ' . ($eq !== '' ? $eq : 'техники') . ($br !== '' ? (' ' . $br) : '') . ($city !== '' ? (' в ' . $city) : '') . '.';
    $s .= ' Диагностика, согласование до ремонта, гарантия.';
  }
  return $s;
}

add_filter('pre_get_document_title', function($title) {
  if (!gs_ld_is_demo_request()) return $title;
  $ctx = gs_ld_ctx();
  return gs_ld_title_from_ctx($ctx);
}, 20);

add_action('wp_head', function() {
  if (!gs_ld_is_demo_request()) return;
  $ctx = gs_ld_ctx();
  $desc = esc_attr(gs_ld_description_from_ctx($ctx));
  echo "\n<meta name=\"description\" content=\"{$desc}\">\n";
  echo "\n<meta name=\"robots\" content=\"noindex, nofollow\">\n";
  // canonical: keep current (demo) URL for now
  $canon = esc_url(home_url(add_query_arg(null, null)));
  echo "\n<link rel=\"canonical\" href=\"{$canon}\">\n";
}, 1);

add_filter('template_include', function($template) {
  if (!gs_ld_is_demo_request()) return $template;

  $t = GS_LD_PATH . '/templates/landing-demo.php';
  return file_exists($t) ? $t : $template;
}, 50);

add_filter('body_class', function($classes) {
  if (gs_ld_is_demo_request()) $classes[] = 'gs-landing';
  return $classes;
}, 20);

add_action('wp_enqueue_scripts', function() {
  if (!gs_ld_is_demo_request()) return;

  wp_enqueue_style('gs-ld', GS_LD_URL . '/assets/gs-landing.css', [], GS_LD_VER);
  wp_enqueue_script('gs-ld', GS_LD_URL . '/assets/gs-landing.js', [], GS_LD_VER, true);

  wp_add_inline_script('gs-ld', 'window.GS_LD_CTX=' . wp_json_encode(gs_ld_ctx(), JSON_UNESCAPED_UNICODE) . ';', 'before');
});
