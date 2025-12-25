<?php
/**
 * Plugin Name: GS SEO JSON-LD (MU)
 * Description: Adds lightweight OpenGraph and JSON-LD (Organization/WebSite/WebPage/Service/Breadcrumbs) for demo pages. Safe-by-default and easy to disable.
 * Version: 0.5.0
 */
if (!defined('ABSPATH')) exit;

define('GS_SJL_VER', '0.5.0');

function gs_sjl_disabled(): bool {
  // Emergency switch: create empty file wp-content/gs-disable-seo-jsonld
  return file_exists(WP_CONTENT_DIR . '/gs-disable-seo-jsonld');
}

function gs_sjl_is_front(): bool {
  if (is_admin()) return false;
  if (function_exists('wp_doing_ajax') && wp_doing_ajax()) return false;
  if (function_exists('wp_is_json_request') && wp_is_json_request()) return false;
  return true;
}

function gs_sjl_current_url(): string {
  $scheme = is_ssl() ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? parse_url(home_url('/'), PHP_URL_HOST);
  $uri  = $_SERVER['REQUEST_URI'] ?? '/';
  return $scheme . '://' . $host . $uri;
}

function gs_sjl_safe_title(): string {
  $t = wp_get_document_title();
  return is_string($t) ? $t : '';
}

function gs_sjl_desc_from_ctx(array $ctx): string {
  $city = trim((string)($ctx['city'] ?? ''));
  $eq   = trim((string)($ctx['equipment'] ?? ''));
  $br   = trim((string)($ctx['brand'] ?? ''));
  $s = 'Сервисный центр: диагностика, ремонт и гарантия.';
  if ($eq !== '' || $br !== '' || $city !== '') {
    $s = 'Ремонт ' . ($eq !== '' ? $eq : 'техники') . ($br !== '' ? (' ' . $br) : '') . ($city !== '' ? (' в ' . $city) : '') . '.';
    $s .= ' Диагностика, согласование до ремонта, гарантия.';
  }
  return $s;
}

function gs_sjl_static_desc(string $slug): string {
  $map = [
    'services'  => 'Диагностика, ремонт и запчасти. Согласование стоимости до ремонта, гарантия.',
    'filialy'   => 'Адреса, график и контакты филиалов. Быстрый выбор ближайшего сервиса.',
    'garantiya' => 'Понятные условия гарантии на работы и детали. Как обратиться и что нужно.',
    'contacts'  => 'Телефоны, мессенджеры, обратная связь. Быстрая заявка на ремонт.',
    'search'    => 'Поиск по технике и брендам. Быстрый переход в каталог ремонта.',
  ];
  return $map[$slug] ?? 'Информация о сервисе.';
}

function gs_sjl_page_ctx(): array {
  $ctx = [
    'type' => 'page',
    'slug' => '',
    'title' => gs_sjl_safe_title(),
    'desc' => '',
    'url' => gs_sjl_current_url(),
    'canonical' => '',
  ];

  // Landing demo (query based)
  if (function_exists('gs_ld_is_demo_request') && gs_ld_is_demo_request() && function_exists('gs_ld_ctx')) {
    $c = (array) gs_ld_ctx();
    $ctx['type'] = 'landing';
    $ctx['title'] = function_exists('gs_ld_title_from_ctx') ? (string) gs_ld_title_from_ctx($c) : $ctx['title'];
    $ctx['desc']  = gs_sjl_desc_from_ctx($c);
    $ctx['canonical'] = gs_sjl_current_url();
    $ctx['landing'] = [
      'city' => (string)($c['city'] ?? ''),
      'equipment' => (string)($c['equipment'] ?? ''),
      'brand' => (string)($c['brand'] ?? ''),
    ];
    return $ctx;
  }

  // Catalog page
  if (function_exists('gs_cp_is_catalog_request') && gs_cp_is_catalog_request()) {
    $ctx['type'] = 'catalog';
    $ctx['title'] = 'Каталог ремонта — выбор техники и бренда | Global Service';
    $ctx['desc']  = 'Выберите город, технику и бренд — получите релевантную страницу ремонта.';
    $ctx['canonical'] = home_url('/catalog/');
    return $ctx;
  }

  // Static pages
  if (function_exists('gs_sp_current_template') && gs_sp_current_template() && function_exists('gs_sp_current_slug')) {
    $slug = (string) gs_sp_current_slug();
    $ctx['type'] = 'static';
    $ctx['slug'] = $slug;
    // Title is already set by other plugin, keep it
    $ctx['desc'] = gs_sjl_static_desc($slug);
    $ctx['canonical'] = home_url('/' . $slug . '/');
    return $ctx;
  }

  
// Search utility page
if (is_page('search')) {
  $ctx['type'] = 'search';
  $ctx['title'] = 'Поиск — Global Service';
  $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
  $ctx['desc'] = $q !== '' ? ('Поиск по сайту: ' . $q) : 'Быстрый поиск по услугам, технике и брендам.';
  $ctx['canonical'] = home_url('/search/');
  return $ctx;
}

// Fallback for other pages
  $ctx['desc'] = 'Сервисный центр: ремонт техники, диагностика и гарантия.';
  $ctx['canonical'] = function_exists('wp_get_canonical_url') ? (string) wp_get_canonical_url() : home_url('/');
  return $ctx;
}

