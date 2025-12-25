<?php
/**
 * Plugin Name: GS Pages Seed (MU)
 * Description: Admin-only helper to create/update basic pages. Safe no-op on frontend.
 * Version: 0.6.4
 */
if (!defined('ABSPATH')) { exit; }

define('GS_PAGES_SEED_VER', '0.6.4');

function gs_pages_seed_disabled(): bool {
  return file_exists(WP_CONTENT_DIR . '/gs-disable-pages-seed');
}

if (gs_pages_seed_disabled()) {
  return;
}

// IMPORTANT: Do nothing on the frontend to avoid side effects and to keep performance predictable.
if (!is_admin()) {
  return;
}

add_action('admin_menu', function() {
  add_management_page(
    'GS Pages',
    'GS Pages',
    'manage_options',
    'gs-seed-pages',
    'gs_pages_seed_render'
  );
}, 50);

function gs_pages_seed_render(): void {
  if (!current_user_can('manage_options')) {
    wp_die('Access denied');
  }

  echo '<div class="wrap">';
  echo '<h1>GS Pages</h1>';

  if (!empty($_GET['gs_seed_done'])) {
    echo '<div class="notice notice-success"><p>Готово. Базовые страницы созданы/обновлены.</p></div>';
  }

  echo '<p>Инструмент создаёт/обновляет базовые страницы сайта. Если страница уже существует — контент не перезаписывается (обновляем только заголовок при необходимости).</p>';
  echo '<p>Если потребуется полностью отключить этот инструмент: создайте пустой файл <code>wp-content/gs-disable-pages-seed</code>.</p>';

  echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
  wp_nonce_field('gs_seed_pages');
  echo '<input type="hidden" name="action" value="gs_seed_pages" />';
  submit_button('Создать/обновить базовые страницы');
  echo '</form>';

  echo '</div>';
}

add_action('admin_post_gs_seed_pages', function() {
  if (!current_user_can('manage_options')) {
    wp_die('Access denied');
  }
  check_admin_referer('gs_seed_pages');

  $pages = [
    'services'     => 'Услуги',
    'prices'       => 'Цены',
    'warranty'     => 'Гарантия',
    'branches'     => 'Филиалы',
    'contacts'     => 'Контакты',
    'landing-demo' => 'Демо-посадочная',
    // NOTE: /catalog/ обслуживается MU-плагином как виртуальная страница; здесь не создаём page "catalog",
    // чтобы не было двусмысленности и конфликтов URL.
  ];

  foreach ($pages as $slug => $title) {
    $existing = get_page_by_path($slug, OBJECT, 'page');

    if ($existing && !empty($existing->ID)) {
      // Update title only if needed.
      if (get_the_title($existing->ID) !== $title) {
        wp_update_post([
          'ID'         => (int)$existing->ID,
          'post_title' => $title,
        ]);
      }
      continue;
    }

    wp_insert_post([
      'post_type'   => 'page',
      'post_status' => 'publish',
      'post_name'   => $slug,
      'post_title'  => $title,
    ]);
  }

  wp_safe_redirect(add_query_arg(['page' => 'gs-seed-pages', 'gs_seed_done' => 1], admin_url('tools.php')));
  exit;
});
