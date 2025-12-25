<?php
if (!defined('ABSPATH')) exit;
$ctx = function_exists('gs_ld_ctx') ? gs_ld_ctx() : ['city'=>'Город','equipment'=>'техника','brand'=>'Бренд'];
$city = $ctx['city']; $equipment = $ctx['equipment']; $brand = $ctx['brand'];

get_header();
?>
<main id="gsMain" role="main">
  <section class="gs-section" style="padding:44px 0 60px;">
    <div class="gs-container">
      <nav class="gs-breadcrumb" aria-label="<?php echo esc_attr__('Хлебные крошки', 'global-service'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Главная', 'global-service'); ?></a>
        <span aria-hidden="true">/</span>
        <span><?php echo esc_html($city); ?></span>
        <span aria-hidden="true">/</span>
        <span><?php echo esc_html__('Ремонт', 'global-service'); ?></span>
        <span aria-hidden="true">/</span>
        <span><?php echo esc_html($equipment . ' ' . $brand); ?></span>
      </nav>

      <div class="gs-subnav" data-gs-subnav>
        <div class="gs-subnav__inner" aria-label="<?php echo esc_attr__('Навигация по разделам', 'global-service'); ?>">
          <a href="#lead" class="is-active"><?php echo esc_html__('Заявка', 'global-service'); ?></a>
          <a href="#prices">Цены</a>
          <a href="#trust">Преимущества</a>
          <a href="#prices"><?php echo esc_html__('Цены', 'global-service'); ?></a>
          <a href="#how"><?php echo esc_html__('Как работаем', 'global-service'); ?></a>
          <a href="#faq"><?php echo esc_html__('FAQ', 'global-service'); ?></a>
          <a href="#consult">Консультация</a>
          <a href="#links">Страницы</a>
          <a href="#reviews"><?php echo esc_html__('Отзывы', 'global-service'); ?></a>
          <a href="#branches"><?php echo esc_html__('Филиалы', 'global-service'); ?></a>
        </div>
      </div>

      <div class="gs-landing-grid" style="margin-top:18px;">
        <div>
          <div class="gs-kicker"><?php echo esc_html__('Сервисный центр', 'global-service'); ?></div>
          <h1 class="gs-h1" style="margin-top:10px;">
            <?php echo esc_html(sprintf('Ремонт %s %s в %s', $equipment, $brand, $city)); ?>
          </h1>
          <p class="gs-muted" style="margin-top:14px;max-width:86ch;">
            <?php echo esc_html__('Демо-посадочная по параметрам. На backend подключим валидатор сочетаний бренд×техника, канонические URL и реальный прайс.', 'global-service'); ?>
          </p>

          <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px;">
            <a class="gs-btn gs-btn--primary" href="#lead"><?php echo esc_html__('Оставить заявку', 'global-service'); ?></a>
            <button class="gs-btn" type="button" data-gs-open-search>Быстрый поиск</button>
            <button class="gs-btn" type="button" data-gs-open-catalog>Каталог</button>
            <a class="gs-btn" href="#prices"><?php echo esc_html__('Цены', 'global-service'); ?></a>
            <a class="gs-btn" href="#branches"><?php echo esc_html__('Филиалы', 'global-service'); ?></a>
          </div>
        </div>

        <div id="gs-quickform" aria-hidden="true"></div>

        <aside class="gs-panel" id="lead">
          <div class="gs-kicker"><?php echo esc_html__('Быстрая заявка', 'global-service'); ?></div>
          <h2 class="gs-h3" style="margin-top:10px;"><?php echo esc_html__('Оставьте телефон — перезвоним и уточним детали', 'global-service'); ?></h2>

          <form style="margin-top:14px;">
            <label class="gs-muted" style="display:block;font-size:13px;margin:10px 0 6px;"><?php echo esc_html__('Телефон', 'global-service'); ?></label>
            <input class="gs-input" type="tel" placeholder="+7 (___) ___‑__‑__" inputmode="tel" autocomplete="tel" />
            <label class="gs-muted" style="display:block;font-size:13px;margin:12px 0 6px;"><?php echo esc_html__('Комментарий (что сломалось)', 'global-service'); ?></label>
            <input class="gs-input" type="text" placeholder="<?php echo esc_attr__('Например: не включается / течёт / ошибка на дисплее', 'global-service'); ?>" />
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
              <button class="gs-btn gs-btn--primary" type="button" data-gs-demo-submit="1"><?php echo esc_html__('Отправить', 'global-service'); ?></button>
              <a class="gs-btn" href="#how"><?php echo esc_html__('Как работаем', 'global-service'); ?></a>
            </div>
            <p class="gs-muted" style="font-size:12px;margin-top:12px;"><?php echo esc_html__('Демо-форма. На backend добавим отправку, антиспам, аналитику.', 'global-service'); ?></p>
          </form>
        </aside>
      </div>
    </div>
  </section>

  <section class="gs-section" id="prices" style="padding-top:0;">
    <div class="gs-container">
      <h2 class="gs-h2"><?php echo esc_html__('Цены на популярные работы', 'global-service'); ?></h2>
      <table class="gs-table" aria-label="<?php echo esc_attr__('Таблица цен', 'global-service'); ?>" style="margin-top:14px;">
        <tbody>
          <tr><td><?php echo esc_html__('Диагностика (при ремонте)', 'global-service'); ?></td><td><?php echo esc_html__('0 ₽', 'global-service'); ?></td></tr>
          <tr><td><?php echo esc_html__('Ремонт платы', 'global-service'); ?></td><td><?php echo esc_html__('от 2 490 ₽', 'global-service'); ?></td></tr>
          <tr><td><?php echo esc_html__('Замена узла', 'global-service'); ?></td><td><?php echo esc_html__('от 1 490 ₽', 'global-service'); ?></td></tr>
        </tbody>
      </table>
      <div class="gs-muted" style="margin-top:10px; max-width:86ch;">В демо цены условные. На backend подключим реальные прайсы по типу техники/бренду и покажем итог только после диагностики и согласования.</div>

    </div>
  </section>

  <?php if (function_exists('gs_mb_trust_strip')) { gs_mb_trust_strip(['id' => 'trust', 'title' => 'Преимущества сервиса', 'lead' => 'Сфокусированы на понятном процессе: диагностика → согласование → ремонт → тестирование.']); } ?>

