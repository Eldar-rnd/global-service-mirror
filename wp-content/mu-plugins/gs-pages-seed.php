cd /home/dev.global-service.ru/public_html/wp-content/mu-plugins

cat > gs-pages-seed.php <<'PHP'
<?php
/**
 * Plugin Name: GS Pages Seed (frontend)
 * Description: Seeds essential frontend pages (Services, Prices, Guarantee, Branches, Contacts, Search, How we work) with demo content. Single-file MU plugin.
 * Version: 0.6.4
 */

if (!defined('ABSPATH')) { exit; }

define('GS_PAGES_SEED_VER', '0.6.4');

function gs_pages_seed_disabled() {
  // Safety valve: create empty file wp-content/gs-disable-pages-seed to disable seeding.
  return file_exists(WP_CONTENT_DIR . '/gs-disable-pages-seed');
}

function gs_pages_seed_city_label() {
  if (!empty($_COOKIE['gs_city'])) {
    return sanitize_text_field(wp_unslash($_COOKIE['gs_city']));
  }
  $opt = get_option('gs_city_default');
  if (is_string($opt) && $opt !== '') return $opt;
  return 'Ростов-на-Дону';
}

function gs_pages_seed_wrap_block($html) {
  return '<div class="gs-card">' . $html . '</div>';
}

function gs_pages_seed_btns() {
  return '<div class="gs-page__actions">'
    . '<a class="gs-btn gs-btn--primary" href="/catalog/">Каталог</a>'
    . '<a class="gs-btn gs-btn--ghost" href="/search/">Поиск</a>'
  . '</div>';
}

function gs_pages_seed_tpl($h1, $lead, $blocks, $bullets) {
  $city = gs_pages_seed_city_label();
  $blocks_html = '';
  foreach ($blocks as $b) { $blocks_html .= gs_pages_seed_wrap_block($b); }

  $bul = '';
  foreach ($bullets as $row) {
    $bul .= '<li><strong>' . esc_html($row[0]) . ':</strong> ' . esc_html($row[1]) . '</li>';
  }

  return '<div class="gs-page" data-gs-page="seed">'
    . '<div class="gs-page__hero">'
      . '<div class="gs-page__kicker">Сервисный центр <span class="gs-page__city" data-gs-city-label>' . esc_html($city) . '</span></div>'
      . '<h1 class="gs-page__h1">' . esc_html($h1) . '</h1>'
      . '<p class="gs-page__lead">' . esc_html($lead) . '</p>'
      . gs_pages_seed_btns()
    . '</div>'
    . '<div class="gs-page__grid">'
      . $blocks_html
      . '<div class="gs-card gs-card--soft">'
        . '<div class="gs-card__title">Принципы</div>'
        . '<ul class="gs-card__list">' . $bul . '</ul>'
      . '</div>'
    . '</div>'
  . '</div>';
}

function gs_pages_seed_card($title, $text, $href) {
  return '<div class="gs-card__title">' . esc_html($title) . '</div>'
    . '<div class="gs-card__text">' . esc_html($text) . '</div>'
    . '<div class="gs-card__actions"><a class="gs-btn gs-btn--ghost" href="' . esc_url($href) . '">Открыть</a></div>';
}

function gs_pages_seed_kpi($title, $value, $text) {
  return '<div class="gs-kpi"><div class="gs-kpi__title">' . esc_html($title) . '</div>'
    . '<div class="gs-kpi__value">' . esc_html($value) . '</div>'
    . '<div class="gs-kpi__text">' . esc_html($text) . '</div></div>';
}

function gs_pages_seed_note($title, $text) {
  return '<div class="gs-card__title">' . esc_html($title) . '</div>'
    . '<div class="gs-card__text">' . esc_html($text) . '</div>';
}

function gs_pages_seed_branch($title, $addr, $hours, $hint, $mapHref) {
  return '<div class="gs-branch">'
    . '<div class="gs-branch__top">'
      . '<div class="gs-branch__title">' . esc_html($title) . '</div>'
      . '<div class="gs-branch__hours">' . esc_html($hours) . '</div>'
    . '</div>'
    . '<div class="gs-branch__addr">' . esc_html($addr) . '</div>'
    . '<div class="gs-branch__hint">' . esc_html($hint) . '</div>'
    . '<div class="gs-branch__actions"><a class="gs-btn gs-btn--ghost" href="' . esc_url($mapHref) . '">На карте</a></div>'
  . '</div>';
}

