\
<?php
if (!defined('ABSPATH')) exit;
status_header(404);
nocache_headers();
get_header();
?>
<main class="gs-err">
  <section class="gs-err__hero">
    <div class="gs-container">
      <div class="gs-kicker">404</div>
      <h1 class="gs-err__h1">Страница не найдена</h1>
      <div class="gs-muted gs-err__lead">
        Возможно, ссылка устарела или страница ещё не создана. Попробуйте поиск или откройте каталог.
      </div>

      <div class="gs-err__cta">
        <button class="gs-btn gs-btn--primary" type="button" data-gs-open-search>Быстрый поиск (Ctrl+K)</button>
        <button class="gs-btn" type="button" data-gs-open-catalog>Каталог</button>
        <a class="gs-btn" href="<?php echo esc_url(home_url('/')); ?>">На главную</a>
      </div>

      <div class="gs-err__links">
        <a href="<?php echo esc_url(home_url('/services/')); ?>">Услуги</a>
        <a href="<?php echo esc_url(home_url('/garantiya/')); ?>">Гарантия</a>
        <a href="<?php echo esc_url(home_url('/filialy/')); ?>">Филиалы</a>
        <a href="<?php echo esc_url(home_url('/contacts/')); ?>">Контакты</a>
      </div>
      <div class="gs-err__note">Текущий город: <span data-gs-city-label>—</span></div>
    </div>
  </section>
</main>
<?php get_footer(); ?>