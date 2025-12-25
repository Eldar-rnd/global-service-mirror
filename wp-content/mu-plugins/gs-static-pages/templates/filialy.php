\
<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<main id="gsMain" class="gs-sp" role="main">
  <div class="gs-sp__container">
<nav class="gs-sp__breadcrumb" aria-label="Хлебные крошки">
  <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
  <span aria-hidden="true">/</span>
  <span><?php echo esc_html('Филиалы'); ?></span>
</nav>

    <section class="gs-sp__hero">
      <div>
        <div class="gs-sp__kicker">Филиалы</div>
        <h1 class="gs-sp__h1">Филиалы в <span data-gs-city-label>вашем городе</span></h1>
        <p class="gs-sp__lead">
          Сейчас это фронтенд‑демо. На backend подключим справочник филиалов: адреса, телефоны, график, карту, точки приёма/выдачи и структурированные данные.
        </p>

        <div class="gs-sp__cta" style="margin-top:14px;">
          <button class="gs-sp__btn gs-sp__btn--accent" type="button" data-gs-open-catalog>Выбрать город</button>
          <button class="gs-sp__btn" type="button" data-gs-open-search>Быстрый поиск</button>
          <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
        </div>

        <div class="gs-sp__steps" aria-label="Как это работает">
          <div class="gs-sp__step">
            <div class="gs-sp__stepn">1</div>
            <div class="gs-sp__stept">Выберите город</div>
            <div class="gs-sp__stepd">Каталог или Smart Search запомнят город для всего сайта.</div>
          </div>
          <div class="gs-sp__step">
            <div class="gs-sp__stepn">2</div>
            <div class="gs-sp__stept">Найдите услугу</div>
            <div class="gs-sp__stepd">Поиск по технике/бренду ведёт на демо‑посадочную.</div>
          </div>
          <div class="gs-sp__step">
            <div class="gs-sp__stepn">3</div>
            <div class="gs-sp__stept">Приезжайте в филиал</div>
            <div class="gs-sp__stepd">На backend появятся адрес, маршрут и запись без очереди.</div>
          </div>
        </div>
      </div>
    </section>

    <?php if (function_exists('gs_mb_trust_strip')) { gs_mb_trust_strip(['id'=>'trust','title'=>'Что важно в филиалах','lead'=>'Удобство приёма/выдачи, ясный процесс и согласование работ до ремонта.']); } ?>

    <section class="gs-sp__section" id="branches">
      <h2>Список филиалов</h2>
      <p class="gs-sp__muted">Фильтрация работает по выбранному городу. Если город не выбран — покажем все демо‑карточки.</p>

      <div class="gs-sp__note" style="margin-top:10px;">
        Текущий город: <strong data-gs-city-label>—</strong>. Если нужно — выберите город в “Каталоге” или через Smart Search (Ctrl+K).
      </div>

      <div class="gs-sp__grid" aria-label="Список филиалов" style="margin-top:14px;">
        <div class="gs-sp__card" data-gs-branch data-gs-branch-city="Ростов-на-Дону">
          <div class="gs-sp__tag">Приём/выдача</div>
          <h3 style="margin-top:10px;">Ростов‑на‑Дону</h3>
          <p class="gs-sp__muted">Адрес, телефон и график подключим на backend. Здесь будет быстрый маршрут и запись.</p>
          <ul class="gs-sp__list">
            <li>Фотофиксация при приёме</li>
            <li>Согласование работ до ремонта</li>
            <li>Тестирование после работ</li>
          </ul>
          <div class="gs-sp__cta" style="margin-top:12px;">
            <a class="gs-sp__btn gs-sp__btn--accent" href="<?php echo esc_url(home_url('/contacts/')); ?>">Связаться</a>
            <button class="gs-sp__btn" type="button" data-gs-open-search="приём">Найти по запросу</button>
          </div>
        </div>

        <div class="gs-sp__card" data-gs-branch data-gs-branch-city="Ростов-на-Дону">
          <div class="gs-sp__tag">Ремонт цифровой техники</div>
          <h3 style="margin-top:10px;">Ростов‑на‑Дону</h3>
          <p class="gs-sp__muted">На backend добавим специализации, сроки диагностики и статусы ремонта.</p>
          <ul class="gs-sp__list">
            <li>Диагностика до согласования</li>
            <li>Запчасти — по согласованию</li>
            <li>Гарантия на результат</li>
          </ul>
          <div class="gs-sp__cta" style="margin-top:12px;">
            <button class="gs-sp__btn gs-sp__btn--accent" type="button" data-gs-open-search="смартфоны">Найти смартфоны</button>
            <button class="gs-sp__btn" type="button" data-gs-open-search="ноутбуки">Найти ноутбуки</button>
          </div>
        </div>

        <div class="gs-sp__card" data-gs-branch data-gs-branch-city="Ростов-на-Дону">
          <div class="gs-sp__tag">Ремонт бытовой техники</div>
          <h3 style="margin-top:10px;">Ростов‑на‑Дону</h3>
          <p class="gs-sp__muted">На backend появятся реальные прайсы и валидация “бренд × техника”, чтобы не было мусора.</p>
          <ul class="gs-sp__list">
            <li>Прайс после диагностики</li>
            <li>Согласование перед ремонтом</li>
            <li>Документы для гарантии</li>
          </ul>
          <div class="gs-sp__cta" style="margin-top:12px;">
            <button class="gs-sp__btn gs-sp__btn--accent" type="button" data-gs-open-search="кофемашины">Найти кофемашины</button>
            <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/garantiya/')); ?>">Гарантия</a>
          </div>
        </div>

        <div class="gs-sp__card" data-gs-branch data-gs-branch-city="Воронеж">
          <div class="gs-sp__tag">Демо‑город</div>
          <h3 style="margin-top:10px;">Воронеж</h3>
          <p class="gs-sp__muted">Пример карточки другого города. На backend добавим поиск “ближайших точек” и карту.</p>
          <ul class="gs-sp__list">
            <li>Фильтр по городу</li>
            <li>Маршрут и расписание</li>
            <li>Онлайн‑заявка</li>
          </ul>
          <div class="gs-sp__cta" style="margin-top:12px;">
            <button class="gs-sp__btn gs-sp__btn--accent" type="button" data-gs-open-catalog>Выбрать технику</button>
            <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
          </div>
        </div>
      </div>

      <div class="gs-sp__note" data-gs-branches-empty hidden style="margin-top:14px;">
        Для выбранного города пока нет карточек в демо. На backend подключим справочник филиалов и реальную выдачу.
      </div>
    </section>

    <section class="gs-sp__section" id="faq">
      <h2>FAQ</h2>
      <div class="gs-acc" data-gs-accordion="single" style="margin-top:12px;">
        <div class="gs-acc__item is-open" data-gs-acc-item>
          <button class="gs-acc__btn" type="button" data-gs-acc-btn>
            Можно ли приехать без записи?
            <span class="gs-acc__chev" aria-hidden="true">▾</span>
          </button>
          <div class="gs-acc__panel" data-gs-acc-panel>
            В демо — да. На backend добавим запись по времени и подсказку по загруженности филиала.
          </div>
        </div>
        <div class="gs-acc__item" data-gs-acc-item>
          <button class="gs-acc__btn" type="button" data-gs-acc-btn>
            Сколько занимает диагностика?
            <span class="gs-acc__chev" aria-hidden="true">▾</span>
          </button>
          <div class="gs-acc__panel" data-gs-acc-panel>
            Зависит от типа техники и симптомов. На backend покажем сроки по справочнику и статус “в работе”.
          </div>
        </div>
      </div>
    </section>

    <div class="gs-sp__note">Следующий шаг: подключение справочника филиалов + карта + LocalBusiness (на backend).</div>
  </div>
</main>
<?php get_footer(); ?>