function gs_pages_seed_contact_block() {
  return '<div class="gs-contact">'
    . '<div class="gs-card__title">Быстрая заявка</div>'
    . '<form class="gs-form" method="post" action="#" onsubmit="return false" data-gs-form="contact">'
      . '<label class="gs-field"><span>Телефон</span><input type="tel" placeholder="+7 (___) ___-__-__" autocomplete="tel" /></label>'
      . '<label class="gs-field"><span>Комментарий (что сломалось)</span><textarea rows="3" placeholder="Например: не включается, течёт, ошибка на дисплее"></textarea></label>'
      . '<div class="gs-form__actions">'
        . '<button type="button" class="gs-btn gs-btn--primary" data-gs-demo-submit>Отправить</button>'
        . '<a class="gs-btn gs-btn--ghost" href="/kak-rabotaem/">Как работаем</a>'
      . '</div>'
      . '<div class="gs-form__hint">Демо-форма. На backend подключим реальную отправку, антиспам и цели аналитики.</div>'
    . '</form>'
  . '</div>';
}

function gs_pages_seed_search_block() {
  return '<div class="gs-search">'
    . '<div class="gs-card__title">Поиск</div>'
    . '<div class="gs-card__text">Введите запрос (например: <em>кофемашины</em>, <em>DeLonghi</em>, <em>iPhone</em>).</div>'
    . '<div class="gs-search__bar">'
      . '<input type="search" placeholder="Поиск по технике/бренду" autocomplete="off" data-gs-search />'
      . '<button type="button" class="gs-btn gs-btn--primary" data-gs-search-go>Найти</button>'
    . '</div>'
    . '<div class="gs-search__results" data-gs-search-results></div>'
    . '<div class="gs-form__hint">Демо. На backend подключим реальные данные, валидатор сочетаний и канонические URL.</div>'
  . '</div>';
}

