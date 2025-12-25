<?php if (!defined('ABSPATH')) exit; ?>
<footer class="gs-footer" id="contacts">
  <div class="gs-container">
    <div class="gs-footer__grid">
      <div>
        <div class="gs-footer__title"><?php echo esc_html(get_bloginfo('name')); ?></div>
        <div class="gs-muted"><?php echo esc_html__('Ремонт цифровой техники, бытовой техники и инструмента. Демо-контент будет заменён на реальные данные.', 'global-service'); ?></div>
        <div class="gs-footer__meta"><?php echo esc_html__('На этапе backend подключим справочник филиалов, телефонов, расписания и схемы (Schema.org) для локального SEO.', 'global-service'); ?></div>
      </div>

      <div>
        <div class="gs-footer__title"><?php echo esc_html__('Навигация', 'global-service'); ?></div>
        <div class="gs-muted" style="display:flex; flex-wrap:wrap; gap:10px;">
          <?php
            wp_nav_menu([
              'theme_location' => 'footer',
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

        <div class="gs-footer__meta"><?php echo esc_html('© ' . date('Y') . ' ' . get_bloginfo('name')); ?></div>
      </div>
    </div>
  </div>
</footer>