<section class="gs-section" id="how" style="padding-top:0;">
    <div class="gs-container">
      <h2 class="gs-h2"><?php echo esc_html__('Как мы работаем', 'global-service'); ?></h2>
      <ol class="gs-muted" style="margin:12px 0 0;padding-left:18px;display:grid;gap:8px;">
        <li><?php echo esc_html__('Принимаем устройство и фиксируем симптомы', 'global-service'); ?></li>
        <li><?php echo esc_html__('Диагностика → согласование цены/срока', 'global-service'); ?></li>
        <li><?php echo esc_html__('Ремонтируем только после согласования', 'global-service'); ?></li>
        <li><?php echo esc_html__('Тестируем и выдаём с гарантией', 'global-service'); ?></li>
      </ol>
    </div>
  </section>

  <section class="gs-section" id="faq">
  <div class="gs-container">
    <div class="gs-kicker">FAQ</div>
    <h2 class="gs-h2" style="margin-top:12px;">Частые вопросы</h2>
    <div class="gs-muted" style="margin-top:8px; max-width:70ch;">
      Коротко и по делу. На backend подключим точные сроки/стоимость по справочникам и статус ремонта.
    </div>

    <div class="gs-acc" data-gs-accordion="single" style="margin-top:16px;">
      <div class="gs-acc__item is-open" data-gs-acc-item>
        <button class="gs-acc__btn" type="button" data-gs-acc-btn>
          Сколько занимает ремонт?
          <span class="gs-acc__chev" aria-hidden="true">▾</span>
        </button>
        <div class="gs-acc__panel" data-gs-acc-panel>
          Срок зависит от модели и наличия деталей. Сначала диагностика, затем согласование срока и стоимости.
        </div>
      </div>

      <div class="gs-acc__item" data-gs-acc-item>
        <button class="gs-acc__btn" type="button" data-gs-acc-btn>
          Нужна ли диагностика перед ремонтом?
          <span class="gs-acc__chev" aria-hidden="true">▾</span>
        </button>
        <div class="gs-acc__panel" data-gs-acc-panel>
          Да. Мы фиксируем симптомы и согласуем план работ до ремонта. Это снижает риск лишних замен.
        </div>
      </div>

      <div class="gs-acc__item" data-gs-acc-item>
        <button class="gs-acc__btn" type="button" data-gs-acc-btn>
          Есть ли гарантия?
          <span class="gs-acc__chev" aria-hidden="true">▾</span>
        </button>
        <div class="gs-acc__panel" data-gs-acc-panel>
          Да, на выполненные работы и детали. Условия зависят от вида ремонта и будут опубликованы на странице “Гарантия”.
        </div>
      </div>

      <div class="gs-acc__item" data-gs-acc-item>
        <button class="gs-acc__btn" type="button" data-gs-acc-btn>
          Как узнать точную цену?
          <span class="gs-acc__chev" aria-hidden="true">▾</span>
        </button>
        <div class="gs-acc__panel" data-gs-acc-panel>
          После диагностики и согласования. На backend добавим прайс по работам/деталям и расчёт для каждой модели.
        </div>
      </div>
    </div>
  </div>