function gs_sjl_faq_for_ctx(array $ctx): array {
  // Demo FAQ sets (safe, non-claimy)
  $type = (string)($ctx['type'] ?? '');
  if ($type === 'landing') {
    return [
      ['q' => 'Сколько занимает ремонт?', 'a' => 'Зависит от модели и наличия деталей. Сначала диагностика, затем согласование срока и стоимости.'],
      ['q' => 'Нужно ли оставлять устройство на диагностику?', 'a' => 'Чаще всего — да. Мы фиксируем симптомы и согласуем план работ до ремонта.'],
      ['q' => 'Есть ли гарантия?', 'a' => 'Да, на выполненные работы и детали. Конкретные условия зависят от вида ремонта.'],
      ['q' => 'Как узнать точную цену?', 'a' => 'После диагностики и согласования. На backend добавим реальный прайс и расчёт по справочникам.'],
    ];
  }
  if ($type === 'static' && (string)($ctx['slug'] ?? '') === 'garantiya') {
    return [
      ['q' => 'Что покрывает гарантия?', 'a' => 'Гарантия распространяется на выполненные работы и установленные детали при соблюдении условий эксплуатации.'],
      ['q' => 'Как обратиться по гарантии?', 'a' => 'Сохраните документы/чек и обратитесь в сервис. На backend подключим форму и отслеживание обращения.'],
    ];
  }
  return [];
}

function gs_sjl_faq_entities(array $faq): array {
  $out = [];
  foreach ($faq as $i => $item) {
    $q = trim((string)($item['q'] ?? ''));
    $a = trim((string)($item['a'] ?? ''));
    if ($q === '' || $a === '') continue;
    $out[] = [
      '@type' => 'Question',
      'name' => $q,
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text' => $a,
      ],
    ];
  }
  return $out;
}

function gs_sjl_allow_index_demo(): bool {
  // Override: create empty file wp-content/gs-allow-index-demo
  return file_exists(WP_CONTENT_DIR . '/gs-allow-index-demo');
}

function gs_sjl_should_noindex(array $ctx): bool {
  if (gs_sjl_allow_index_demo()) return false;

  $type = (string)($ctx['type'] ?? '');
  if ($type === 'landing') return true; // query-based demo landing
  if ($type === 'search')  return true; // utility

  $u = (string)($ctx['url'] ?? '');
  if (strpos($u, 'q=') !== false) return true;
  if (preg_match('~[?&]gs_(city|equipment|brand)=~', $u)) return true;

  return false;
}

function gs_sjl_print_meta(array $ctx): void {
  $noindex = gs_sjl_should_noindex($ctx);
  if ($noindex) {
    echo "\n<meta name=\"robots\" content=\"noindex,follow\">";
  }

  $desc = trim((string)($ctx['desc'] ?? ''));
  if ($desc !== '') {
    if (function_exists('mb_substr')) $desc = mb_substr($desc, 0, 180);
    else $desc = substr($desc, 0, 180);
    echo "\n<meta name=\"description\" content=\"" . esc_attr($desc) . "\">";
  }

  $canon = (string)($ctx['canonical'] ?: $ctx['url']);
  if ($canon) {
    echo "\n<link rel=\"canonical\" href=\"" . esc_url($canon) . "\">";
  }

  // Mobile browser UI color (accent)
  echo "\n<meta name=\"theme-color\" content=\"#D2051E\">";

  // OpenGraph + Twitter
  gs_sjl_print_og($ctx);
}

function gs_sjl_print_og(array $ctx): void {
  $title = esc_attr((string)($ctx['title'] ?? ''));
  $desc  = esc_attr((string)($ctx['desc'] ?? ''));
  $url   = esc_url((string)($ctx['canonical'] ?: $ctx['url']));
  $site  = esc_attr(get_bloginfo('name') ?: 'Global Service');

  $loc = get_locale();
  if (is_string($loc) && $loc) {
    $loc = preg_replace('/[^a-zA-Z_]/', '', $loc);
    if ($loc) echo "\n<meta property=\"og:locale\" content=\"" . esc_attr($loc) . "\">";
  }

  echo "\n<meta property=\"og:site_name\" content=\"{$site}\">";
  echo "\n<meta property=\"og:title\" content=\"{$title}\">";
  echo "\n<meta property=\"og:description\" content=\"{$desc}\">";
  echo "\n<meta property=\"og:url\" content=\"{$url}\">";
  echo "\n<meta property=\"og:type\" content=\"website\">";
  echo "\n<meta name=\"twitter:card\" content=\"summary\">\n";
}

