<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<main id="gsMain" class="gs-sp" role="main">
  <div class="gs-sp__container">
<nav class="gs-sp__breadcrumb" aria-label="Хлебные крошки">
  <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
  <span aria-hidden="true">/</span>
  <span><?php echo esc_html('Услуги'); ?></span>
</nav>

    <section class="gs-sp__hero">
      <div>
        <div class="gs-sp__kicker">Услуги</div>
        <h1 class="gs-sp__h1">Ремонт техники без “сюрпризов”</h1>
        <p class="gs-sp__lead">
          Диагностика, согласование стоимости до ремонта, прозрачные сроки и гарантия.
          На этом этапе это витрина: на backend подключим справочник услуг, прайсы, филиалы и заявки.
        </p>
        <div class="gs-sp__cta">
          <a class="gs-sp__btn gs-sp__btn--accent" href="<?php echo esc_url(home_url('/catalog/')); ?>">Открыть каталог</a>
          <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
          <button class="gs-sp__btn" type="button" data-gs-open-catalog>Быстрый выбор (оверлей)</button>
        </div>
      </div>
      <aside class="gs-sp__side">
        <div class="gs-sp__label">Быстрый поиск</div>
        <input data-gs-open-search class="gs-sp__input" type="search" placeholder="Например: кофемашины, Makita" onfocus="this.select()" />
        <div class="gs-sp__note">Подключим реальный поиск по справочникам на backend‑этапе.</div>
      </aside>
    </section>

<?php if (function_exists('gs_mb_trust_strip')) { gs_mb_trust_strip(['id'=>'trust', 'title'=>'Преимущества', 'lead'=>'Без “магии”: сначала диагностика, затем согласование, потом ремонт.']); } ?>


    <section class="gs-sp__grid" aria-label="Сервисы">
      <div class="gs-sp__card">
        <h2>Диагностика</h2>
        <p>Определяем причину поломки, фиксируем симптомы, формируем план ремонта.</p>
        <ul class="gs-sp__list">
          <li>Предварительная оценка по описанию</li>
          <li>Фото/видео фиксация при приёме</li>
          <li>Согласование до начала работ</li>
        </ul>
      </div>
      <div class="gs-sp__card">
        <h2>Ремонт и запчасти</h2>
        <p>Ремонтируем, меняем узлы, используем совместимые или оригинальные запчасти.</p>
        <ul class="gs-sp__list">
          <li>Сроки под контроль</li>
          <li>Прозрачная стоимость</li>
          <li>Тестирование после ремонта</li>
        </ul>
      </div>
      <div class="gs-sp__card">
        <h2>Гарантия</h2>
        <p>Гарантия на работы и детали. Условия — понятным языком, без мелкого шрифта.</p>
        <ul class="gs-sp__list">
          <li>Гарантийный талон</li>
          <li>Проверка результата</li>
          <li>Поддержка после ремонта</li>
        </ul>
      </div>
    </section>

    <section class="gs-sp__section" <section class="gs-sp__section" id="popular" style="margin-top:18px;">
  <h2>Популярные запросы</h2>
  <p class="gs-sp__muted">Быстрый старт: нажмите запрос — откроем Smart Search и подскажем подходящую страницу.</p>
  <div class="gs-sp__chips" style="margin-top:12px;">
    <button class="gs-sp__chip" type="button" data-gs-open-search="кофемашины">Кофемашины</button>
    <button class="gs-sp__chip" type="button" data-gs-open-search="пылесосы">Пылесосы</button>
    <button class="gs-sp__chip" type="button" data-gs-open-search="стиральные машины">Стиральные машины</button>
    <button class="gs-sp__chip" type="button" data-gs-open-search="телевизоры">Телевизоры</button>
    <button class="gs-sp__chip" type="button" data-gs-open-search="смартфоны">Смартфоны</button>
  </div>
</section>

id="faq" style="margin-top:18px;">
  <h2>FAQ</h2>
  <p class="gs-sp__muted">Сейчас демо. На backend подключим цены/сроки по справочникам и статус ремонта.</p>

  <div class="gs-acc" data-gs-accordion="single" style="margin-top:12px;">
    <div class="gs-acc__item is-open" data-gs-acc-item>
      <button class="gs-acc__btn" type="button" data-gs-acc-btn>
        Как формируется цена?
        <span class="gs-acc__chev" aria-hidden="true">▾</span>
      </button>
      <div class="gs-acc__panel" data-gs-acc-panel>
        После диагностики и согласования. В демо конкретные цены не показываем, чтобы не вводить в заблуждение.
      </div>
    </div>

    <div class="gs-acc__item" data-gs-acc-item>
      <button class="gs-acc__btn" type="button" data-gs-acc-btn>
        Как быстро можно сдать технику?
        <span class="gs-acc__chev" aria-hidden="true">▾</span>
      </button>
      <div class="gs-acc__panel" data-gs-acc-panel>
        На странице филиалов подключим расписание и маршрут. Позже добавим онлайн‑запись и статус ремонта.
      </div>
    </div>
  </div>

  <div class="gs-sp__cta" style="margin-top:14px;">
    <button class="gs-sp__btn gs-sp__btn--accent" type="button" data-gs-open-search>Найти услугу</button>
    <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/contacts/')); ?>">Оставить заявку</a>
  </div>
</section>
  </div>
</main>
<?php get_footer(); ?>
