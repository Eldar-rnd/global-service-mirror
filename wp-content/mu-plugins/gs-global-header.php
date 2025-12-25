<?php
/**
 * Plugin Name: GS Global Header (MU)
 * Description: Sticky top header with mobile drawer; integrates with Smart Search and Catalog overlay.
 * Version: 0.5.5
 */
if (!defined('ABSPATH')) exit;

define('GS_HDR_VER', '0.5.5');
define('GS_HDR_PATH', __DIR__ . '/gs-global-header');
define('GS_HDR_URL', content_url('mu-plugins/gs-global-header'));

function gs_hdr_force_enabled(): bool {
  // Force on even if theme already renders its own header:
  // create empty file wp-content/gs-force-global-header
  return file_exists(WP_CONTENT_DIR . '/gs-force-global-header');
}

function gs_hdr_disabled(): bool {
  // Emergency switch: create empty file wp-content/gs-disable-global-header
  return file_exists(WP_CONTENT_DIR . '/gs-disable-global-header');
}

function gs_hdr_should_render(): bool {
  if (gs_hdr_disabled()) return false;

  // If our custom theme is active, it likely already has a header.
  // Avoid double headers unless forced.
  if (!gs_hdr_force_enabled()) {
    $theme = wp_get_theme();
    $slug = is_object($theme) ? (string)$theme->get_stylesheet() : '';
    if ($slug === 'global-service') return false;
  }

  return true;
}

add_filter('body_class', function($classes){
  if (!gs_hdr_should_render()) return $classes;
  $classes[] = 'gs-hdr-on';
  return $classes;
});

add_action('wp_enqueue_scripts', function(){
  if (!gs_hdr_should_render() || is_admin()) return;
  wp_enqueue_style('gs-global-header', GS_HDR_URL . '/assets/gs-header.css', [], GS_HDR_VER);
  wp_enqueue_script('gs-global-header', GS_HDR_URL . '/assets/gs-header.js', [], GS_HDR_VER, true);
}, 22);

add_action('wp_body_open', function(){
  if (!gs_hdr_should_render() || is_admin()) return;

  $links = [
    ['title' => 'Услуги',    'url' => home_url('/services/')],
    ['title' => 'Каталог',   'url' => home_url('/catalog/')],
    ['title' => 'Гарантия',  'url' => home_url('/garantiya/')],
    ['title' => 'Филиалы',   'url' => home_url('/filialy/')],
    ['title' => 'Контакты',  'url' => home_url('/contacts/')],
  ];
  ?>
  <a class="gs-skip" href="#gsMain">Перейти к содержимому</a>

  <header class="gs-hdr" data-gs-hdr>
    <div class="gs-hdr__inner">
      <a class="gs-hdr__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Global Service">
        <span class="gs-hdr__logo" aria-hidden="true"></span>
        <span class="gs-hdr__brandtxt">GLOBAL Service</span>
      </a>

      <nav class="gs-hdr__nav" aria-label="Основное меню">
        <?php foreach ($links as $l): ?>
          <a class="gs-hdr__link" href="<?php echo esc_url($l['url']); ?>"><?php echo esc_html($l['title']); ?></a>
        <?php endforeach; ?>
      </nav>

      <div class="gs-hdr__actions">
        <button class="gs-hdr__city" type="button" data-gs-open-catalog aria-label="Выбрать город">
          <span class="gs-hdr__citylabel" data-gs-city-label>вашем городе</span>
        </button>
        <button class="gs-hdr__btn gs-hdr__btn--accent" type="button" data-gs-open-search>Поиск</button>
        <button class="gs-hdr__burger" type="button" aria-controls="gsHdrDrawer" aria-expanded="false" data-gs-hdr-open>
          <span class="gs-hdr__burgerbars" aria-hidden="true"></span>
          <span class="gs-hdr__sr">Меню</span>
        </button>
      </div>
    </div>
  </header>

  <div class="gs-drawer" id="gsHdrDrawer" hidden data-gs-hdr-drawer>
    <div class="gs-drawer__backdrop" data-gs-hdr-close></div>
    <div class="gs-drawer__panel" role="dialog" aria-modal="true" aria-label="Меню">
      <div class="gs-drawer__top">
        <div class="gs-drawer__title">Меню</div>
        <button class="gs-drawer__close" type="button" data-gs-hdr-close aria-label="Закрыть">✕</button>
      </div>

      <div class="gs-drawer__meta">
        Город: <strong data-gs-city-label>—</strong>
        <button class="gs-drawer__citybtn" type="button" data-gs-open-catalog>Изменить</button>
      </div>

      <nav class="gs-drawer__nav" aria-label="Навигация">
        <?php foreach ($links as $l): ?>
          <a class="gs-drawer__link" href="<?php echo esc_url($l['url']); ?>"><?php echo esc_html($l['title']); ?></a>
        <?php endforeach; ?>
      </nav>

      <div class="gs-drawer__cta">
        <button class="gs-hdr__btn gs-hdr__btn--accent" type="button" data-gs-open-search>Быстрый поиск</button>
        <button class="gs-hdr__btn" type="button" data-gs-open-catalog>Каталог</button>
      </div>

      <div class="gs-drawer__note">
        Ctrl+K — быстрый поиск. Выбор города сохраняется.
      </div>
    </div>
  </div>
  <?php
}, 6);