<?php
/**
 * Plugin Name: GS Header Rebuild (MU)
 * Description: Rebuilt global header (light, accessible, responsive) with city selector. Designed to be unzipped into /home/dev.global-service.ru (public_html/...).
 * Version: 0.6.3
 */

if (!defined('ABSPATH')) exit;

define('GS_HDR2_VER', '0.6.3');
define('GS_HDR2_URL', content_url('mu-plugins/gs-header-rebuild'));

function gs_hdr2_is_front(): bool {
  if (is_admin()) return false;
  if (defined('REST_REQUEST') && REST_REQUEST) return false;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return false;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return false;
  return true;
}

function gs_hdr2_disabled(): bool {
  // Emergency switch: create empty file wp-content/gs-disable-header-rebuild
  return defined('WP_CONTENT_DIR') && file_exists(WP_CONTENT_DIR . '/gs-disable-header-rebuild');
}

add_action('wp_enqueue_scripts', function() {
  if (!gs_hdr2_is_front() || gs_hdr2_disabled()) return;

  wp_enqueue_style('gs-hdr2', GS_HDR2_URL . '/assets/gs-hdr2.css', [], GS_HDR2_VER);
  wp_enqueue_script('gs-hdr2', GS_HDR2_URL . '/assets/gs-hdr2.js', [], GS_HDR2_VER, true);

  // Provide base data (cookie-based city is already used by gs-ui-enhancements) — keep behavior consistent.
  $city = '';
  if (!empty($_COOKIE['gs_city'])) {
    $city = rawurldecode((string)$_COOKIE['gs_city']);
  }
  $fallback_cities = ['Ростов-на-Дону','Краснодар','Воронеж','Москва','Санкт-Петербург'];

  wp_add_inline_script('gs-hdr2', 'window.GS_HDR2=' . wp_json_encode([
    'ver' => GS_HDR2_VER,
    'city' => $city,
    'cities' => $fallback_cities,
  ], JSON_UNESCAPED_UNICODE) . ';', 'before');
}, 999);

add_filter('body_class', function($classes) {
  if (!gs_hdr2_is_front() || gs_hdr2_disabled()) return $classes;
  $classes[] = 'gs-hdr2-enabled';
  return $classes;
}, 50);

