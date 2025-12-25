<?php if (!defined('ABSPATH')) exit; ?>
<header class="gs-header" role="banner">
  <div class="gs-container">
    <div class="gs-header__row">
      <div class="gs-brand">
        <a class="gs-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
          <span class="gs-logo__mark" aria-hidden="true"></span>
          <span><?php echo esc_html(get_bloginfo('name')); ?></span>
        </a>
      </div>

      <nav class="gs-header__nav" aria-label="<?php echo esc_attr__('Основная навигация', 'global-service'); ?>">
        <div class="gs-nav">
          <button type="button" class="gs-btn" data-gs-open="catalog" aria-haspopup="dialog" aria-controls="gsCatalog">
            <span class="gs-icon" aria-hidden="true"><?php echo gs_inline_svg('default'); ?></span>
            <span><?php echo esc_html__('Каталог', 'global-service'); ?></span>
          </button>

          <?php
            wp_nav_menu([
              'theme_location' => 'primary',
              'container' => false,
              'fallback_cb' => function () {
                echo '<a href="#service">' . esc_html__('Услуги', 'global-service') . '</a>';
                echo '<a href="#branches">' . esc_html__('Филиалы', 'global-service') . '</a>';
                echo '<a href="#guarantee">' . esc_html__('Гарантия', 'global-service') . '</a>';
              },
              'items_wrap' => '%3$s',
            ]);
          ?>
        </div>
      </nav>

      <div class="gs-header__right">
        <div data-gs-city-wrap style="position:relative">
          <button type="button" class="gs-btn" data-gs-city-btn aria-haspopup="listbox" aria-expanded="false">
            <span class="gs-icon" aria-hidden="true"><?php echo gs_inline_svg('default'); ?></span>
            <span data-gs-city-value>—</span>
          </button>

          <div class="gs-pop" data-gs-city-panel hidden>
            <?php foreach (['rostov-na-donu' => 'Ростов-на-Дону', 'krasnodar' => 'Краснодар', 'voronezh' => 'Воронеж'] as $slug => $name): ?>
              <button type="button" class="gs-listbtn" data-gs-city="<?php echo esc_attr($slug); ?>">
                <span><?php echo esc_html($name); ?></span>
                <span aria-hidden="true">↵</span>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <button type="button" class="gs-btn" data-gs-open="search" aria-haspopup="dialog" aria-controls="gsSearch">
          <span class="gs-icon" aria-hidden="true"><?php echo gs_inline_svg('default'); ?></span>
          <span><?php echo esc_html__('Поиск', 'global-service'); ?></span>
        </button>

        <a class="gs-btn gs-btn--primary" href="#contacts"><?php echo esc_html__('Контакты', 'global-service'); ?></a>
      </div>
    </div>
  </div>
</header>

<div id="gsOverlay" class="gs-overlay" aria-hidden="true"></div>

<div id="gsCatalog" class="gs-drawer" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php echo esc_attr__('Каталог', 'global-service'); ?>">
  <div class="gs-drawer__head">
    <div class="gs-drawer__title">
      <span class="gs-icon" aria-hidden="true"><?php echo gs_inline_svg('default'); ?></span>
      <span><?php echo esc_html__('Каталог', 'global-service'); ?></span>
    </div>
    <button class="gs-btn" type="button" data-gs-close><?php echo esc_html__('Закрыть', 'global-service'); ?></button>
  </div>

  <div class="gs-drawer__body">
    <div class="gs-drawer__left" data-gs-catalog-list></div>
    <div class="gs-drawer__right" data-gs-catalog-right></div>
  </div>
</div>

<div id="gsSearch" class="gs-drawer" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php echo esc_attr__('Поиск', 'global-service'); ?>">
  <div class="gs-drawer__head">
    <div class="gs-drawer__title">
      <span class="gs-icon" aria-hidden="true"><?php echo gs_inline_svg('default'); ?></span>
      <span><?php echo esc_html__('Поиск', 'global-service'); ?></span>
    </div>
    <button class="gs-btn" type="button" data-gs-close><?php echo esc_html__('Закрыть', 'global-service'); ?></button>
  </div>

  <div class="gs-search__wrap">
    <input class="gs-input" type="search" placeholder="<?php echo esc_attr__('Например: кофемашина, перфоратор, Makita…', 'global-service'); ?>" data-gs-search-input />
    <div class="gs-search__hint"><?php echo esc_html__('Поиск сейчас работает на демо-данных. На этапе backend подключим реальные справочники и валидатор URL.', 'global-service'); ?></div>
    <div class="gs-search__list" data-gs-search-list></div>
  </div>
</div>

<div class="gs-mobilebar" aria-label="<?php echo esc_attr__('Быстрые действия', 'global-service'); ?>">
  <div class="gs-mobilebar__row">
    <a href="tel:+70000000000" data-gs-track="call"><?php echo esc_html__('Позвонить', 'global-service'); ?></a>
    <a href="#" data-gs-track="wa"><?php echo esc_html__('WhatsApp', 'global-service'); ?></a>
    <a href="#" data-gs-track="tg"><?php echo esc_html__('Telegram', 'global-service'); ?></a>
    <a href="#contacts" data-gs-track="lead"><?php echo esc_html__('Заявка', 'global-service'); ?></a>
  </div>
</div>