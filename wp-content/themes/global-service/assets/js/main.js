(function(){
  'use strict';
  const $ = (sel, root=document) => root.querySelector(sel);
  const $$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));

  const overlay = $('#gsOverlay');
  const catalogDrawer = $('#gsCatalog');
  const searchDrawer = $('#gsSearch');
  const cityValue = $('[data-gs-city-value]');
  const cityBtn = $('[data-gs-city-btn]');
  const cityPanel = $('[data-gs-city-panel]');

  const boot = window.GS_BOOT || { cities: [], defaultCity: 'rostov-na-donu' };

  const equipmentTop = [
    { id: 'bytovaya-tehnika', label: 'Бытовая техника', groups: [
      { title: 'Кухня', tiles: [
        { label: 'Кофемашины', hint: 'Диагностика, ремонт, профилактика' },
        { label: 'Микроволновки', hint: 'Плата, магнетрон, двери' },
        { label: 'Посудомойки', hint: 'Слив, нагрев, ошибки' },
      ]},
      { title: 'Дом', tiles: [
        { label: 'Стиральные машины', hint: 'Подшипники, ТЭН, слив' },
        { label: 'Пылесосы', hint: 'Тяга, питание, щётки' },
        { label: 'Кондиционеры', hint: 'Сервис и ремонт' },
      ]},
    ]},
    { id: 'cifrovaya-tehnika', label: 'Цифровая техника', groups: [
      { title: 'Гаджеты', tiles: [
        { label: 'Смартфоны', hint: 'Экран, питание, разъёмы' },
        { label: 'Планшеты', hint: 'Аккумулятор, зарядка' },
        { label: 'Ноутбуки', hint: 'Чистка, ремонт, апгрейд' },
      ]},
      { title: 'Фото/видео', tiles: [
        { label: 'Фотоаппараты', hint: 'Объектив, матрица, питание' },
        { label: 'Видеокамеры', hint: 'Стабилизация, питание' },
        { label: 'Дроны', hint: 'Диагностика и ремонт' },
      ]},
    ]},
    { id: 'instrument', label: 'Инструмент', groups: [
      { title: 'Электроинструмент', tiles: [
        { label: 'Перфораторы', hint: 'Ударный механизм, щётки' },
        { label: 'Шуруповерты', hint: 'Редуктор, кнопка, батарея' },
        { label: 'Болгарки', hint: 'Подшипники, статор/ротор' },
      ]},
      { title: 'Аккумуляторный', tiles: [
        { label: 'АКБ и зарядки', hint: 'Диагностика, восстановление' },
        { label: 'Платы управления', hint: 'Перепайка, восстановление' },
        { label: 'Корпуса', hint: 'Замена элементов' },
      ]},
    ]},
  ];

  const brands = [
    { label: 'Apple', meta: 'Популярный бренд' },
    { label: 'Samsung', meta: 'Популярный бренд' },
    { label: 'Bosch', meta: 'Популярный бренд' },
    { label: 'Makita', meta: 'Популярный бренд' },
    { label: 'DeLonghi', meta: 'Кофемашины' },
    { label: 'Dyson', meta: 'Пылесосы' },
  ];

  function escapeHtml(s){
    return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
  }

  function getCity(){
    const saved = localStorage.getItem('gs_city');
    if (saved && boot.cities.some(c => c.slug === saved)) return saved;
    return boot.defaultCity || 'rostov-na-donu';
  }
  function setCity(slug){
    localStorage.setItem('gs_city', slug);
    renderCity();
  }
  function renderCity(){
    if (!cityValue) return;
    const slug = getCity();
    const c = boot.cities.find(x => x.slug === slug);
    cityValue.textContent = c ? c.name : slug;
  }

  let lastActive = null;
  let trapRoot = null;

  function openDrawer(drawer){
    if (!drawer) return;
    lastActive = document.activeElement;

    overlay?.classList.add('is-open');
    drawer.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');

    trapFocus(drawer);
  }

  function closeAll(){
    overlay?.classList.remove('is-open');
    [catalogDrawer, searchDrawer].forEach(d => {
      if (!d) return;
      d.classList.remove('is-open');
      d.setAttribute('aria-hidden','true');
    });
    releaseFocus();
  }

  function trapFocus(root){
    trapRoot = root;
    const focusable = $$('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])', root)
      .filter(el => !el.hasAttribute('disabled') && el.getAttribute('aria-hidden') !== 'true');

    const first = focusable[0];
    const last  = focusable[focusable.length - 1];
    if (first) first.focus();

    function onKey(e){
      if (e.key === 'Escape') { closeAll(); return; }
      if (e.key !== 'Tab') return;
      if (!first || !last) return;

      if (e.shiftKey && document.activeElement === first){
        e.preventDefault(); last.focus();
      } else if (!e.shiftKey && document.activeElement === last){
        e.preventDefault(); first.focus();
      }
    }
    document.addEventListener('keydown', onKey);
    root._gsTrapHandler = onKey;
  }

  function releaseFocus(){
    if (trapRoot && trapRoot._gsTrapHandler){
      document.removeEventListener('keydown', trapRoot._gsTrapHandler);
    }
    trapRoot = null;
    if (lastActive && lastActive.focus) lastActive.focus();
    lastActive = null;
  }

  const list = $('[data-gs-catalog-list]');
  const right = $('[data-gs-catalog-right]');

  function iconPlus(){
    return `<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M7 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
      <path d="M12 7v10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".55"/>
    </svg>`;
  }

  function renderCatalog(){
    if (!list || !right) return;
    list.innerHTML = equipmentTop.map((x, i) => (
      `<button class="gs-listbtn ${i===0?'is-active':''}" type="button" data-gs-catalog-id="${x.id}">
        <span>${escapeHtml(x.label)}</span><span aria-hidden="true">›</span>
      </button>`
    )).join('');

    renderCatalogRight(equipmentTop[0]?.id || '');
  }

  function renderCatalogRight(id){
    const item = equipmentTop.find(x => x.id === id) || equipmentTop[0];
    if (!item || !right) return;

    right.innerHTML = item.groups.map(g => `
      <section style="margin-bottom:16px">
        <div class="gs-kicker" style="margin-bottom:10px">${escapeHtml(g.title)}</div>
        <div class="gs-gridtiles">
          ${g.tiles.map(t => `
            <a class="gs-tile" href="#" data-gs-track="catalog_tile">
              <div class="gs-tile__top">
                <span class="gs-icon" aria-hidden="true">${iconPlus()}</span>
                <span>${escapeHtml(t.label)}</span>
              </div>
              <div class="gs-tile__sub">${escapeHtml(t.hint)}</div>
            </a>
          `).join('')}
        </div>
      </section>
    `).join('');
  }

  list?.addEventListener('click', (e) => {
    const b = e.target.closest('button[data-gs-catalog-id]');
    if (!b) return;
    const id = b.getAttribute('data-gs-catalog-id');
    $$('.gs-listbtn', list).forEach(x => x.classList.remove('is-active'));
    b.classList.add('is-active');
    renderCatalogRight(id);
  });

  function searchIndex(){
    const eq = equipmentTop.flatMap(t => t.groups.flatMap(g => g.tiles.map(x => ({ type:'equipment', title:x.label, meta:t.label }))));
    const br = brands.map(x => ({ type:'brand', title:x.label, meta:x.meta }));
    return [...eq, ...br];
  }
  const index = searchIndex();

  const qInput = $('[data-gs-search-input]');
  const qList  = $('[data-gs-search-list]');
  let debounceT = null;

  qInput?.addEventListener('input', () => {
    const q = qInput.value.trim().toLowerCase();
    clearTimeout(debounceT);
    debounceT = setTimeout(() => renderSuggest(q), 110);
  });

  function renderSuggest(q){
    if (!qList) return;
    if (!q){ qList.innerHTML = ''; return; }
    const found = index.filter(x => x.title.toLowerCase().includes(q)).slice(0, 10);

    qList.innerHTML = found.map(x => `
      <a class="gs-suggest" href="#" data-gs-track="search_suggest">
        <div>
          <div class="gs-suggest__title">${escapeHtml(x.title)}</div>
          <div class="gs-suggest__meta">${escapeHtml(x.type)} · ${escapeHtml(x.meta)}</div>
        </div>
        <span aria-hidden="true">↵</span>
      </a>
    `).join('') || `<div class="gs-muted" style="padding:12px 6px">Ничего не найдено</div>`;
  }

  if (cityBtn && cityPanel){
    cityBtn.addEventListener('click', () => {
      cityPanel.hidden = !cityPanel.hidden;
      cityBtn.setAttribute('aria-expanded', cityPanel.hidden ? 'false' : 'true');
      if (!cityPanel.hidden) cityPanel.querySelector('button')?.focus();
    });
    cityPanel.addEventListener('click', (e) => {
      const b = e.target.closest('button[data-gs-city]');
      if (!b) return;
      setCity(b.getAttribute('data-gs-city'));
      cityPanel.hidden = true;
      cityBtn.setAttribute('aria-expanded','false');
    });
  }

  $$('[data-gs-open]').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.getAttribute('data-gs-open');
      if (target === 'catalog') openDrawer(catalogDrawer);
      if (target === 'search') { openDrawer(searchDrawer); setTimeout(() => qInput?.focus(), 0); }
    });
  });

  $$('[data-gs-close]').forEach(btn => btn.addEventListener('click', closeAll));
  overlay?.addEventListener('click', closeAll);

  document.addEventListener('click', (e) => {
    const isBtn = e.target.closest('[data-gs-open]');
    const inDrawer = e.target.closest('.gs-drawer');
    const inCity = e.target.closest('[data-gs-city-wrap]');
    if (isBtn || inDrawer || inCity) return;

    if (overlay?.classList.contains('is-open')) closeAll();
    if (cityPanel && !cityPanel.hidden){
      cityPanel.hidden = true;
      cityBtn?.setAttribute('aria-expanded','false');
    }
  });

  renderCity();
  renderCatalog();
})();