function gs_pages_seed_pages() {
  return [
    'uslugi' => [
      'title' => 'Услуги',
      'excerpt' => 'Ремонт техники: направления, этапы, сроки и подбор услуги по типу устройства.',
      'content' => gs_pages_seed_tpl('Услуги ремонта', 'Понятная структура услуг. На backend свяжем категории с посадочными и прайсом.', [
        gs_pages_seed_card('Бытовая техника', 'Кухня, климат, уборка. Быстрый переход в каталог по типу устройства.', '/catalog/'),
        gs_pages_seed_card('Цифровая техника', 'Смартфоны, ноутбуки, планшеты. Диагностика и ремонт по модели.', '/catalog/'),
        gs_pages_seed_card('Инструмент', 'Электроинструмент, зарядки и аккумуляторы. Ремонт, обслуживание, замена узлов.', '/catalog/'),
      ], [
        ['Диагностика', 'Согласовываем стоимость до ремонта.'],
        ['Ремонт', 'Делаем только после согласования и фиксируем этапы.'],
        ['Гарантия', 'На работы и детали — по условиям, прозрачные сроки.'],
      ]),
    ],
    'ceny' => [
      'title' => 'Цены',
      'excerpt' => 'Ориентиры по стоимости работ. Финальные цены — после диагностики и согласования.',
      'content' => gs_pages_seed_tpl('Цены и ориентиры', 'На frontend показываем примеры. На backend подтянем прайс по типу техники и бренду.', [
        gs_pages_seed_kpi('Диагностика', 'от 0 ₽', 'В ряде случаев — бесплатно при ремонте.'),
        gs_pages_seed_kpi('Сроки', 'от 30 минут', 'Зависит от типа ремонта и наличия деталей.'),
        gs_pages_seed_kpi('Гарантия', 'до 12 месяцев', 'На работы и детали по условиям.'),
      ], [
        ['Диагностика', 'Стоимость до ремонта — после осмотра.'],
        ['Согласование', 'Без ремонта без согласования.'],
        ['Прозрачность', 'Ориентиры на сайте, финал — в заказе.'],
      ]),
    ],
    'garantiya' => [
      'title' => 'Гарантия',
      'excerpt' => 'Прозрачные условия гарантии на выполненные работы и установленные детали.',
      'content' => gs_pages_seed_tpl('Гарантия', 'Мы фиксируем работы и выдаём документы. На backend добавим статусы заказа и историю ремонта.', [
        gs_pages_seed_note('Что покрывает гарантия', 'Работы и установленные детали — в пределах условий и при соблюдении правил эксплуатации.'),
        gs_pages_seed_note('Что не покрывает гарантия', 'Механические повреждения, следы влаги, вмешательство третьих лиц.'),
        gs_pages_seed_note('Как обратиться', 'Позвоните или оставьте заявку — подскажем порядок действий.'),
      ], [
        ['Фиксация', 'Работы и детали фиксируются в заказе.'],
        ['Сроки', 'Зависят от вида работ и деталей.'],
        ['Поддержка', 'Поможем восстановить историю заказа на backend.'],
      ]),
    ],
    'filialy' => [
      'title' => 'Филиалы',
      'excerpt' => 'Адреса, часы работы и контакты сервисных центров. На backend будет справочник филиалов.',
      'content' => gs_pages_seed_tpl('Филиалы', 'Демо-структура. На backend подключим справочник филиалов по городу и схемы для локального SEO.', [
        gs_pages_seed_branch('Филиал №1', 'ул. Примерная, 10', 'Сегодня до 20:00', '10 минут пешком', '#'),
        gs_pages_seed_branch('Филиал №2', 'пр. Демонстрации, 25', 'Завтра с 09:00', '15 минут на авто', '#'),
      ], [
        ['Как добраться', 'На backend добавим карты и маршруты.'],
        ['Что принимаем', 'Категории техники по филиалам.'],
        ['Сроки', 'Ориентиры и статусы заказов.'],
      ]),
    ],
    'kontakty' => [
      'title' => 'Контакты',
      'excerpt' => 'Телефоны, мессенджеры и форма заявки. На backend подключим отправку и антиспам.',
      'content' => gs_pages_seed_tpl('Контакты', 'Оставьте заявку — перезвоним и уточним детали. На backend подключим реальную отправку, антиспам и аналитику.', [
        gs_pages_seed_contact_block(),
      ], [
        ['Телефон', '8 800 000-00-00'],
        ['График', 'Ежедневно 09:00–20:00'],
        ['Ответ', 'Перезвоним и уточним детали'],
      ]),
    ],
    'search' => [
      'title' => 'Поиск',
      'excerpt' => 'Поиск по технике и брендам. На backend подключим валидатор сочетаний и канонические URL.',
      'content' => gs_pages_seed_tpl('Поиск по технике и брендам', 'Введите тип техники или бренд. На backend заменим демо на реальные данные и канонические ссылки.', [
        gs_pages_seed_search_block(),
      ], [
        ['Подсказки', 'Сначала тип техники, затем бренд.'],
        ['Сочетания', 'Валидируем бренд×техника на backend.'],
        ['URL', 'Переходы на канонические посадочные.'],
      ]),
    ],
  ];
}

function gs_pages_seed_insert_or_get_page($slug, $cfg) {
  $existing = get_page_by_path($slug, OBJECT, 'page');
  if ($existing && !empty($existing->ID)) return (int)$existing->ID;

  $postarr = [
    'post_type' => 'page',
    'post_status' => 'publish',
    'post_title' => (string)($cfg['title'] ?? $slug),
    'post_name' => $slug,
    'post_content' => (string)($cfg['content'] ?? ''),
    'post_excerpt' => (string)($cfg['excerpt'] ?? ''),
    'comment_status' => 'closed',
    'ping_status' => 'closed',
  ];

  $id = wp_insert_post(wp_slash($postarr), true);
  if (is_wp_error($id)) {
    error_log('[GS Pages Seed] failed to create page ' . $slug . ': ' . $id->get_error_message());
    return 0;
  }
  add_post_meta((int)$id, '_gs_seed_ver', GS_PAGES_SEED_VER, true);
  return (int)$id;
}