add_action('wp_body_open', function() {
  if (!gs_hdr2_is_front() || gs_hdr2_disabled()) return;

  $city = '';
  if (!empty($_COOKIE['gs_city'])) {
    $city = rawurldecode((string)$_COOKIE['gs_city']);
  }
  if (!$city) $city = 'Ростов-на-Дону';

  $nav = [
    ['label'=>'Услуги','href'=>'/services/'],
    ['label'=>'Цены','href'=>'/prices/'],
    ['label'=>'Гарантия','href'=>'/guarantee/'],
    ['label'=>'Контакты','href'=>'/contacts/'],
    ['label'=>'Каталог','href'=>'/catalog/','data'=>'data-gs-open-catalog="1"'],
  ];

  echo '<a class="gs-hdr2-skip" href="#gsMain">Перейти к содержанию</a>';

  echo '<header id="gsHdr2" class="gs-hdr2" data-gs-hdr2="1" role="banner">';
  echo '  <div class="gs-hdr2__in">';
  echo '    <div class="gs-hdr2__left">';
  echo '      <a class="gs-hdr2__logo" href="/" aria-label="GLOBAL Service">';
  echo '        <span class="gs-hdr2__mark" aria-hidden="true"></span>';
  echo '        <span class="gs-hdr2__name">GLOBAL</span><span class="gs-hdr2__name gs-hdr2__name--muted">Service</span>';
  echo '      </a>';
  echo '    </div>';

  echo '    <nav class="gs-hdr2__nav" aria-label="Основная навигация">';
  foreach ($nav as $item) {
    $data = isset($item['data']) ? ' ' . $item['data'] : '';
    echo '      <a class="gs-hdr2__link" href="' . esc_url($item['href']) . '"' . $data . '>' . esc_html($item['label']) . '</a>';
  }
  echo '    </nav>';

  echo '    <div class="gs-hdr2__right">';
  echo '      <button class="gs-hdr2__city" type="button" data-gs-hdr2-citybtn aria-haspopup="dialog" aria-expanded="false">';
  echo '        <span class="gs-hdr2__cityPrefix">Город:</span> ';
  echo '        <span class="gs-hdr2__cityVal" data-gs-city-label>' . esc_html($city) . '</span>';
  echo '        <span class="gs-hdr2__chev" aria-hidden="true">▾</span>';
  echo '      </button>';

  echo '      <a class="gs-hdr2__phone" href="tel:+78000000000" aria-label="Позвонить">8 800 000‑00‑00</a>';
  echo '      <a class="gs-hdr2__cta" href="/contacts/#form">Оставить заявку</a>';

  echo '      <button class="gs-hdr2__burger" type="button" data-gs-hdr2-burger aria-label="Меню" aria-controls="gsHdr2Drawer" aria-expanded="false">';
  echo '        <span></span><span></span><span></span>';
  echo '      </button>';
  echo '    </div>';
  echo '  </div>';

  // Drawer (mobile)
  echo '  <div class="gs-hdr2__drawer" id="gsHdr2Drawer" data-gs-hdr2-drawer hidden>';
  echo '    <div class="gs-hdr2__drawerHead">';
  echo '      <div class="gs-hdr2__drawerTitle">Меню</div>';
  echo '      <button class="gs-hdr2__drawerClose" type="button" data-gs-hdr2-close aria-label="Закрыть">×</button>';
  echo '    </div>';
  echo '    <div class="gs-hdr2__drawerBody">';
  foreach ($nav as $item) {
    $data = isset($item['data']) ? ' ' . $item['data'] : '';
    echo '      <a class="gs-hdr2__drawerLink" href="' . esc_url($item['href']) . '"' . $data . '>' . esc_html($item['label']) . '</a>';
  }
  echo '      <div class="gs-hdr2__drawerSep"></div>';
  echo '      <button class="gs-hdr2__drawerCity" type="button" data-gs-hdr2-citybtn2>';
  echo '        <span class="gs-hdr2__cityPrefix">Город:</span> <span class="gs-hdr2__cityVal" data-gs-city-label>' . esc_html($city) . '</span> <span aria-hidden="true">▾</span>';
  echo '      </button>';
  echo '      <a class="gs-hdr2__drawerPhone" href="tel:+78000000000">8 800 000‑00‑00</a>';
  echo '      <a class="gs-hdr2__drawerCta" href="/contacts/#form">Оставить заявку</a>';
  echo '    </div>';
  echo '  </div>';

  // City dialog
  echo '  <div class="gs-hdr2__cityDlg" data-gs-hdr2-citydlg hidden role="dialog" aria-modal="true" aria-label="Выбор города">';
  echo '    <div class="gs-hdr2__cityDlgIn">';
  echo '      <div class="gs-hdr2__cityDlgHead">';
  echo '        <div class="gs-hdr2__cityDlgTitle">Выберите город</div>';
  echo '        <button class="gs-hdr2__cityDlgClose" type="button" data-gs-hdr2-cityclose aria-label="Закрыть">×</button>';
  echo '      </div>';
  echo '      <div class="gs-hdr2__cityList" data-gs-hdr2-citylist>';
  // fallback cities; JS will replace using GS_CATALOG/GS_CATALOG_DATA if available
  $cities = ['Ростов-на-Дону','Краснодар','Воронеж','Москва','Санкт-Петербург'];
  foreach ($cities as $c) {
    echo '        <button type="button" class="gs-hdr2__cityItem" data-gs-hdr2-city="' . esc_attr($c) . '">' . esc_html($c) . '</button>';
  }
  echo '      </div>';
  echo '    </div>';
  echo '  </div>';

  echo '</header>';
}, 1);
