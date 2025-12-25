<?php
/**
 * Plugin Name: GS UI Catalog Overlay (MU)
 * Description: Catalog overlay (frontend-only, demo data). Opens from header link/button "Каталог" and provides quick navigation to demo landing pages.
 * Version: 0.4.8
 */
if (!defined('ABSPATH')) { exit; }

define('GS_UIC_VER', '0.4.8');
define('GS_UIC_DIR', __DIR__ . '/gs-ui-catalog');
define('GS_UIC_URL', content_url('mu-plugins/gs-ui-catalog'));

function gs_uic_disabled() {
  // Emergency switch: create empty file wp-content/gs-disable-catalog to disable.
  return file_exists(WP_CONTENT_DIR . '/gs-disable-catalog');
}

function gs_uic_is_front() {
  if (is_admin()) return false;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return false;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return false;
  return true;
}

function gs_uic_demo_data() {
  return [
    'defaultCity' => 'Ростов-на-Дону',
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
    'links' => [
      'catalogPage' => '/catalog/',
      'searchPage'  => '/search/',
    ],
  ];
}

add_action('wp_enqueue_scripts', function() {
  if (!gs_uic_is_front() || gs_uic_disabled()) return;

  wp_enqueue_style('gs-uic', GS_UIC_URL . '/assets/gs-catalog.css', [], GS_UIC_VER);
  wp_enqueue_script('gs-uic', GS_UIC_URL . '/assets/gs-catalog.js', [], GS_UIC_VER, true);

  $data = gs_uic_demo_data();
  wp_add_inline_script('gs-uic', 'window.GS_CATALOG=' . wp_json_encode($data, JSON_UNESCAPED_UNICODE) . ';', 'before');
}, 20);

add_action('wp_footer', function() {
  if (!gs_uic_is_front() || gs_uic_disabled()) return;
  ?>
  <div class="gs-catalog" data-gs-catalog hidden aria-hidden="true">
    <div class="gs-catalog__backdrop" data-gs-catalog-close tabindex="-1" aria-hidden="true"></div>

    <div class="gs-catalog__panel" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__('Каталог', 'global-service'); ?>">
      <header class="gs-catalog__head">
        <div class="gs-catalog__brand">
          <span class="gs-dot" aria-hidden="true"></span>
          <span class="gs-catalog__title"><?php echo esc_html__('Каталог', 'global-service'); ?></span>
        </div>

        <div class="gs-catalog__actions">
          <button class="gs-catalog__iconbtn" type="button" data-gs-catalog-close aria-label="<?php echo esc_attr__('Закрыть', 'global-service'); ?>">
            <span aria-hidden="true">×</span>
          </button>
        </div>
      </header>

      <div class="gs-catalog__search">
        <label class="gs-catalog__label" for="gsCatalogSearch"><?php echo esc_html__('Поиск по технике / бренду', 'global-service'); ?></label>
        <input id="gsCatalogSearch" class="gs-catalog__input" type="search" placeholder="<?php echo esc_attr__('Например: кофемашины, DeLonghi', 'global-service'); ?>" autocomplete="off" />
      </div>

      <div class="gs-catalog__grid">
        <nav class="gs-catalog__cats" aria-label="<?php echo esc_attr__('Разделы', 'global-service'); ?>">
          <div class="gs-catalog__list" data-gs-catalog-cats></div>
        </nav>

        <section class="gs-catalog__content" aria-label="<?php echo esc_attr__('Содержимое', 'global-service'); ?>">
          <div class="gs-catalog__contenthead">
            <div class="gs-catalog__crumbs" data-gs-catalog-crumbs></div>
            <div class="gs-catalog__city">
              <span class="gs-catalog__label"><?php echo esc_html__('Город', 'global-service'); ?></span>
              <button type="button" class="gs-catalog__chip" data-gs-catalog-citybtn>
                <span data-gs-catalog-city></span>
                <span aria-hidden="true">▾</span>
              </button>
            </div>
          </div>

          <div class="gs-catalog__section">
            <div class="gs-catalog__h"><?php echo esc_html__('Техника', 'global-service'); ?></div>
            <div class="gs-catalog__tiles" data-gs-catalog-items></div>
          </div>

          <div class="gs-catalog__section">
            <div class="gs-catalog__h"><?php echo esc_html__('Популярные бренды', 'global-service'); ?></div>
            <div class="gs-catalog__brands" data-gs-catalog-brands></div>
          </div>

          <div class="gs-catalog__footer">
<div class="gs-catalog__summary" data-gs-catalog-summary>
  <div class="gs-catalog__summaryline">
    <span class="gs-catalog__label"><?php echo esc_html__('Выбрано', 'global-service'); ?>:</span>
    <span data-gs-catalog-summarytext></span>
  </div>
  <div class="gs-catalog__summaryactions">
    <button class="gs-catalog__btn" type="button" data-gs-catalog-open><?php echo esc_html__('Открыть', 'global-service'); ?></button>
    <button class="gs-catalog__btn" type="button" data-gs-catalog-copy><?php echo esc_html__('Скопировать ссылку', 'global-service'); ?></button>
    <button class="gs-catalog__btn" type="button" data-gs-catalog-reset><?php echo esc_html__('Сброс', 'global-service'); ?></button>
  </div>
</div>

            <a class="gs-catalog__link" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('На главную', 'global-service'); ?></a>
            <span class="gs-catalog__sep" aria-hidden="true">·</span>
            <a class="gs-catalog__link" href="<?php echo esc_url(home_url('/catalog/')); ?>"><?php echo esc_html__('Страница каталога', 'global-service'); ?></a>
          </div>
        </section>
      </div>

      <div class="gs-catalog__citymenu" data-gs-catalog-citymenu hidden aria-hidden="true">
        <div class="gs-catalog__citymenuhead">
          <div class="gs-catalog__h"><?php echo esc_html__('Выберите город', 'global-service'); ?></div>
          <button class="gs-catalog__iconbtn" type="button" data-gs-catalog-cityclose aria-label="<?php echo esc_attr__('Закрыть', 'global-service'); ?>">
            <span aria-hidden="true">×</span>
          </button>
        </div>
<div class="gs-catalog__search" style="padding: 10px 14px 0;">
  <label class="gs-catalog__label" for="gsCatalogCityFilter"><?php echo esc_html__('Поиск города', 'global-service'); ?></label>
  <input id="gsCatalogCityFilter" class="gs-catalog__input" type="search" placeholder="<?php echo esc_attr__('Начните вводить…', 'global-service'); ?>" autocomplete="off" data-gs-catalog-cityfilter />
</div>

        <div class="gs-catalog__citylist" data-gs-catalog-citylist></div>
      </div>
    </div>
  </div>
  <?php
}, 20);