function gs_pages_seed_run_once() {
  if (gs_pages_seed_disabled()) return;

  $key = 'gs_pages_seed_done_' . str_replace('.', '_', GS_PAGES_SEED_VER);
  if (get_option($key)) return;

  $pages = gs_pages_seed_pages();
  foreach ($pages as $slug => $cfg) {
    gs_pages_seed_insert_or_get_page($slug, $cfg);
  }

  if (!get_page_by_path('kak-rabotaem', OBJECT, 'page')) {
    $cfg = [
      'title' => 'Как работаем',
      'excerpt' => 'Этапы работы сервисного центра: приём, диагностика, согласование, ремонт, выдача.',
      'content' => gs_pages_seed_tpl('Как мы работаем', 'Покажем этапы и сроки. На backend подключим статусы заказа.', [
        gs_pages_seed_note('1. Принимаем устройство', 'Фиксируем симптомы, комплектность и контакты.'),
        gs_pages_seed_note('2. Диагностируем', 'Определяем причину и предлагаем варианты.'),
        gs_pages_seed_note('3. Согласовываем', 'Стоимость/сроки до ремонта, без сюрпризов.'),
        gs_pages_seed_note('4. Ремонтируем и тестируем', 'Проверяем результат, выдаём с документами.'),
      ], [
        ['Прозрачность', 'Согласование до ремонта.'],
        ['Сроки', 'Озвучиваем до начала работ.'],
        ['Гарантия', 'На работы и детали.'],
      ]),
    ];
    gs_pages_seed_insert_or_get_page('kak-rabotaem', $cfg);
  }

  update_option($key, gmdate('c'), true);
}

add_action('init', 'gs_pages_seed_run_once', 9);

