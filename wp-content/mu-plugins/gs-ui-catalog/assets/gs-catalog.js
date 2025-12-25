(function(){
  'use strict';

  const data = window.GS_CATALOG || null;
  if (!data) return;

  const qs = (s, r=document) => r.querySelector(s);
  const qsa = (s, r=document) => Array.from(r.querySelectorAll(s));

  const root = qs('[data-gs-catalog]');
  if (!root) return;

  const closeEls = qsa('[data-gs-catalog-close]', root);
  const catsEl = qs('[data-gs-catalog-cats]', root);
  const itemsEl = qs('[data-gs-catalog-items]', root);
  const brandsEl = qs('[data-gs-catalog-brands]', root);
  const crumbsEl = qs('[data-gs-catalog-crumbs]', root);
  const search = qs('#gsCatalogSearch', root);

  const cityBtn = qs('[data-gs-catalog-citybtn]', root);
  const cityEl = qs('[data-gs-catalog-city]', root);
  const cityMenu = qs('[data-gs-catalog-citymenu]', root);
  const cityClose = qs('[data-gs-catalog-cityclose]', root);
  const cityList = qs('[data-gs-catalog-citylist]', root);

  let state = {
    open: false,
    activeCatIndex: 0,
    activeItemSlug: null,
    city: (data.defaultCity || (data.cities && data.cities[0])) || 'Ростов-на-Дону',
    q: ''
  };

  function lockScroll(on){
    document.documentElement.classList.toggle('gs-uic-lock', on);
    document.body.classList.toggle('gs-uic-lock', on);
  }

  function setHidden(el, hidden){
    if (!el) return;
    if (hidden){
      el.setAttribute('hidden','');
      el.setAttribute('aria-hidden','true');
    } else {
      el.removeAttribute('hidden');
      el.setAttribute('aria-hidden','false');
    }
  }

  function open(){
    if (state.open) return;
    state.open = true;
    setHidden(root, false);
    lockScroll(true);
    render();
    setTimeout(() => { try{ search && search.focus(); }catch(e){} }, 0);
    document.addEventListener('keydown', onKey);
  }

  function close(){
    if (!state.open) return;
    state.open = false;
    setHidden(root, true);
    lockScroll(false);
    setHidden(cityMenu, true);
    document.removeEventListener('keydown', onKey);
  }

  function onKey(e){
    if (e.key === 'Escape'){
      if (cityMenu && !cityMenu.hasAttribute('hidden')) {
        setHidden(cityMenu, true);
        return;
      }
      close();
    }
  }

  closeEls.forEach(el => el.addEventListener('click', close));

  // Trigger: any header/nav link or button that says "Каталог", OR href #gs-catalog, OR [data-gs-open-catalog]
  function isTrigger(el){
    if (!el) return false;
    if (el.hasAttribute('data-gs-open-catalog')) return true;

    const href = (el.getAttribute('href') || '').trim();
    if (href === '#gs-catalog' || href === '#catalog') return true;

    const txt = (el.textContent || '').trim().toLowerCase();
    if (txt === 'каталог'){
      // prefer header/nav context
      const inHeader = !!el.closest('header, .gs-header, nav');
      return inHeader;
    }
    return false;
  }

  document.addEventListener('click', (e) => {
    const a = e.target.closest('a,button');
    if (!a) return;
    if (!isTrigger(a)) return;
    e.preventDefault();
    open();
  });

  // Search
  if (search){
    search.addEventListener('input', () => {
      state.q = (search.value || '').trim().toLowerCase();
      render();
    });
  }

  // City menu
  function toggleCityMenu(show){
    if (!cityMenu) return;
    setHidden(cityMenu, !show);
  }
  if (cityBtn) cityBtn.addEventListener('click', () => toggleCityMenu(cityMenu.hasAttribute('hidden')));
  if (cityClose) cityClose.addEventListener('click', () => toggleCityMenu(false));
  document.addEventListener('click', (e) => {
    if (!cityMenu || cityMenu.hasAttribute('hidden')) return;
    if (e.target.closest('[data-gs-catalog-citymenu]') || e.target.closest('[data-gs-catalog-citybtn]')) return;
    toggleCityMenu(false);
  });

  function setCity(name){
    state.city = name;
    if (cityEl) cityEl.textContent = name;
    renderCityList();
  }

  function renderCityList(){
    if (!cityList) return;
    cityList.innerHTML = '';
    (data.cities || []).forEach((c) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'gs-catalog__cityitem' + (c === state.city ? ' is-active' : '');
      b.innerHTML = `<span>${escapeHtml(c)}</span><span aria-hidden="true">${c === state.city ? '✓' : ''}</span>`;
      b.addEventListener('click', () => {
        setCity(c);
        toggleCityMenu(false);
      });
      cityList.appendChild(b);
    });
  }

  // Render categories
  function renderCats(filtered){
    if (!catsEl) return;
    catsEl.innerHTML = '';
    (filtered || data.categories || []).forEach((cat, idx) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'gs-catalog__cat' + (idx === state.activeCatIndex ? ' is-active' : '');
      btn.innerHTML = `<span>${escapeHtml(cat.title)}</span><span aria-hidden="true">→</span>`;
      btn.addEventListener('click', () => {
        state.activeCatIndex = idx;
        state.activeItemSlug = null;
        render();
      });
      catsEl.appendChild(btn);
    });
  }

  function buildDemoUrl(equipmentTitle, brand){
    const p = new URLSearchParams();
    p.set('gs_city', state.city);
    if (equipmentTitle) p.set('gs_equipment', equipmentTitle);
    if (brand) p.set('gs_brand', brand);
    return '/?' + p.toString();
  }

  function renderContent(cat){
    if (!cat) return;

    // crumbs
    if (crumbsEl){
      crumbsEl.innerHTML = `<b>${escapeHtml(cat.title)}</b>` + (state.activeItemSlug ? ` <span aria-hidden="true">/</span> <span>${escapeHtml(getActiveItem(cat)?.title || '')}</span>` : '');
    }

    // items tiles
    if (itemsEl){
      itemsEl.innerHTML = '';
      const items = cat.children || [];
      const q = state.q;

      const filteredItems = q
        ? items.filter(i => (i.title || '').toLowerCase().includes(q) || (i.brands||[]).some(b => (b||'').toLowerCase().includes(q)))
        : items;

      filteredItems.forEach(i => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'gs-catalog__tile';
        btn.innerHTML = `<span>${escapeHtml(i.title)}</span><span aria-hidden="true">→</span>`;
        btn.addEventListener('click', () => {
          state.activeItemSlug = i.slug || i.title;
          render();
        });
        itemsEl.appendChild(btn);
      });

      if (!filteredItems.length){
        itemsEl.innerHTML = `<div style="color:rgba(255,255,255,.62);padding:10px 2px;">Ничего не найдено</div>`;
      }
    }

    // brands
    if (brandsEl){
      brandsEl.innerHTML = '';
      const activeItem = getActiveItem(cat);
      const brands = activeItem ? (activeItem.brands || []) : collectBrands(cat);

      const q = state.q;
      const filteredBrands = q ? brands.filter(b => (b||'').toLowerCase().includes(q)) : brands;

      filteredBrands.slice(0, 18).forEach((b, idx) => {
        const chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'gs-catalog__brandchip' + (idx === 0 ? ' is-accent' : '');
        chip.textContent = b;
        chip.addEventListener('click', () => {
          const url = buildDemoUrl(activeItem ? activeItem.title : (cat.children?.[0]?.title || ''), b);
          close();
          window.location.href = url;
        });
        brandsEl.appendChild(chip);
      });

      // Also allow quick open by equipment only
      if (activeItem){
        const chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'gs-catalog__brandchip';
        chip.textContent = 'Без бренда';
        chip.addEventListener('click', () => {
          const url = buildDemoUrl(activeItem.title, '');
          close();
          window.location.href = url;
        });
        brandsEl.appendChild(chip);
      }

      if (!filteredBrands.length){
        brandsEl.innerHTML = `<div style="color:rgba(255,255,255,.62);padding:10px 2px;">Нет брендов для отображения</div>`;
      }
    }
  }

  function getActiveItem(cat){
    if (!state.activeItemSlug) return null;
    return (cat.children || []).find(i => (i.slug || i.title) === state.activeItemSlug) || null;
  }

  function collectBrands(cat){
    const set = new Set();
    (cat.children || []).forEach(i => (i.brands || []).forEach(b => set.add(b)));
    return Array.from(set);
  }

  function escapeHtml(s){
    return String(s || '').replace(/[&<>"']/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }

  function filterCategoriesByQuery(){
    const q = state.q;
    if (!q) return data.categories || [];

    const out = [];
    (data.categories || []).forEach(cat => {
      const titleHit = (cat.title || '').toLowerCase().includes(q);
      const children = (cat.children || []).filter(i => {
        const hit = (i.title || '').toLowerCase().includes(q) || (i.brands || []).some(b => (b||'').toLowerCase().includes(q));
        return hit;
      });
      if (titleHit || children.length){
        out.push(Object.assign({}, cat, { children: children.length ? children : (cat.children || []) }));
      }
    });
    return out;
  }

  function render(){
    if (cityEl) cityEl.textContent = state.city;
    renderCityList();

    const filteredCats = filterCategoriesByQuery();
    // Ensure active index in range
    if (state.activeCatIndex >= filteredCats.length) state.activeCatIndex = 0;

    renderCats(filteredCats);

    const activeCat = filteredCats[state.activeCatIndex] || filteredCats[0] || null;
    renderContent(activeCat);
  }

  // start closed
  setHidden(root, true);
  setCity(state.city);
})();