function gs_sjl_print_jsonld(array $ctx): void {
  $home = home_url('/');
  $org = [
    '@type' => 'Organization',
    'name'  => get_bloginfo('name') ?: 'Global Service',
    'url'   => $home,
  ];

  $website = [
    '@type' => 'WebSite',
    '@id'   => $home . '#website',
    'url'   => $home,
    'name'  => $org['name'],
    'publisher' => ['@type' => 'Organization', 'name' => $org['name'], 'url' => $home],
    'potentialAction' => [
      '@type' => 'SearchAction',
      'target' => [
        '@type' => 'EntryPoint',
        'urlTemplate' => home_url('/search/?q={search_term_string}'),
      ],
      'query-input' => 'required name=search_term_string',
    ],
  ];

  $page_url = (string)($ctx['canonical'] ?: $ctx['url']);
  $webpage = [
    '@type' => 'WebPage',
    '@id'   => $page_url . '#webpage',
    'url'   => $page_url,
    'name'  => (string)($ctx['title'] ?? ''),
    'description' => (string)($ctx['desc'] ?? ''),
    'isPartOf' => ['@id' => $home . '#website'],
  ];

// FAQ (for demo landing and warranty page)
$faq = gs_sjl_faq_for_ctx($ctx);
$faqEntities = gs_sjl_faq_entities($faq);
if (!empty($faqEntities)) {
  // Mark page as FAQPage
  $webpage['@type'] = ['WebPage', 'FAQPage'];
  $webpage['mainEntity'] = $faqEntities;
}

  $graph = [
    ['@id' => $home . '#org'] + $org,
    $website,
    $webpage,
  ];

  // Breadcrumbs (lightweight)
  $crumbs = [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => $home],
  ];

  if (($ctx['type'] ?? '') === 'catalog') {
    $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'Каталог', 'item' => home_url('/catalog/')];
  } elseif (($ctx['type'] ?? '') === 'static') {
    $name_map = [
      'services' => 'Услуги',
      'filialy' => 'Филиалы',
      'garantiya' => 'Гарантия',
      'contacts' => 'Контакты',
      'search' => 'Поиск',
    ];
    $slug = (string)($ctx['slug'] ?? '');
    $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => ($name_map[$slug] ?? $slug), 'item' => $page_url];
  } elseif (($ctx['type'] ?? '') === 'landing') {
    $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'Каталог', 'item' => home_url('/catalog/')];
    $crumbs[] = ['@type' => 'ListItem', 'position' => 3, 'name' => (string)($ctx['title'] ?? ''), 'item' => $page_url];

    // Service entity
    $l = (array)($ctx['landing'] ?? []);
    $eq = trim((string)($l['equipment'] ?? ''));
    $br = trim((string)($l['brand'] ?? ''));
    $city = trim((string)($l['city'] ?? ''));

    $serviceType = 'Ремонт техники';
    if ($eq !== '') $serviceType = 'Ремонт ' . $eq;
    $service = [
      '@type' => 'Service',
      '@id'   => $page_url . '#service',
      'serviceType' => $serviceType,
      'provider' => ['@id' => $home . '#org'],
      'areaServed' => $city !== '' ? ['@type' => 'City', 'name' => $city] : null,
      'brand' => $br !== '' ? ['@type' => 'Brand', 'name' => $br] : null,
    ];
    // Remove nulls
    $service = array_filter($service, fn($v) => $v !== null);
    $graph[] = $service;
    $webpage['about'] = ['@id' => $page_url . '#service'];
    $graph[2] = $webpage; // update
  }

  $breadcrumb = [
    '@type' => 'BreadcrumbList',
    '@id'   => $page_url . '#breadcrumbs',
    'itemListElement' => $crumbs,
  ];
  $graph[] = $breadcrumb;

  $payload = [
    '@context' => 'https://schema.org',
    '@graph' => $graph,
  ];

  echo "\n<script type=\"application/ld+json\">" . wp_json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . "</script>\n";
}

add_action('wp_head', function() {
  if (gs_sjl_disabled()) return;
  if (!gs_sjl_is_front()) return;

  $ctx = gs_sjl_page_ctx();
  gs_sjl_print_meta($ctx);
  gs_sjl_print_og($ctx);
  gs_sjl_print_jsonld($ctx);
}, 3);
