\
<?php
if (!defined('ABSPATH')) exit;
get_header();
?>
<main class="gs-home">
  <section class="gs-home__hero">
    <div class="gs-container">
      <div class="gs-kicker">Сервисный центр</div>
      <h1 class="gs-home__h1">
        Ремонт техники в <span class="gs-home__city" data-gs-city-label>вашем городе</span>
      </h1>
      <div class="gs-muted gs-home__lead">
        Делаем фронтенд сначала: понятная навигация, поиск услуг и аккуратные страницы доверия. На backend подключим прайс, заявки, статусы и аналитику.
      </div>

      <div class="gs-home__cta">
        <button class="gs-btn gs-btn--primary" type="button" data-gs-open-search>Быстрый поиск (Ctrl+K)</button>
        <button class="gs-btn" type="button" data-gs-open-catalog>Каталог</button>
        <a class="gs-btn" href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
      </div>

      <div class="gs-home__hint">
        Если вы знаете, что нужно — начните с поиска. Если хотите посмотреть структуру услуг — откройте каталог.
      </div>
    </div>
  </section>

  <section class="gs-home__section" id="categories">
    <div class="gs-container">
      <h2 class="gs-h2">Направления</h2>
      <div class="gs-muted" style="margin-top:8px; max-width:80ch;">
        Это демо‑структура. На backend свяжем категории с реальными услугами и посадочными.
      </div>

      <div class="gs-home__cards" style="margin-top:14px;">
        <div class="gs-home__card">
          <div class="gs-home__cardicon" aria-hidden="true"></div>
          <div class="gs-home__cardt">Бытовая техника</div>
          <div class="gs-home__cardd">Кухня, климат, уборка. Начните с поиска модели или бренда.</div>
          <div class="gs-home__cardcta">
            <button class="gs-btn gs-btn--primary" type="button" data-gs-open-search="кофемашины">Найти кофемашину</button>
            <button class="gs-btn" type="button" data-gs-open-catalog>Открыть каталог</button>
          </div>
        </div>

        <div class="gs-home__card">
          <div class="gs-home__cardicon" aria-hidden="true"></div>
          <div class="gs-home__cardt">Цифровая техника</div>
          <div class="gs-home__cardd">Смартфоны, ноутбуки, планшеты. На backend добавим формы по типам ремонтов.</div>
          <div class="gs-home__cardcta">
            <button class="gs-btn gs-btn--primary" type="button" data-gs-open-search="смартфоны">Найти смартфон</button>
            <a class="gs-btn" href="<?php echo esc_url(home_url('/services/')); ?>">Услуги</a>
          </div>
        </div>

        <div class="gs-home__card">
          <div class="gs-home__cardicon" aria-hidden="true"></div>
          <div class="gs-home__cardt">Инструмент</div>
          <div class="gs-home__cardd">Электроинструмент и зарядки. В дальнейшем — отдельные посадочные под бренды.</div>
          <div class="gs-home__cardcta">
            <button class="gs-btn gs-btn--primary" type="button" data-gs-open-search="электроинструмент">Найти услугу</button>
            <a class="gs-btn" href="<?php echo esc_url(home_url('/garantiya/')); ?>">Гарантия</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php if (function_exists('gs_mb_trust_strip')) { gs_mb_trust_strip(['id'=>'trust','title'=>'Понятный процесс','lead'=>'Без “магии”: сначала диагностика, затем согласование, потом ремонт и тестирование.']); } ?>

  <section class="gs-home__section" id="popular">
    <div class="gs-container">
      <h2 class="gs-h2">Популярные запросы</h2>
      <div class="gs-muted" style="margin-top:8px;">Кликайте — откроется Smart Search уже с готовым запросом.</div>

      <div class="gs-home__chips" style="margin-top:12px;">
        <button class="gs-home__chip" type="button" data-gs-open-search="кофемашины DeLonghi">кофемашины DeLonghi</button>
        <button class="gs-home__chip" type="button" data-gs-open-search="пылесосы Dyson">пылесосы Dyson</button>
        <button class="gs-home__chip" type="button" data-gs-open-search="смартфоны Samsung">смартфоны Samsung</button>
        <button class="gs-home__chip" type="button" data-gs-open-search="ноутбуки ASUS">ноутбуки ASUS</button>
        <button class="gs-home__chip" type="button" data-gs-open-search="телевизоры LG">телевизоры LG</button>
        <button class="gs-home__chip" type="button" data-gs-open-search="электроинструмент Makita">электроинструмент Makita</button>
      </div>
    </div>
  </section>

  <section class="gs-home__section" id="links">
    <div class="gs-container">
      <h2 class="gs-h2">Полезные страницы</h2>
      <div class="gs-home__links" style="margin-top:12px;">
        <a class="gs-home__link" href="<?php echo esc_url(home_url('/services/')); ?>">Услуги</a>
        <a class="gs-home__link" href="<?php echo esc_url(home_url('/catalog/')); ?>">Каталог</a>
        <a class="gs-home__link" href="<?php echo esc_url(home_url('/garantiya/')); ?>">Гарантия</a>
        <a class="gs-home__link" href="<?php echo esc_url(home_url('/filialy/')); ?>">Филиалы</a>
        <a class="gs-home__link" href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
      </div>

      <?php if (function_exists('gs_mb_cta_panel')) { gs_mb_cta_panel(['title'=>'Готовы начать?','text'=>'Пока это демо. Дальше подключим backend: заявки, цели, статусы и генерацию посадочных по справочникам.']); } ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>