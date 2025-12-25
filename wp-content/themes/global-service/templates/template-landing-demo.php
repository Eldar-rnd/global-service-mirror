<?php
/**
 * Template Name: Landing (Demo)
 * Description: Demo landing template (city × equipment × brand). Backend phase will connect real data & canonical URLs.
 */
if (!defined('ABSPATH')) exit;

$ctx = [
  'city'      => isset($_GET['gs_city']) ? wp_strip_all_tags((string)$_GET['gs_city']) : 'Ростов-на-Дону',
  'equipment' => isset($_GET['gs_equipment']) ? wp_strip_all_tags((string)$_GET['gs_equipment']) : 'кофемашины',
  'brand'     => isset($_GET['gs_brand']) ? wp_strip_all_tags((string)$_GET['gs_brand']) : 'DeLonghi',
];

if (function_exists('gs_demo_landing_context')) {
  $ctx = array_merge($ctx, (array) gs_demo_landing_context());
}

$city = trim($ctx['city']);
$equipment = trim($ctx['equipment']);
$brand = trim($ctx['brand']);

get_header();
?>
<main id="gsMain" role="main">
  <section class="gs-landing-hero">
    <div class="gs-container">
      <nav class="gs-breadcrumb" aria-label="<?php echo esc_attr__('Хлебные крошки', 'global-service'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Главная', 'global-service'); ?></a>
        <span aria-hidden="true">/</span>
        <span><?php echo esc_html($city); ?></span>
        <span aria-hidden="true">/</span>
        <span><?php echo esc_html__('Ремонт', 'global-service'); ?></span>
        <span aria-hidden="true">/</span>
        <span><?php echo esc_html($equipment . ' ' . $brand); ?></span>
      </nav>

      <div class="gs-landing-hero__grid">
        <div>
          <div class="gs-pill">
            <span class="gs-icon" aria-hidden="true"><?php echo function_exists('gs_inline_svg') ? gs_inline_svg('default') : ''; ?></span>
            <span><?php echo esc_html($city); ?></span>
          </div>

          <h1 class="gs-h1" style="margin-top:12px;">
            <?php echo esc_html(sprintf('Ремонт %s %s в %s', $equipment, $brand, $city)); ?>
          </h1>

          <p class="gs-muted" style="margin-top:14px;max-width:86ch;">
            <?php echo esc_html__('Это демо-шаблон посадочной. На этапе backend подключим валидатор сочетаний бренд×техника, канонические URL, цены из прайса и ближайшие филиалы.', 'global-service'); ?>
          </p>

          <div class="gs-stats">
            <div class="gs-stat">
              <div class="gs-stat__k"><?php echo esc_html__('Цена от', 'global-service'); ?></div>
              <div class="gs-stat__v"><?php echo esc_html__('590 ₽', 'global-service'); ?></div>
            </div>
            <div class="gs-stat">
              <div class="gs-stat__k"><?php echo esc_html__('Сроки', 'global-service'); ?></div>
              <div class="gs-stat__v"><?php echo esc_html__('от 30 минут', 'global-service'); ?></div>
            </div>
            <div class="gs-stat">
              <div class="gs-stat__k"><?php echo esc_html__('Гарантия', 'global-service'); ?></div>
              <div class="gs-stat__v"><?php echo esc_html__('до 12 месяцев', 'global-service'); ?></div>
            </div>
          </div>

          <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px;">
            <a class="gs-btn gs-btn--primary" href="#lead"><?php echo esc_html__('Оставить заявку', 'global-service'); ?></a>
            <a class="gs-btn" href="#prices"><?php echo esc_html__('Цены', 'global-service'); ?></a>
            <a class="gs-btn" href="#faq"><?php echo esc_html__('FAQ', 'global-service'); ?></a>
          </div>

          <div class="gs-ctaBand">
            <div class="gs-ctaBand__row">
              <div>
                <div class="gs-kicker"><?php echo esc_html__('Прозрачность', 'global-service'); ?></div>
                <div class="gs-muted" style="margin-top:8px;">
                  <?php echo esc_html__('Сначала диагностика и согласование. Без “сюрпризов” в конце.', 'global-service'); ?>
                </div>
              </div>
              <a class="gs-btn" href="#how"><?php echo esc_html__('Как работаем', 'global-service'); ?></a>
            </div>
          </div>
        </div>

        <aside class="gs-panel" id="lead">
          <div class="gs-kicker"><?php echo esc_html__('Быстрая заявка', 'global-service'); ?></div>
          <h2 class="gs-h3" style="margin-top:10px;"><?php echo esc_html__('Опишите проблему — перезвоним', 'global-service'); ?></h2>

          <form style="margin-top:14px;">
            <label class="gs-muted" style="display:block;font-size:13px;margin:10px 0 6px;"><?php echo esc_html__('Телефон', 'global-service'); ?></label>
            <input class="gs-input" type="tel" placeholder="+7 (___) ___-__-__" inputmode="tel" autocomplete="tel" />
            <label class="gs-muted" style="display:block;font-size:13px;margin:12px 0 6px;"><?php echo esc_html__('Что случилось', 'global-service'); ?></label>
            <input class="gs-input" type="text" placeholder="<?php echo esc_attr__('Например: не включается / течёт / ошибка на дисплее', 'global-service'); ?>" />
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
              <button class="gs-btn gs-btn--primary" type="button"><?php echo esc_html__('Отправить', 'global-service'); ?></button>
              <a class="gs-btn" href="tel:+70000000000"><?php echo esc_html__('Позвонить', 'global-service'); ?></a>
            </div>
            <p class="gs-muted" style="font-size:12px;margin-top:12px;"><?php echo esc_html__('Демо-форма. Backend добавит отправку, антиспам, цели аналитики.', 'global-service'); ?></p>
          </form>
        </aside>
      </div>
    </div>
  </section>

  <section class="gs-section" id="prices" style="padding-top:0;">
    <div class="gs-container">
      <div class="gs-grid2">
        <div>
          <h2 class="gs-h2"><?php echo esc_html__('Цены на популярные работы', 'global-service'); ?></h2>
          <p class="gs-muted" style="margin-top:10px;max-width:86ch;"><?php echo esc_html__('Демо-структура. На backend подставим реальные позиции и цены из прайса.', 'global-service'); ?></p>

          <div class="gs-priceList">
            <div class="gs-priceRow"><span><?php echo esc_html__('Диагностика', 'global-service'); ?></span><strong><?php echo esc_html__('0 ₽', 'global-service'); ?></strong></div>
            <div class="gs-priceRow"><span><?php echo esc_html__('Замена помпы', 'global-service'); ?></span><strong><?php echo esc_html__('от 1 490 ₽', 'global-service'); ?></strong></div>
            <div class="gs-priceRow"><span><?php echo esc_html__('Ремонт платы', 'global-service'); ?></span><strong><?php echo esc_html__('от 2 490 ₽', 'global-service'); ?></strong></div>
          </div>

          <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;">
            <span class="gs-badge"><?php echo esc_html__('Цены “от” — после диагностики', 'global-service'); ?></span>
            <span class="gs-badge"><?php echo esc_html__('Согласование до ремонта', 'global-service'); ?></span>
          </div>
        </div>

        <aside class="gs-panel" id="how">
          <h2 class="gs-h3"><?php echo esc_html__('Как мы работаем', 'global-service'); ?></h2>
          <ol class="gs-muted" style="margin:12px 0 0;padding-left:18px;display:grid;gap:8px;">
            <li><?php echo esc_html__('Фиксируем симптомы и принимаем устройство', 'global-service'); ?></li>
            <li><?php echo esc_html__('Диагностируем и озвучиваем стоимость/срок', 'global-service'); ?></li>
            <li><?php echo esc_html__('Ремонтируем только после согласования', 'global-service'); ?></li>
            <li><?php echo esc_html__('Тестируем и выдаём с гарантией', 'global-service'); ?></li>
          </ol>
          <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
            <a class="gs-btn gs-btn--primary" href="#lead"><?php echo esc_html__('Оставить заявку', 'global-service'); ?></a>
            <a class="gs-btn" href="#reviews"><?php echo esc_html__('Отзывы', 'global-service'); ?></a>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <section class="gs-section" id="faq">
    <div class="gs-container">
      <h2 class="gs-h2"><?php echo esc_html__('Вопросы и ответы', 'global-service'); ?></h2>
      <p class="gs-muted" style="margin-top:10px;max-width:86ch;"><?php echo esc_html__('FAQ важен для конверсии. На backend будем собирать FAQ по типу техники и бренду.', 'global-service'); ?></p>

      <div class="gs-faqDetails">
        <details class="gs-details">
          <summary class="gs-details__sum"><?php echo esc_html__('Сколько стоит ремонт?', 'global-service'); ?></summary>
          <div class="gs-details__body"><?php echo esc_html__('Стоимость зависит от неисправности. Мы делаем диагностику, согласуем цену и только потом ремонтируем.', 'global-service'); ?></div>
        </details>

        <details class="gs-details">
          <summary class="gs-details__sum"><?php echo esc_html__('Есть ли гарантия?', 'global-service'); ?></summary>
          <div class="gs-details__body"><?php echo esc_html__('Да. Гарантия зависит от вида работ и запчастей, обычно до 12 месяцев.', 'global-service'); ?></div>
        </details>

        <details class="gs-details">
          <summary class="gs-details__sum"><?php echo esc_html__('Сколько времени занимает ремонт?', 'global-service'); ?></summary>
          <div class="gs-details__body"><?php echo esc_html__('Некоторые работы делаем в день обращения. Точные сроки зависят от сложности и наличия запчастей.', 'global-service'); ?></div>
        </details>
      </div>
    </div>
  </section>

  <section class="gs-section" id="reviews" style="padding-top:0;">
    <div class="gs-container">
      <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <div>
          <h2 class="gs-h2"><?php echo esc_html__('Отзывы клиентов', 'global-service'); ?></h2>
          <div class="gs-muted" style="margin-top:10px;"><?php echo esc_html__('Демо. На backend подключим реальные отзывы и рейтинги.', 'global-service'); ?></div>
        </div>
        <div class="gs-pill"><span aria-hidden="true">★★★★★</span><span><?php echo esc_html__('4.8 из 5', 'global-service'); ?></span></div>
      </div>

      <div class="gs-cards2">
        <div class="gs-review">
          <div class="gs-review__top"><strong><?php echo esc_html__('Александр', 'global-service'); ?></strong><span class="gs-stars" aria-hidden="true">★★★★★</span></div>
          <div class="gs-review__txt"><?php echo esc_html__('Быстро нашли проблему, согласовали цену и сделали в тот же день.', 'global-service'); ?></div>
        </div>
        <div class="gs-review">
          <div class="gs-review__top"><strong><?php echo esc_html__('Мария', 'global-service'); ?></strong><span class="gs-stars" aria-hidden="true">★★★★★</span></div>
          <div class="gs-review__txt"><?php echo esc_html__('Понравилась прозрачность: всё объяснили, стоимость до ремонта, гарантию выдали.', 'global-service'); ?></div>
        </div>
      </div>
    </div>
  </section>

  <section class="gs-section" id="branches" style="padding-top:0;">
    <div class="gs-container">
      <h2 class="gs-h2"><?php echo esc_html__('Филиалы рядом', 'global-service'); ?></h2>
      <p class="gs-muted" style="margin-top:10px;max-width:86ch;"><?php echo esc_html__('Демо. На backend подключим справочник филиалов по городу.', 'global-service'); ?></p>

      <div class="gs-cards2">
        <div class="gs-review">
          <div class="gs-review__top"><strong><?php echo esc_html__('Филиал №1', 'global-service'); ?></strong><span class="gs-pill"><?php echo esc_html__('Сегодня до 20:00', 'global-service'); ?></span></div>
          <div class="gs-review__txt"><?php echo esc_html__('Улица Примерная, 10 · 10 минут пешком', 'global-service'); ?></div>
        </div>
        <div class="gs-review">
          <div class="gs-review__top"><strong><?php echo esc_html__('Филиал №2', 'global-service'); ?></strong><span class="gs-pill"><?php echo esc_html__('Завтра с 09:00', 'global-service'); ?></span></div>
          <div class="gs-review__txt"><?php echo esc_html__('Проспект Демонстрации, 25 · 15 минут на авто', 'global-service'); ?></div>
        </div>
      </div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:14px;">
        <a class="gs-btn gs-btn--primary" href="#lead"><?php echo esc_html__('Оставить заявку', 'global-service'); ?></a>
        <a class="gs-btn" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('На главную', 'global-service'); ?></a>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>