</section>

  <section class="gs-section" id="consult" style="padding-top:0;">
  <div class="gs-container">
    <div class="gs-panel" style="display:flex; gap:14px; align-items:center; justify-content:space-between; flex-wrap:wrap;">
      <div>
        <div class="gs-kicker">Нужна консультация?</div>
        <div class="gs-h3" style="margin-top:10px;">Ответим и подскажем по ремонту — без спама</div>
        <div class="gs-muted" style="margin-top:8px; max-width:70ch;">
          Сейчас кнопки в демо‑режиме. На backend подключим телефоны, WhatsApp/Telegram, трекинг и цели аналитики.
        </div>
      </div>
      <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a class="gs-btn gs-btn--primary" href="#" data-gs-demo-submit="1">Позвонить (демо)</a>
        <a class="gs-btn" href="#" data-gs-demo-submit="1">WhatsApp (демо)</a>
        <a class="gs-btn" href="#" data-gs-demo-submit="1">Telegram (демо)</a>
      </div>
    </div>
  </div>
</section>

<section class="gs-section" id="reviews" style="padding-top:0;">
    <div class="gs-container">
      <h2 class="gs-h2"><?php echo esc_html__('Отзывы', 'global-service'); ?></h2>
      <div class="gs-cards2" style="margin-top:14px;">
        <div class="gs-review">
          <div class="gs-review__top"><strong><?php echo esc_html__('Александр', 'global-service'); ?></strong><span class="gs-stars" aria-hidden="true">★★★★★</span></div>
          <div class="gs-review__txt"><?php echo esc_html__('Быстро нашли проблему и согласовали цену до ремонта.', 'global-service'); ?></div>
        </div>
        <div class="gs-review">
          <div class="gs-review__top"><strong><?php echo esc_html__('Мария', 'global-service'); ?></strong><span class="gs-stars" aria-hidden="true">★★★★★</span></div>
          <div class="gs-review__txt"><?php echo esc_html__('Понравилась прозрачность процесса и гарантия.', 'global-service'); ?></div>
        </div>
      </div>
    </div>
  </section>

  <section class="gs-section" id="branches" style="padding-top:0;">
    <div class="gs-container">
      <h2 class="gs-h2"><?php echo esc_html__('Филиалы рядом', 'global-service'); ?></h2>
<p class="gs-muted" style="margin-top:10px;max-width:86ch;">
  <?php echo esc_html__('Пока это демо-блок. На backend-этапе подключим справочник филиалов и будем показывать ближайшие по выбранному городу.', 'global-service'); ?>
</p>

