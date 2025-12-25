\
<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<main id="gsMain" class="gs-sp" role="main">
  <div class="gs-sp__container">

<nav class="gs-sp__breadcrumb" aria-label="Хлебные крошки">
  <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
  <span aria-hidden="true">/</span>
  <span><?php echo esc_html('Поиск'); ?></span>
</nav>

    <section class="gs-sp__hero">
      <div>
        <div class="gs-sp__kicker">Поиск</div>
        <h1 class="gs-sp__h1">Быстрый поиск по технике и брендам</h1>
        <p class="gs-sp__lead">
          На frontend‑этапе это “переключатель” в Smart Search. На backend появится поиск по справочникам, подсказки и валидация “бренд × техника”.
        </p>

        <div class="gs-sp__cta" style="margin-top:14px;">
          <button class="gs-sp__btn gs-sp__btn--accent" type="button" data-gs-search-go>Открыть поиск</button>
          <button class="gs-sp__btn" type="button" data-gs-open-catalog>Каталог</button>
          <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
        </div>

        <div class="gs-sp__chips" aria-label="Популярные запросы">
          <button class="gs-sp__chip" type="button" data-gs-open-search="кофемашины delonghi">Кофемашины DeLonghi</button>
          <button class="gs-sp__chip" type="button" data-gs-open-search="смартфоны samsung">Смартфоны Samsung</button>
          <button class="gs-sp__chip" type="button" data-gs-open-search="роботы-пылесосы xiaomi">Роботы‑пылесосы Xiaomi</button>
          <button class="gs-sp__chip" type="button" data-gs-open-search="стиральные машины lg">Стиральные машины LG</button>
          <button class="gs-sp__chip" type="button" data-gs-open-search="ноутбуки lenovo">Ноутбуки Lenovo</button>
          <button class="gs-sp__chip" type="button" data-gs-open-search="телевизоры samsung">Телевизоры Samsung</button>
        </div>

        <div class="gs-sp__note" style="margin-top:14px;">
          Совет: <strong>Ctrl+K</strong> открывает Smart Search с любого экрана.
        </div>
      </div>

      <aside class="gs-sp__side" aria-label="Поле поиска">
        <div class="gs-sp__label">Введите запрос</div>
        <input class="gs-sp__input" type="search" inputmode="search" autocomplete="off"
               placeholder="Например: кофемашины, DeLonghi" data-gs-search-input />
        <div class="gs-sp__note">Enter — открыть Smart Search. На backend добавим подсказки, опечатки, популярные запросы.</div>
      </aside>
    </section>

    <section class="gs-sp__section" id="how">
      <h2>Как мы используем поиск</h2>
      <div class="gs-sp__steps">
        <div class="gs-sp__step">
          <div class="gs-sp__stepn">1</div>
          <div class="gs-sp__stept">Ввод</div>
          <div class="gs-sp__stepd">Поиск понимает “техника + бренд” и ведёт на демо‑посадочную.</div>
        </div>
        <div class="gs-sp__step">
          <div class="gs-sp__stepn">2</div>
          <div class="gs-sp__stept">Проверка</div>
          <div class="gs-sp__stepd">На backend добавим валидацию связок, чтобы не было мусора и нерелевантных брендов.</div>
        </div>
        <div class="gs-sp__step">
          <div class="gs-sp__stepn">3</div>
          <div class="gs-sp__stept">Посадочная</div>
          <div class="gs-sp__stepd">Дальше — лид‑форма, trust‑блоки и перелинковка. Всё без “воды”.</div>
        </div>
      </div>
    </section>

  </div>
</main>
<?php get_footer(); ?>