function gs_pages_seed_inline_assets() {
  if (!is_page()) return;

  $css = <<<CSS
:root{
  --gs-bg:#f6f7f9;--gs-card:#fff;--gs-border:rgba(20,22,26,.12);
  --gs-text:#121318;--gs-muted:rgba(18,19,24,.72);
  --gs-accent:#D2051E;--gs-radius:18px;--gs-shadow:0 14px 34px rgba(20,22,26,.10)
}
.gs-page{background:var(--gs-bg);color:var(--gs-text);padding:32px 0 56px}
.gs-page *{box-sizing:border-box}
.gs-page__hero,.gs-page__grid{width:min(1440px,calc(100% - 40px));margin:0 auto}
.gs-page__hero{padding:22px 22px 26px;background:var(--gs-card);border:1px solid var(--gs-border);border-radius:var(--gs-radius);box-shadow:var(--gs-shadow)}
.gs-page__kicker{font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:var(--gs-muted);margin-bottom:10px}
.gs-page__h1{font-size:clamp(28px,3.4vw,52px);line-height:1.02;margin:0 0 10px}
.gs-page__lead{margin:0 0 16px;color:var(--gs-muted);max-width:70ch}
.gs-page__actions{display:flex;gap:10px;flex-wrap:wrap}
.gs-page__grid{display:grid;grid-template-columns:1.2fr 1.2fr 1fr;gap:14px;margin-top:16px}
.gs-card{background:var(--gs-card);border:1px solid var(--gs-border);border-radius:var(--gs-radius);padding:18px;box-shadow:0 8px 22px rgba(20,22,26,.06);min-height:120px}
.gs-card--soft{background:#fbfbfc}
.gs-card__title{font-weight:700;font-size:16px;margin-bottom:8px}
.gs-card__text{color:var(--gs-muted);font-size:14px;line-height:1.45;margin-bottom:12px}
.gs-card__list{margin:0;padding-left:18px;color:var(--gs-muted)}
.gs-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:999px;border:1px solid var(--gs-border);padding:10px 14px;font-weight:600;text-decoration:none;color:var(--gs-text);background:transparent;cursor:pointer}
.gs-btn--primary{background:var(--gs-accent);border-color:transparent;color:#fff}
.gs-btn--ghost:hover{border-color:rgba(20,22,26,.22)}
.gs-kpi{display:grid;gap:6px}
.gs-kpi__title{color:var(--gs-muted);font-size:12px;text-transform:uppercase;letter-spacing:.06em}
.gs-kpi__value{font-weight:800;font-size:20px}
.gs-kpi__text{color:var(--gs-muted);font-size:13px;line-height:1.45}
.gs-branch{display:grid;gap:8px}
.gs-branch__top{display:flex;align-items:baseline;justify-content:space-between;gap:10px}
.gs-branch__title{font-weight:800}
.gs-branch__hours{color:var(--gs-muted);font-size:12px}
.gs-branch__addr{font-size:14px}
.gs-branch__hint{color:var(--gs-muted);font-size:13px}
.gs-form{display:grid;gap:10px}
.gs-field{display:grid;gap:6px;font-size:12px;color:var(--gs-muted)}
.gs-field input,.gs-field textarea{width:100%;border-radius:14px;border:1px solid var(--gs-border);padding:12px 12px;font-size:14px;color:var(--gs-text);background:#fff;outline:none}
.gs-field input:focus,.gs-field textarea:focus{border-color:rgba(210,5,30,.5);box-shadow:0 0 0 4px rgba(210,5,30,.08)}
.gs-form__actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.gs-form__hint{color:var(--gs-muted);font-size:12px;line-height:1.45}
.gs-search__bar{display:flex;gap:10px;flex-wrap:wrap}
.gs-search__bar input{flex:1 1 260px;border-radius:999px;border:1px solid var(--gs-border);padding:12px 14px;font-size:14px}
.gs-search__results{margin-top:10px;display:grid;gap:10px}
.gs-search__item{padding:12px 12px;border-radius:14px;border:1px solid var(--gs-border);background:#fff;display:flex;justify-content:space-between;gap:12px}
.gs-search__item span{color:var(--gs-muted);font-size:13px}
@media (max-width:980px){.gs-page__grid{grid-template-columns:1fr}.gs-branch__top{flex-direction:column;align-items:flex-start}}
CSS;

  $js = <<<JS
(function(){
  document.addEventListener('click', function(e){
    var btn = e.target && e.target.closest ? e.target.closest('[data-gs-demo-submit]') : null;
    if(!btn) return;
    e.preventDefault();
    btn.textContent = 'Отправлено (демо)';
    btn.disabled = true;
    setTimeout(function(){ btn.textContent = 'Отправить'; btn.disabled = false; }, 1600);
  });

  document.addEventListener('click', function(e){
    var go = e.target && e.target.closest ? e.target.closest('[data-gs-search-go]') : null;
    if(!go) return;
    e.preventDefault();
    var wrap = e.target.closest('.gs-search') || document;
    var input = wrap.querySelector('[data-gs-search]');
    var out = wrap.querySelector('[data-gs-search-results]');
    if(!input || !out) return;
    var q = (input.value || '').trim();
    out.innerHTML = '';
    if(!q){
      out.innerHTML = '<div class="gs-search__item"><strong>Введите запрос</strong><span>Например: кофемашины, DeLonghi</span></div>';
      return;
    }
    var cityEl = document.querySelector('[data-gs-city-label]');
    var city = (cityEl && cityEl.textContent) ? cityEl.textContent : 'Ростов-на-Дону';
    var items = [
      {t:'Кофемашины',b:'DeLonghi',u:'/?gs_city='+encodeURIComponent(city)+'&gs_equipment=кофемашины&gs_brand=DeLonghi'},
      {t:'Смартфоны',b:'Apple',u:'/?gs_city='+encodeURIComponent(city)+'&gs_equipment=смартфоны&gs_brand=Apple'},
      {t:'Электроинструмент',b:'Makita',u:'/?gs_city='+encodeURIComponent(city)+'&gs_equipment=электроинструмент&gs_brand=Makita'}
    ];
    var found = 0;
    items.forEach(function(it){
      if((it.t+' '+it.b).toLowerCase().indexOf(q.toLowerCase()) === -1) return;
      found++;
      var a = document.createElement('a');
      a.href = it.u;
      a.className = 'gs-search__item';
      a.innerHTML = '<strong>'+it.t+'</strong><span>'+it.b+'</span>';
      out.appendChild(a);
    });
    if(!found){
      out.innerHTML = '<div class="gs-search__item"><strong>Ничего не найдено (демо)</strong><span>На backend подключим реальный поиск</span></div>';
    }
  });

  document.addEventListener('gs:cityChange', function(e){
    var city = e && e.detail && e.detail.city ? e.detail.city : null;
    if(!city) return;
    document.querySelectorAll('[data-gs-city-label]').forEach(function(n){ n.textContent = city; });
  });
})();
JS;

  // Attach only on seeded pages (or kak-rabotaem).
  global $post;
  if (!$post || empty($post->ID)) return;
  $seed = get_post_meta((int)$post->ID, '_gs_seed_ver', true);
  if (!$seed && !in_array($post->post_name, ['kak-rabotaem'], true)) return;

  wp_register_style('gs-pages-seed-inline', false, [], GS_PAGES_SEED_VER);
  wp_enqueue_style('gs-pages-seed-inline');
  wp_add_inline_style('gs-pages-seed-inline', $css);

  wp_register_script('gs-pages-seed-inline', '', [], GS_PAGES_SEED_VER, true);
  wp_enqueue_script('gs-pages-seed-inline');
  wp_add_inline_script('gs-pages-seed-inline', $js);
}

add_action('wp_enqueue_scripts', 'gs_pages_seed_inline_assets', 50);
PHP
