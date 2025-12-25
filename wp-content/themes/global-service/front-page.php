<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<main id="gsMain" role="main">
  <section class="gs-hero">
    <div class="gs-container">
      <div class="gs-hero__grid">
        <div>
          <div class="gs-kicker"><?php echo esc_html__('Сервисный центр', 'global-service'); ?></div>
          <h1 class="gs-h1"><?php echo esc_html__('Ремонт техники — быстро, прозрачно, с гарантией', 'global-service'); ?></h1>
          <p class="gs-muted" style="margin-top:14px;max-width:62ch;">
            <?php echo esc_html__('Выбираете тип техники и бренд — получаете понятную страницу: сроки, цены “от”, частые неисправности и ближайшие филиалы.', 'global-service'); ?>
          </p>

          <div class="gs-hero__actions">
            <button class="gs-btn gs-btn--primary" type="button" data-gs-open="catalog"><?php echo esc_html__('Открыть каталог', 'global-service'); ?></button>
            <button class="gs-btn" type="button" data-gs-open="search"><?php echo esc_html__('Поиск по технике/бренду', 'global-service'); ?></button>
            <a class="gs-btn" href="#branches"><?php echo esc_html__('Филиалы', 'global-service'); ?></a>
          </div>

          <div class="gs-cardgrid" id="service">
            <a class="gs-card" href="#">
              <div class="gs-card__title"><span class="gs-icon" aria-hidden="true"><?php echo gs_inline_svg('default'); ?></span><?php echo esc_html__('Диагностика', 'global-service'); ?></div>
              <div class="gs-card__desc"><?php echo esc_html__('Определяем причину и согласуем работы до ремонта.', 'global-service'); ?></div>
            </a>
            <a class="gs-card" href="#">
              <div class="gs-card__title"><span class="gs-icon" aria-hidden="true"><?php echo gs_inline_svg('default'); ?></span><?php echo esc_html__('Ремонт и запчасти', 'global-service'); ?></div>
              <div class="gs-card__desc"><?php echo esc_html__('Держим в фокусе сроки, качество и прозрачность этапов.', 'global-service'); ?></div>
            </a>
            <a class="gs-card" href="#">
              <div class="gs-card__title"><span class="gs-icon" aria-hidden="true"><?php echo gs_inline_svg('default'); ?></span><?php echo esc_html__('Гарантия', 'global-service'); ?></div>
              <div class="gs-card__desc"><?php echo esc_html__('Гарантия на работы и детали — без “мелкого шрифта”.', 'global-service'); ?></div>
            </a>
          </div>
        </div>

        <aside class="gs-hero__panel" id="branches">
          <div class="gs-kicker"><?php echo esc_html__('Быстрая заявка', 'global-service'); ?></div>
          <h2 class="gs-h3" style="margin-top:10px;"><?php echo esc_html__('Оставьте телефон — перезвоним и уточним детали', 'global-service'); ?></h2>

          <form style="margin-top:14px;">
            <label class="gs-muted" style="display:block;font-size:13px;margin:10px 0 6px;"><?php echo esc_html__('Телефон', 'global-service'); ?></label>
            <input class="gs-input" type="tel" placeholder="+7 (___) ___-__-__" inputmode="tel" autocomplete="tel" />
            <label class="gs-muted" style="display:block;font-size:13px;margin:12px 0 6px;"><?php echo esc_html__('Комментарий (что сломалось)', 'global-service'); ?></label>
            <input class="gs-input" type="text" placeholder="<?php echo esc_attr__('Например: не включается, не греет, выбивает автомат…', 'global-service'); ?>" />
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
              <button class="gs-btn gs-btn--primary" type="button"><?php echo esc_html__('Отправить', 'global-service'); ?></button>
              <a class="gs-btn" href="#guarantee"><?php echo esc_html__('Как работаем', 'global-service'); ?></a>
            </div>
            <p class="gs-muted" style="font-size:12px;margin-top:12px;">
              <?php echo esc_html__('Демо-форма. На этапе backend подключим реальную отправку, антиспам и цели аналитики.', 'global-service'); ?>
            </p>
          </form>

          <div id="guarantee" style="margin-top:18px;">
            <div class="gs-kicker"><?php echo esc_html__('Принципы', 'global-service'); ?></div>
            <ul class="gs-muted" style="margin:12px 0 0;padding-left:18px;">
              <li><?php echo esc_html__('Согласование работ до ремонта', 'global-service'); ?></li>
              <li><?php echo esc_html__('Фиксация этапов и результатов', 'global-service'); ?></li>
              <li><?php echo esc_html__('Гарантия на работы и детали', 'global-service'); ?></li>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>