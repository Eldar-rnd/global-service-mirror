(function(){
  'use strict';

  const data = window.GS_CATALOG_PAGE || window.GS_CATALOG || null;
  if (!data) return;

  const qs = (s,r=document)=>r.querySelector(s);
  const qsa = (s,r=document)=>Array.from(r.querySelectorAll(s));

  const elCats = qs('[data-gs-cp-cats]');
  const elCrumbs = qs('[data-gs-cp-crumbs]');
  const elItems = qs('[data-gs-cp-items]');
  const elBrands = qs('[data-gs-cp-brands]');
  const elSearch = qs('[data-gs-cp-search]');
  const elCity = qs('[data-gs-cp-city]');
  const elCityMenu = qs('[data-gs-cp-citymenu]');
  const elCityList = qs('[data-gs-cp-citylist]');
  const elCityBtn = qs('[data-gs-cp-citybtn]');

  let state = {
    catIndex: 0,
    itemSlug: null,
    city: data.defaultCity || (data.cities && data.cities[0]) || 'Ростов-на-Дону',
    q: ''
  };

  function escapeHtml(s){
    return String(s||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }

  function buildUrl(equipmentTitle, brand){
    const p = new URLSearchParams();
    p.set('gs_city', state.city);
    if (equipmentTitle) p.set('gs_equipment', equipmentTitle);
    if (brand) p.set('gs_brand', brand);
    return '/?' + p.toString();
  }

  function filterCats(){
    const q = state.q;
    if (!q) return data.categories || [];

    const out = [];
    (data.categories || []).forEach(cat => {
      const titleHit = (cat.title || '').toLowerCase().includes(q);
      const children = (cat.children || []).filter(i => {
        const hit = (i.title || '').toLowerCase().includes(q) || (i.brands||[]).some(b => (b||'').toLowerCase().includes(q));
        return hit;
      });
      if (titleHit || children.length){
        out.push(Object.assign({}, cat, { children: children.length ? children : (cat.children || []) }));
      }
    });
    return out;
  }

  function activeCat(cats){
    if (!cats.length) return null;
    if (state.catIndex >= cats.length) state.catIndex = 0;
    return cats[state.catIndex];
  }

  function getItem(cat){
    if (!cat || !state.itemSlug) return null;
    return (cat.children || []).find(i => (i.slug || i.title) === state.itemSlug) || null;
  }

  function collectBrands(cat){
    const set = new Set();
    (cat.children || []).forEach(i => (i.brands || []).forEach(b => set.add(b)));
    return Array.from(set);
  }

  function renderCity(){
    if (elCity) elCity.textContent = state.city;
    if (!elCityList) return;
    elCityList.innerHTML = '';
    (data.cities || []).forEach(c => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'gs-cp__chip' + (c === state.city ? ' gs-cp__chip--accent' : '');
      b.innerHTML = `<span>${escapeHtml(c)}</span><span aria-hidden="true">${c === state.city ? '✓' : ''}</span>`;
      b.addEventListener('click', () => {
        state.city = c;
        render();
        hideCityMenu();
      });
      elCityList.appendChild(b);
    });
  }

  function showCityMenu(){
    if (!elCityMenu) return;
    elCityMenu.removeAttribute('hidden');
    elCityMenu.setAttribute('aria-hidden','false');
  }
  function hideCityMenu(){
    if (!elCityMenu) return;
    elCityMenu.setAttribute('hidden','');
    elCityMenu.setAttribute('aria-hidden','true');
  }

  function render(){
    const cats = filterCats();

    // categories
    if (elCats){
      elCats.innerHTML = '';
      cats.forEach((cat, idx) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'gs-cp__cat' + (idx === state.catIndex ? ' is-active' : '');
        btn.innerHTML = `<span>${escapeHtml(cat.title)}</span><span aria-hidden="true">→</span>`;
        btn.addEventListener('click', () => {
          state.catIndex = idx;
          state.itemSlug = null;
          render();
        });
        elCats.appendChild(btn);
      });
    }

    const cat = activeCat(cats);
    const item = getItem(cat);

    if (elCrumbs){
      elCrumbs.innerHTML = cat ? `<b>${escapeHtml(cat.title)}</b>` + (item ? ` <span aria-hidden="true">/</span> <span>${escapeHtml(item.title)}</span>` : '') : '';
    }

    // items
    if (elItems){
      elItems.innerHTML = '';
      const items = cat ? (cat.children || []) : [];
      const q = state.q;
      const filteredItems = q ? items.filter(i => (i.title||'').toLowerCase().includes(q) || (i.brands||[]).some(b => (b||'').toLowerCase().includes(q))) : items;

      filteredItems.forEach(i => {
        const t = document.createElement('button');
        t.type = 'button';
        t.className = 'gs-cp__tile';
        t.innerHTML = `<span>${escapeHtml(i.title)}</span><span aria-hidden="true">→</span>`;
        t.addEventListener('click', () => {
          state.itemSlug = (i.slug || i.title);
          render();
        });
        elItems.appendChild(t);
      });

      if (!filteredItems.length){
        elItems.innerHTML = `<div class="gs-cp__note">Ничего не найдено</div>`;
      }
    }

    // brands
    if (elBrands){
      elBrands.innerHTML = '';
      let brands = [];
      if (item) brands = item.brands || [];
      else if (cat) brands = collectBrands(cat);

      const q = state.q;
      const filteredBrands = q ? brands.filter(b => (b||'').toLowerCase().includes(q)) : brands;

      filteredBrands.slice(0, 22).forEach((b, idx) => {
        const chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'gs-cp__brand' + (idx === 0 ? ' is-accent' : '');
        chip.textContent = b;
        chip.addEventListener('click', () => {
          const eq = item ? item.title : (cat && cat.children && cat.children[0] ? cat.children[0].title : '');
          window.location.href = buildUrl(eq, b);
        });
        elBrands.appendChild(chip);
      });

      if (item){
        const nob = document.createElement('button');
        nob.type = 'button';
        nob.className = 'gs-cp__brand';
        nob.textContent = 'Без бренда';
        nob.addEventListener('click', () => window.location.href = buildUrl(item.title, ''));
        elBrands.appendChild(nob);
      }

      if (!filteredBrands.length){
        elBrands.innerHTML = `<div class="gs-cp__note">Нет брендов для отображения</div>`;
      }
    }

    renderCity();
  }

  // Search
  if (elSearch){
    elSearch.addEventListener('input', () => {
      state.q = (elSearch.value || '').trim().toLowerCase();
      // when searching, reset selections to show results
      state.catIndex = 0;
      state.itemSlug = null;
      render();
    });
  }

  // City menu toggle
  if (elCityBtn){
    elCityBtn.addEventListener('click', () => {
      if (!elCityMenu) return;
      if (elCityMenu.hasAttribute('hidden')) showCityMenu();
      else hideCityMenu();
    });
  }
  document.addEventListener('click', (e) => {
    if (!elCityMenu || elCityMenu.hasAttribute('hidden')) return;
    if (e.target.closest('[data-gs-cp-citymenu]') || e.target.closest('[data-gs-cp-citybtn]')) return;
    hideCityMenu();
  });

  // FAQ toggles
  qsa('[data-gs-cp-q]').forEach(box => {
    const btn = qs('button', box);
    if (!btn) return;
    btn.addEventListener('click', () => box.classList.toggle('is-open'));
  });

  render();
})();
