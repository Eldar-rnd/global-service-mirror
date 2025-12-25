<?php
/**
 * Plugin Name: GS Smart Search (MU)
 * Description: Global search modal (Ctrl+K) over demo catalog dataset. Helps users jump to relevant demo landing pages.
 * Version: 0.5.4
 */
if (!defined('ABSPATH')) exit;

define('GS_SS_VER', '0.5.4');
define('GS_SS_PATH', __DIR__ . '/gs-smart-search');
define('GS_SS_URL', content_url('mu-plugins/gs-smart-search'));

function gs_ss_is_front(): bool {
  if (is_admin()) return false;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return false;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return false;
  return true;
}

function gs_ss_city_cookie(): string {
  if (empty($_COOKIE['gs_city'])) return '';
  return trim(rawurldecode((string)$_COOKIE['gs_city']));
}

function gs_ss_data(): array {
  // Must stay in sync with /catalog/ demo dataset.
  return [
    'cities' => ['Ростов-на-Дону','Краснодар','Воронеж'],
    'defaultCity' => 'Ростов-на-Дону',
    'categories' => [
      [
        'title' => 'Бытовая техника',
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
        'children' => [
          ['title' => 'Смартфоны', 'slug' => 'smartfony', 'brands' => ['Apple','Samsung','Xiaomi','Honor']],
          ['title' => 'Ноутбуки', 'slug' => 'noutbuki', 'brands' => ['Lenovo','HP','ASUS','Acer']],
          ['title' => 'Планшеты', 'slug' => 'planshety', 'brands' => ['Apple','Samsung','Xiaomi']],
          ['title' => 'Телевизоры', 'slug' => 'televizory', 'brands' => ['Samsung','LG','Sony','Hisense']],
        ],
      ],
      [
        'title' => 'Инструмент',
        'children' => [
          ['title' => 'Электроинструмент', 'slug' => 'elektroinstrument', 'brands' => ['Makita','Bosch','DeWalt','Metabo']],
          ['title' => 'Аккумуляторы и зарядки', 'slug' => 'akku-zaryadki', 'brands' => ['Makita','Bosch','DeWalt']],
        ],
      ],
    ],
  ];
}

add_action('wp_enqueue_scripts', function() {
  if (!gs_ss_is_front()) return;

  wp_enqueue_style('gs-ss', GS_SS_URL . '/assets/gs-smart-search.css', [], GS_SS_VER);
  wp_enqueue_script('gs-ss', GS_SS_URL . '/assets/gs-smart-search.js', [], GS_SS_VER, true);

  $data = gs_ss_data();
  $city = gs_ss_city_cookie();
  if ($city && in_array($city, $data['cities'], true)) {
    $data['defaultCity'] = $city;
  }
  wp_add_inline_script('gs-ss', 'window.GS_SMART_SEARCH=' . wp_json_encode($data, JSON_UNESCAPED_UNICODE) . ';', 'before');
}, 60);

add_action('wp_footer', function() {
  if (!gs_ss_is_front()) return;
  ?>
  <div class="gs-ss" id="gs-ss" aria-hidden="true">
    <div class="gs-ss__backdrop" data-gs-ss-close></div>
    <div class="gs-ss__panel" role="dialog" aria-modal="true" aria-label="Поиск по каталогу">
      <div class="gs-ss__head">
        <div class="gs-ss__kicker">Поиск</div>
        <button class="gs-ss__x" type="button" aria-label="Закрыть" data-gs-ss-close>×</button>
      </div>
      <div class="gs-ss__box">
        <input class="gs-ss__input" type="search" placeholder="Бренд или техника: DeLonghi, кофемашины, Makita…" autocomplete="off" />
        <div class="gs-ss__hint">
          Ctrl+K — открыть, Esc — закрыть. Город: <button class="gs-ss__citybtn" type="button" data-gs-ss-citybtn><span class="gs-ss__city"></span><span class="gs-ss__chev" aria-hidden="true">▾</span></button>
        </div>
        <div class="gs-ss__citymenu" hidden aria-label="Выбор города" role="listbox"></div>
      </div>
      <div class="gs-ss__results" role="list"></div>
      <div class="gs-ss__foot">
        <a class="gs-ss__link" href="<?php echo esc_url(home_url('/catalog/')); ?>">Открыть полный каталог</a>
        <a class="gs-ss__link" href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
      </div>
    </div>
  </div>
  <?php
}, 999);
