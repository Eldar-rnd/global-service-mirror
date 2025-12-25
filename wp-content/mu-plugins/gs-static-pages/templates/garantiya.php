\
<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<main id="gsMain" class="gs-sp" role="main">
  <div class="gs-sp__container">
<nav class="gs-sp__breadcrumb" aria-label="Хлебные крошки">
  <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
  <span aria-hidden="true">/</span>
  <span><?php echo esc_html('Гарантия'); ?></span>
</nav>

    <section class="gs-sp__hero">
      <div>
        <div class="gs-sp__kicker">Гарантия</div>
        <h1 class="gs-sp__h1">Понятные условия гарантии</h1>
        <p class="gs-sp__lead">
          Это демо‑страница структуры. На backend подключим конкретные сроки по видам работ и деталям, форму обращения и статус рассмотрения.
          Здесь важнее логика: что покрывается и как обратиться без “квеста”.
        </p>

        <div class="gs-sp__cta" style="margin-top:14px;">
          <a class="gs-sp__btn gs-sp__btn--accent" href="<?php echo esc_url(home_url('/contacts/')); ?>">Связаться</a>
          <button class="gs-sp__btn" type="button" data-gs-open-search>Найти услугу</button>
          <a class="gs-sp__btn" href="<?php echo esc_url(home_url('/filialy/')); ?>">Филиалы</a>
        </div>

        <div class="gs-sp__steps" aria-label="Как обратиться по гарантии">
          <div class="gs-sp__step">
            <div class="gs-sp__stepn">1</div>
            <div class="gs-sp__stept">Документы</div>
            <div class="gs-sp__stepd">Сохраните чек/заказ‑наряд и отметьте проблему (когда проявляется, что менялось).</div>
          </div>
          <div class="gs-sp__step">
            <div class="gs-sp__stepn">2</div>
            <div class="gs-sp__stept">Обращение</div>
            <div class="gs-sp__stepd">Приходите в филиал или оставьте заявку. На backend сделаем форму и трекинг.</div>
          </div>
          <div class="gs-sp__step">
            <div class="gs-sp__stepn">3</div>
            <div class="gs-sp__stept">Проверка</div>
            <div class="gs-sp__stepd">Проверяем повторяемость дефекта и условия эксплуатации. Затем — решение и согласование.</div>
          </div>
        </div>
      </div>
    </section>

    <?php if (function_exists('gs_mb_trust_strip')) { gs_mb_trust_strip(['id'=>'trust','title'=>'На что опираемся','lead'=>'Гарантия — часть процесса: документы, фиксация состояния и понятное согласование до ремонта.']); } ?>

    <section class="gs-sp__section" id="coverage">
      <h2>Что покрывается</h2>
      <div class="gs-sp__grid" style="margin-top:12px;">
        <div class="gs-sp__card">
          <div class="gs-sp__tag">Работы</div>
          <p class="gs-sp__muted" style="margin-top:10px;">Если проблема связана с выполненными работами и подтверждается при проверке.</p>
        </div>
        <div class="gs-sp__card">
          <div class="gs-sp__tag">Детали</div>
          <p class="gs-sp__muted" style="margin-top:10px;">Если установленные детали дают дефект в рамках условий эксплуатации.</p>
        </div>
        <div class="gs-sp__card">
          <div class="gs-sp__tag">Повторная неисправность</div>
          <p class="gs-sp__muted" style="margin-top:10px;">Если проявляется аналогичная проблема после ремонта — разбираемся в приоритетном порядке.</p>
        </div>
      </div>
    </section>

    <section class="gs-sp__section" id="docs">
      <h2>Что нужно для обращения</h2>
      <div class="gs-sp__chips">
        <button class="gs-sp__chip" type="button" data-gs-open-search="заказ-наряд">Заказ‑наряд</button>
        <button class="gs-sp__chip" type="button" data-gs-open-search="чек">Чек/оплата</button>
        <button class="gs-sp__chip" type="button" data-gs-open-search="описание проблемы">Описание проблемы</button>
        <button class="gs-sp__chip" type="button" data-gs-open-search="фото приёма">Фото приёма</button>
      </div>
      <div class="gs-sp__note" style="margin-top:12px;">
        В демо это подсказки. На backend добавим загрузку документов и фото, чтобы снизить количество “уточняющих звонков”.
      </div>
    </section>

    <section class="gs-sp__section" id="faq">
      <h2>Частые вопросы по гарантии</h2>
      <p class="gs-sp__muted">Сейчас демо. На backend добавим конкретные сроки, исключения и форму обращения.</p>

      <div class="gs-acc" data-gs-accordion="single" style="margin-top:12px;">
        <div class="gs-acc__item is-open" data-gs-acc-item>
          <button class="gs-acc__btn" type="button" data-gs-acc-btn>
            Что покрывает гарантия?
            <span class="gs-acc__chev" aria-hidden="true">▾</span>
          </button>
          <div class="gs-acc__panel" data-gs-acc-panel>
            Гарантия распространяется на выполненные работы и установленные детали при соблюдении условий эксплуатации.
          </div>
        </div>

        <div class="gs-acc__item" data-gs-acc-item>
          <button class="gs-acc__btn" type="button" data-gs-acc-btn>
            Как обратиться по гарантии?
            <span class="gs-acc__chev" aria-hidden="true">▾</span>
          </button>
          <div class="gs-acc__panel" data-gs-acc-panel>
            Сохраните документы и обратитесь в сервис. На backend подключим форму обращения и отслеживание статуса.
          </div>
        </div>

        <div class="gs-acc__item" data-gs-acc-item>
          <button class="gs-acc__btn" type="button" data-gs-acc-btn>
            Сколько действует гарантия?
            <span class="gs-acc__chev" aria-hidden="true">▾</span>
          </button>
          <div class="gs-acc__panel" data-gs-acc-panel>
            Срок зависит от вида работ и деталей. В демо мы не публикуем конкретные числа — чтобы не вводить в заблуждение.
          </div>
        </div>

        <div class="gs-acc__item" data-gs-acc-item>
          <button class="gs-acc__btn" type="button" data-gs-acc-btn>
            Когда гарантия может не применяться?
            <span class="gs-acc__chev" aria-hidden="true">▾</span>
          </button>
          <div class="gs-acc__panel" data-gs-acc-panel>
            Когда повреждение вызвано внешними факторами, нарушением условий эксплуатации или вмешательством третьих лиц.
            На backend сформируем список исключений, привязанный к типу техники.
          </div>
        </div>
      </div>

      <div class="gs-sp__cta" style="margin-top:14px;">
        <a class="gs-sp__btn gs-sp__btn--accent" href="<?php echo esc_url(home_url('/contacts/')); ?>">Связаться</a>
        <button class="gs-sp__btn" type="button" data-gs-open-search>Найти услугу</button>
      </div>
    </section>

    <?php if (function_exists('gs_mb_cta_panel')) { gs_mb_cta_panel(['title'=>'Хотите, чтобы всё было прозрачно?','text'=>'Следующий этап — backend: справочник работ/деталей, сроки, документы, статусы и автоматическая перелинковка.']); } ?>
  </div>
</main>
<?php get_footer(); ?>