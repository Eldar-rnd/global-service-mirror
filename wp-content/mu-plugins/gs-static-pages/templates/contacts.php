<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<main id="gsMain" class="gs-sp" role="main">
  <div class="gs-sp__container">
<nav class="gs-sp__breadcrumb" aria-label="Хлебные крошки">
  <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
  <span aria-hidden="true">/</span>
  <span><?php echo esc_html('Контакты'); ?></span>
</nav>

    <section class="gs-sp__hero">
      <div>
        <div class="gs-sp__kicker">Контакты</div>
        <h1 class="gs-sp__h1">Связаться с сервисом в <span data-gs-city-label>—</span></h1>
        <p class="gs-sp__lead">
          Демо‑контакты. На backend подключим реальные телефоны, филиалы, формы заявок, антиспам и аналитику.
        </p>
        <div class="gs-sp__cta">
          <a class="gs-sp__btn gs-sp__btn--accent" href="<?php echo esc_url(home_url('/catalog/')); ?>">Каталог</a>
          <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/filialy/')); ?>">Филиалы</a>
        </div>
      </div>
      <aside id="gs-quickform" class="gs-sp__side">
        <div class="gs-sp__label">Быстрая заявка</div>
        <input class="gs-sp__input" type="tel" placeholder="+7 (___) ___‑__‑__" />
        <input class="gs-sp__input" type="text" placeholder="Что сломалось? (демо)" style="margin-top:10px;" />
        <button data-gs-demo-submit="1" class="gs-sp__btn gs-sp__btn--accent" type="button" style="margin-top:10px; width:100%;">Отправить (демо)</button>
        <div class="gs-sp__note">На backend: реальная отправка, антиспам, согласие, цели аналитики.</div>
      </aside>
    </section>

<?php if (function_exists('gs_mb_trust_strip')) { gs_mb_trust_strip(['id'=>'trust', 'title'=>'Понятный процесс', 'lead'=>'Мы не обещаем невозможного. Сначала диагностика, затем согласование, потом ремонт.']); } ?>


    <section class="gs-sp__grid" aria-label="Контакты">
      <div class="gs-sp__card">
        <h2>Телефон</h2>
        <p>+7 (___) ___‑__‑__</p>
      </div>
      <div class="gs-sp__card">
        <h2>Мессенджеры</h2>
        <p>WhatsApp / Telegram (подключим ссылки)</p>
      </div>
      <div class="gs-sp__card">
        <h2>Почта</h2>
        <p>info@… (демо)</p>
      </div>
    </section>

<section class="gs-sp__section" id="actions" style="margin-top:18px;">
  <h2>Способы связи</h2>
  <p class="gs-sp__muted">Демо‑кнопки. На backend подключим реальные номера/ссылки, трекинг и цели аналитики.</p>
  <div class="gs-sp__cta" style="margin-top:12px;">
    <a class="gs-sp__btn gs-sp__btn--accent" href="#" data-gs-demo-submit="1">Позвонить (демо)</a>
    <a class="gs-sp__btn" href="#" data-gs-demo-submit="1">WhatsApp (демо)</a>
    <a class="gs-sp__btn" href="#" data-gs-demo-submit="1">Telegram (демо)</a>
  </div>
  <div class="gs-sp__note" style="margin-top:10px;">
    Переходы и события подключим на backend. Сейчас важно “собрать” UX без ложных обещаний.
  </div>
<div class="gs-sp__cta" style="margin-top:12px;">
  <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/filialy/')); ?>">Посмотреть филиалы</a>
  <button class="gs-sp__btn" type="button" data-gs-open-search>Найти услугу</button>
</div>
</section>

  </div>
</main>
<?php get_footer(); ?>
