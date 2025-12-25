<?php
/**
 * Plugin Name: GS Marketing Blocks (MU)
 * Description: Reusable trust/CTA blocks for frontend demo. No fake numbers, safe microcopy.
 * Version: 0.5.4
 */
if (!defined('ABSPATH')) exit;

define('GS_MB_VER', '0.5.4');

add_action('wp_enqueue_scripts', function(){
  $base = plugin_dir_url(__FILE__);
  wp_enqueue_style('gs-marketing', $base . 'gs-marketing-blocks/assets/gs-marketing.css', [], GS_MB_VER);
}, 20);

function gs_mb_trust_strip(array $args = []): void {
  $title = $args['title'] ?? 'Почему выбирают сервис';
  $lead  = $args['lead'] ?? 'Коротко о подходе. Без обещаний, которые нельзя гарантировать на каждом кейсе.';
  ?>
  <section class="gs-mb" id="<?php echo esc_attr($args['id'] ?? 'trust'); ?>">
    <div class="gs-container">
      <div class="gs-mb__head">
        <div class="gs-kicker"><?php echo esc_html__('Доверие', 'global-service'); ?></div>
        <h2 class="gs-h2" style="margin-top:12px;"><?php echo esc_html($title); ?></h2>
        <div class="gs-muted" style="margin-top:8px; max-width:80ch;"><?php echo esc_html($lead); ?></div>
      </div>

      <div class="gs-mb__grid" aria-label="<?php echo esc_attr__('Преимущества', 'global-service'); ?>">
        <?php
          $items = $args['items'] ?? [
            ['t'=>'Диагностика перед ремонтом', 'd'=>'Фиксируем симптомы, согласуем план работ до вмешательства.'],
            ['t'=>'Прозрачное согласование', 'd'=>'Стоимость и срок — после диагностики, до ремонта.'],
            ['t'=>'Гарантия на результат', 'd'=>'На работы и детали. Условия — на странице “Гарантия”.'],
            ['t'=>'Аккуратное обращение', 'd'=>'Фотофиксация при приёме и тестирование после работ.'],
          ];
          foreach ($items as $it):
        ?>
          <div class="gs-mb__card">
            <div class="gs-mb__icon" aria-hidden="true"></div>
            <div class="gs-mb__t"><?php echo esc_html($it['t'] ?? ''); ?></div>
            <div class="gs-mb__d"><?php echo esc_html($it['d'] ?? ''); ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="gs-mb__cta">
        <button class="gs-btn gs-btn--primary" type="button" data-gs-open-search>Быстрый поиск (Ctrl+K)</button>
        <button class="gs-btn" type="button" data-gs-open-catalog>Открыть каталог</button>
        <a class="gs-btn" href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
      </div>
    </div>
  </section>
  <?php
}

function gs_mb_cta_panel(array $args = []): void {
  $title = $args['title'] ?? 'Нужна консультация?';
  $text  = $args['text'] ?? 'Опишите проблему — на backend подключим формы, трекинг и цели аналитики.';
  ?>
  <div class="gs-panel gs-mb__panel">
    <div class="gs-kicker"><?php echo esc_html__('Дальше проще', 'global-service'); ?></div>
    <div class="gs-h3" style="margin-top:10px;"><?php echo esc_html($title); ?></div>
    <div class="gs-muted" style="margin-top:8px; max-width:80ch;"><?php echo esc_html($text); ?></div>
    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:14px;">
      <a class="gs-btn gs-btn--primary" href="<?php echo esc_url(home_url('/contacts/')); ?>">Оставить заявку</a>
      <button class="gs-btn" type="button" data-gs-open-search>Найти услугу</button>
    </div>
  </div>
  <?php
}
