<?php
if (!defined('ABSPATH')) exit;
get_header();
?>
<main id="gsMain" class="gs-cp" role="main">
  <div class="gs-cp__wrap">
    <div class="gs-cp__container">
      <section class="gs-cp__hero">
        <div>
          <div class="gs-cp__kicker">Навигация</div>
          <h1 class="gs-cp__h1">Каталог ремонта</h1>
          <p class="gs-cp__lead">
            Быстрый выбор техники и бренда. Это фронтенд‑демо: переходы ведут на параметры <code>/?gs_city=…&amp;gs_equipment=…&amp;gs_brand=…</code>.
            На backend‑этапе заменим на чистые канонические URL и валидатор сочетаний.
          </p>
<div class="gs-cp__quick" style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
  <button class="gs-btn gs-btn--primary" type="button" data-gs-open-search>Быстрый поиск (Ctrl+K)</button>
  <button class="gs-btn" type="button" data-gs-open-catalog>Оверлей каталога</button>
  <div class="gs-muted" style="font-size:13px;">Город: <strong data-gs-city-label>—</strong></div>
</div>

          <div class="gs-cp__row" style="margin-top:14px;">
            <button class="gs-cp__chip gs-cp__chip--accent" type="button" data-gs-open-catalog>Открыть оверлей «Каталог»</button>
            <a class="gs-cp__chip" href="<?php echo esc_url(home_url('/')); ?>">На главную</a>
          </div>
        </div>

        <div class="gs-cp__controls">
          <div>
            <div class="gs-cp__label">Город</div>
            <div class="gs-cp__row" style="margin-top:8px;">
              <button type="button" class="gs-cp__chip" data-gs-cp-citybtn>
                <span data-gs-cp-city>—</span>
                <span aria-hidden="true">▾</span>
              </button>
            </div>

            <div class="gs-cp__panel" data-gs-cp-citymenu hidden aria-hidden="true" style="margin-top:10px;">
              <div class="gs-cp__panelhead">
                <div class="gs-cp__h">Выберите город</div>
              </div>
              <div class="gs-cp__cats" data-gs-cp-citylist></div>
            </div>
          </div>

          <div>
            <div class="gs-cp__label">Поиск</div>
            <input class="gs-cp__input" type="search" placeholder="Например: кофемашины, DeLonghi" data-gs-cp-search />
          </div>

          <div class="gs-cp__note">
            Совет: начните с выбора категории, затем техники, затем бренда. На мобильных всё работает в один столбец.
          </div>
        </div>
      </section>

      <section class="gs-cp__grid" aria-label="Каталог">
        <aside class="gs-cp__panel">
          <div class="gs-cp__panelhead">
            <div class="gs-cp__h">Категории</div>
          </div>
          <div class="gs-cp__cats" data-gs-cp-cats></div>
        </aside>

        <div class="gs-cp__panel">
          <div class="gs-cp__content">
            <div class="gs-cp__crumbs" data-gs-cp-crumbs></div>

            <div style="margin-top:12px;">
              <div class="gs-cp__h">Техника</div>
              <div class="gs-cp__tiles" data-gs-cp-items></div>
            </div>

            <div style="margin-top:18px;">
              <div class="gs-cp__h">Популярные бренды</div>
              <div class="gs-cp__brands" data-gs-cp-brands></div>
            </div>

            <div class="gs-cp__faq" aria-label="FAQ">
              <div class="gs-cp__q" data-gs-cp-q>
                <button type="button"><span>Как будут выглядеть URL на проде?</span><span aria-hidden="true">+</span></button>
                <p>Сделаем чистые канонические URL (город/ремонт/категория/бренд) и запретим индексацию query‑вариантов.</p>
              </div>
              <div class="gs-cp__q" data-gs-cp-q>
                <button type="button"><span>Почему не выводим сразу 10–12k страниц?</span><span aria-hidden="true">+</span></button>
                <p>Потому что это backend‑задача: нужны справочники, валидатор сочетаний, генерация sitemap и контроль дублей.</p>
              </div>
            </div>

            <div class="gs-cp__footer">
              <span>Демо‑страница /catalog/ (frontend‑этап).</span>
              <span aria-hidden="true">·</span>
              <a href="<?php echo esc_url(home_url('/?gs_city=Ростов-на-Дону&gs_equipment=кофемашины&gs_brand=DeLonghi')); ?>">Пример демо‑посадочной</a>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</main>
<?php get_footer(); ?>