<div class="gs-cards2" style="margin-top:14px;">
  <div class="gs-panel">
    <div class="gs-kicker"><?php echo esc_html__('Филиал №1 (демо)', 'global-service'); ?></div>
    <div class="gs-h3" style="margin-top:8px;"><?php echo esc_html__('Адрес будет добавлен', 'global-service'); ?></div>
    <div class="gs-muted" style="margin-top:8px;"><?php echo esc_html__('График и контакты подключим к базе филиалов.', 'global-service'); ?></div>
  </div>
  <div class="gs-panel">
    <div class="gs-kicker"><?php echo esc_html__('Филиал №2 (демо)', 'global-service'); ?></div>
    <div class="gs-h3" style="margin-top:8px;"><?php echo esc_html__('Адрес будет добавлен', 'global-service'); ?></div>
    <div class="gs-muted" style="margin-top:8px;"><?php echo esc_html__('На проде здесь появится карта и маршруты.', 'global-service'); ?></div>
  </div>
</div>
    </div>
  </section>

  <div class="gs-landingbar" aria-label="<?php echo esc_attr__('Быстрые действия', 'global-service'); ?>">
    <div class="gs-landingbar__top">
      <div class="gs-landingbar__ctx" data-gs-landingbar-ctx><?php echo esc_html($equipment . ' · ' . $brand . ' · ' . $city); ?></div>
      <div class="gs-pill" style="padding:6px 10px;"><?php echo esc_html__('Диагностика 0 ₽', 'global-service'); ?></div>
    </div>
    <div class="gs-landingbar__row">
      <a href="tel:+70000000000"><?php echo esc_html__('Позвонить', 'global-service'); ?></a>
      <a href="#" data-gs-track="wa"><?php echo esc_html__('WhatsApp', 'global-service'); ?></a>
      <a class="gs-landingbar__lead" href="#lead"><?php echo esc_html__('Заявка', 'global-service'); ?></a>
    </div>
  </div>
<section class="gs-section" id="links" style="padding-top:0;">
  <div class="gs-container">
    <div class="gs-kicker">Навигация</div>
    <h2 class="gs-h2" style="margin-top:12px;">Полезные страницы</h2>
    <div class="gs-muted" style="margin-top:8px; max-width:80ch;">
      Для доверия и удобства: услуги, гарантия, филиалы и контакты. Сейчас демо; на backend добавим реальные адреса и расписание.
    </div>

    <div class="gs-mb__grid" style="margin-top:14px;">
      <a class="gs-mb__card" href="<?php echo esc_url(home_url('/services/')); ?>">
        <div class="gs-mb__icon" aria-hidden="true"></div>
        <div class="gs-mb__t">Услуги</div>
        <div class="gs-mb__d">Что делаем, как согласуем, как тестируем результат.</div>
      </a>
      <a class="gs-mb__card" href="<?php echo esc_url(home_url('/garantiya/')); ?>">
        <div class="gs-mb__icon" aria-hidden="true"></div>
        <div class="gs-mb__t">Гарантия</div>
        <div class="gs-mb__d">Условия на работы и детали, как обратиться по гарантии.</div>
      </a>
      <a class="gs-mb__card" href="<?php echo esc_url(home_url('/filialy/')); ?>">
        <div class="gs-mb__icon" aria-hidden="true"></div>
        <div class="gs-mb__t">Филиалы</div>
        <div class="gs-mb__d">Города и точки приёма. Позже — карта и маршруты.</div>
      </a>
      <a class="gs-mb__card" href="<?php echo esc_url(home_url('/contacts/')); ?>">
        <div class="gs-mb__icon" aria-hidden="true"></div>
        <div class="gs-mb__t">Контакты</div>
        <div class="gs-mb__d">Способы связи и заявка на ремонт.</div>
      </a>
    </div>

    <?php if (function_exists('gs_mb_cta_panel')) { gs_mb_cta_panel(['title'=>'Готовы начать?', 'text'=>'Выберите технику и бренд — дальше подключим генерацию посадочных и заявки.']); } ?>
  </div>
</section>
</main>
<?php get_footer(); ?>
