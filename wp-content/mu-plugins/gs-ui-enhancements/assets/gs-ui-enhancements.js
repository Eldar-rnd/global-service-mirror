(function(){
  'use strict';

  const GS_UIE_VER = '0.5.2';

  const ctx = window.GS_CTX || {};
  const city = (ctx.city || '').trim();

  function isMobile(){
    return window.matchMedia && window.matchMedia('(max-width: 980px)').matches;
  }

  function scrollToQuickForm(){
    const el = document.getElementById('gs-quickform');
    if (el){
      el.scrollIntoView({behavior:'smooth', block:'start'});
      return true;
    }
    return false;
  }

  function ensureMobileBar(){
    if (!isMobile()) return;
    if (document.querySelector('.gs-mbar')) return;

    const bar = document.createElement('div');
    bar.className = 'gs-mbar';
    bar.innerHTML = `
      <div class="gs-mbar__row">
        <button type="button" class="gs-mbar__btn gs-mbar__btn--accent" data-gs-mbar="catalog">Каталог</button>
        <button type="button" class="gs-mbar__btn" data-gs-mbar="search">Поиск</button>
        <button type="button" class="gs-mbar__btn" data-gs-mbar="contacts">Контакты</button>
        <button type="button" class="gs-mbar__btn" data-gs-mbar="request">Заявка</button>
      </div>
    `;
    document.body.appendChild(bar);
    document.body.classList.add('gs-has-mbar');

    bar.addEventListener('click', (e) => {
      const btn = e.target.closest('button[data-gs-mbar]');
      if (!btn) return;
      const a = btn.getAttribute('data-gs-mbar');

      if (a === 'catalog') { openCatalog(); return; }
      if (a === 'search'){
        const open = window.GS_SEARCH_OPEN || null;
        if (typeof open === 'function') { open(); return; }
        openSearch(); return;
      }
      if (a === 'contacts'){ window.location.href = addCtxToUrl('/contacts/'); return; }

      if (a === 'request'){
        if (scrollToQuickForm()) return;
        try {
          sessionStorage.setItem('gs_scroll_quickform', '1');
        } catch(_){}
        window.location.href = addCtxToUrl('/contacts/');
      }
    });
  }

  // On contacts page: if requested, scroll to quickform
  function normPath(p){
  if (!p) return '/';
  try{
    // strip query/hash
    p = p.split('?')[0].split('#')[0];
  }catch(_){}
  if (!p.startsWith('/')) p = '/' + p;
  if (p !== '/' && !p.endsWith('/')) p += '/';
  return p;
}

function markActiveNav(){
  const cur = normPath(window.location.pathname || '/');
  const links = Array.from(document.querySelectorAll('header a, nav a, footer a'));
  links.forEach(a => {
    const href = (a.getAttribute('href') || '').trim();
    if (!href) return;
    if (href.startsWith('#') || href.startsWith('javascript:')) return;

    // Only same-origin / relative links
    let path = '';
    try{
      const u = new URL(href, window.location.origin);
      if (u.origin !== window.location.origin) return;
      path = normPath(u.pathname || '/');
    }catch(_){ return; }

    if (path === cur){
      a.classList.add('is-active');
      a.setAttribute('aria-current','page');
    }
  });
}

function getGsContext(){
  const out = {};
  try{
    const u = new URL(window.location.href);
    ['gs_city','gs_equipment','gs_brand'].forEach(k => {
      const v = u.searchParams.get(k);
      if (v) out[k] = v;
    });
  }catch(_){}
  // fall back to cookie city if exists
  if (!out.gs_city){
    try{
      const m = document.cookie.match(/(?:^|;\s*)gs_city=([^;]+)/);
      if (m && m[1]) out.gs_city = decodeURIComponent(m[1]);
    }catch(_){}
  }
  return out;
}

function addCtxToUrl(path, opts){
  opts = opts || {};
  const ctx = getGsContext ? getGsContext() : {};
  try{
    const u = new URL(path, window.location.origin);
    const p = (u.pathname || '/').endsWith('/') ? u.pathname : (u.pathname + '/');

    if (p === '/catalog/'){
      if (ctx.gs_city && !u.searchParams.get('gs_city')) u.searchParams.set('gs_city', ctx.gs_city);
    } else {
      Object.entries(ctx).forEach(([k,v]) => {
        if (!v) return;
        if (!u.searchParams.get(k)) u.searchParams.set(k, v);
      });
    }

    return u.pathname + (u.search ? u.search : '') + (u.hash ? u.hash : '');
  }catch(_){
    return path;
  }
}

function openSearch(initialQ){
  const open = window.GS_SEARCH_OPEN || null;
  if (typeof open === 'function') { open(initialQ); return true; }
  if (initialQ){
      try{
        const u = new URL('/search/', window.location.origin);
        u.searchParams.set('q', String(initialQ));
        window.location.href = addCtxToUrl(u.pathname + u.search);
      }catch(_){ window.location.href = addCtxToUrl('/search/'); }
    } else {
      window.location.href = addCtxToUrl('/search/');
    }
  return true;
}

function openCatalog(){
  const open = window.GS_CATALOG_OPEN || window.GS_CATALOG || null;
  if (typeof open === 'function') { open(); return true; }
  window.location.href = addCtxToUrl('/catalog/');
  return true;
}

function bindQuickOpen(){
  document.addEventListener('click', (e) => {
    const s = e.target.closest('[data-gs-open-search]');
      if (s){
        e.preventDefault();
        const v = s.getAttribute('data-gs-open-search');
        openSearch(v && v !== '1' ? v : undefined);
        return;
      }
    const c = e.target.closest('[data-gs-open-catalog]');
    if (c){
      e.preventDefault();
      openCatalog();
      return;
    }
  }, {capture:true});
}


function propagateContextLinks(){
  const ctx = getGsContext();
  if (!ctx.gs_city && !ctx.gs_equipment && !ctx.gs_brand) return;

  const targets = new Set(['/contacts/','/catalog/','/services/','/filialy/','/garantiya/','/search/']);
  document.querySelectorAll('a[href]').forEach(a => {
    const href = (a.getAttribute('href') || '').trim();
    if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

    let u;
    try{
      u = new URL(href, window.location.origin);
    }catch(_){ return; }

    if (u.origin !== window.location.origin) return;

    const p = (u.pathname || '/').endsWith('/') ? u.pathname : (u.pathname + '/');
    if (!targets.has(p)) return;

    // Do not pollute canonical-ish pages: for /catalog/ only pass city
    if (p === '/catalog/'){
      if (ctx.gs_city && !u.searchParams.get('gs_city')) u.searchParams.set('gs_city', ctx.gs_city);
    } else {
      Object.entries(ctx).forEach(([k,v]) => {
        if (!u.searchParams.get(k) && v) u.searchParams.set(k, v);
      });
    }

    a.setAttribute('href', u.pathname + (u.search ? u.search : '') + (u.hash ? u.hash : ''));
  });
}

function updateCityLabels(){
  const city = (window.GS_CTX && window.GS_CTX.city) ? String(window.GS_CTX.city).trim() : '';
  if (!city) return;
  document.querySelectorAll('[data-gs-city-label]').forEach(el => {
    el.textContent = city;
  });
}

function filterBranches(){
  let city = '';
  try{
    const ctx = (typeof getGsContext === 'function') ? getGsContext() : {};
    city = (ctx.gs_city || '').trim();
  }catch(_){}
  const cards = Array.from(document.querySelectorAll('[data-gs-branch]'));
  if (!cards.length) return;

  // If no city chosen yet — show all and hide "empty" state
  const empty = document.querySelector('[data-gs-branches-empty]');
  if (!city){
    cards.forEach(c => c.removeAttribute('hidden'));
    if (empty) empty.setAttribute('hidden','');
    return;
  }

  let shown = 0;
  cards.forEach(c => {
    const cCity = (c.getAttribute('data-gs-branch-city') || '').trim();
    const ok = (cCity === '' || cCity.toLowerCase() === city.toLowerCase());
    if (ok){
      c.removeAttribute('hidden');
      shown++;
    } else {
      c.setAttribute('hidden','');
    }
  });

  if (empty){
    if (shown === 0) empty.removeAttribute('hidden');
    else empty.setAttribute('hidden','');
  }
}

function initAccordions(){
  const accs = Array.from(document.querySelectorAll('[data-gs-accordion]'));
  accs.forEach(root => {
    if (root.dataset.gsAccInit === '1') return;
    root.dataset.gsAccInit = '1';

    root.querySelectorAll('[data-gs-acc-item]').forEach(item => {
      const btn = item.querySelector('[data-gs-acc-btn]');
      const panel = item.querySelector('[data-gs-acc-panel]');
      if (!btn || !panel) return;

      // A11y wiring
      const pid = panel.id || ('gs-acc-' + Math.random().toString(36).slice(2));
      panel.id = pid;
      btn.setAttribute('aria-controls', pid);

      const open = item.classList.contains('is-open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      panel.hidden = !open;

      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const isOpen = item.classList.contains('is-open');
        // close others if accordion is single
        const single = root.getAttribute('data-gs-accordion') === 'single';
        if (single && !isOpen){
          root.querySelectorAll('[data-gs-acc-item].is-open').forEach(other => {
            if (other === item) return;
            other.classList.remove('is-open');
            const ob = other.querySelector('[data-gs-acc-btn]');
            const op = other.querySelector('[data-gs-acc-panel]');
            if (ob) ob.setAttribute('aria-expanded', 'false');
            if (op) op.hidden = true;
          });
        }

        item.classList.toggle('is-open', !isOpen);
        btn.setAttribute('aria-expanded', (!isOpen) ? 'true' : 'false');
        panel.hidden = isOpen;
      });

      // Keyboard support: Enter/Space already clicks; add ArrowUp/Down navigation
      btn.addEventListener('keydown', (e) => {
        if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') return;
        const btns = Array.from(root.querySelectorAll('[data-gs-acc-btn]'));
        const i = btns.indexOf(btn);
        if (i < 0) return;
        e.preventDefault();
        const ni = e.key === 'ArrowDown' ? Math.min(i+1, btns.length-1) : Math.max(i-1, 0);
        btns[ni].focus();
      });
    });
  });
}

function setCookieCity(city){
  try{
    const enc = encodeURIComponent(String(city||'').trim());
    if (enc){
      document.cookie = 'gs_city=' + enc + ';path=/;max-age=' + (60*60*24*30) + ';samesite=lax' + (location.protocol === 'https:' ? ';secure' : '');
    }
  }catch(_){}
}

function syncCtxFromUrl(){
  try{
    const u = new URL(window.location.href);
    const city = (u.searchParams.get('gs_city') || '').trim();
    if (city){
      setCookieCity(city);
      window.GS_CTX = window.GS_CTX || {};
      window.GS_CTX.city = city;
      try{ window.dispatchEvent(new CustomEvent('gs:cityChange', {detail:{city: city}})); }catch(_){}
    }
  }catch(_){}
}

document.addEventListener('DOMContentLoaded', () => {
    propagateContextLinks();
    updateCityLabels();
    try{ syncCityControls(); }catch(_){}
    filterBranches();
    filterBranches();
    window.addEventListener('gs:cityChange', () => { try{ updateCityLabels(); }catch(_){} });
    syncCtxFromUrl();
    initAccordions();
    bindQuickOpen();
    markActiveNav();
    try{
      if (sessionStorage.getItem('gs_scroll_quickform') === '1'){
        sessionStorage.removeItem('gs_scroll_quickform');
        setTimeout(() => scrollToQuickForm(), 120);
      }
    } catch(_){}
    ensureMobileBar();
  });

  window.addEventListener('resize', () => {
    const bar = document.querySelector('.gs-mbar');
    if (isMobile()){
      ensureMobileBar();
    } else {
      if (bar) bar.remove();
      document.body.classList.remove('gs-has-mbar');
    }
  });


// gs_shortcut_slash: open Smart Search with "/" from anywhere (except when typing)
document.addEventListener('keydown', (e) => {
  try{
    if (e.defaultPrevented) return;
    if (e.ctrlKey || e.metaKey || e.altKey) return;
    if (e.key !== '/') return;

    const t = e.target;
    const tag = (t && t.tagName) ? t.tagName.toLowerCase() : '';
    const typing = (tag === 'input' || tag === 'textarea' || tag === 'select' || (t && t.isContentEditable));
    if (typing) return;

    // Don't steal focus if Smart Search already open
    const ss = document.getElementById('gs-ss');
    if (ss && ss.getAttribute('aria-hidden') === 'false') return;

    e.preventDefault();
    openSearch();
  }catch(_){}
});


function getKnownCities(){
  // Prefer live catalog data (backend/front demo)
  try{
    const d = window.GS_CATALOG_DATA || window.GS_CATALOG || null;
    if (d && d.cities && typeof d.cities === 'object'){
      const keys = Object.keys(d.cities);
      if (keys && keys.length) return keys;
    }
  }catch(_){}
  // Safe fallback
  return ['Ростов-на-Дону','Краснодар','Воронеж','Москва','Санкт-Петербург'];
}

function syncCityControls(){
  const city = (window.GS_CTX && window.GS_CTX.city) ? String(window.GS_CTX.city).trim() : '';
  if (!city) return;

  const known = getKnownCities();
  // Small safety: don't touch unknown city names if we don't have a list
  if (!Array.isArray(known) || known.length < 1) return;

  // Scope: headers/navs + common "top" containers. Avoid scanning entire DOM.
  const scopes = Array.from(document.querySelectorAll('header, nav, [role="navigation"], .gs-topbar, .gs-hero, .gs-page-head'));
  if (!scopes.length) return;

  const isKnown = (t) => known.includes(t);

  scopes.forEach(root => {
    // 1) any dedicated labels
    root.querySelectorAll('[data-gs-city-label]').forEach(el => { el.textContent = city; });

    // 2) any controls that open the catalog (often show "+ CITY")
    root.querySelectorAll('[data-gs-open-catalog]').forEach(el => {
      const txtRaw = (el.textContent || '').trim();
      if (!txtRaw) return;

      let hasPlus = false;
      let txt = txtRaw;

      if (txt.startsWith('+')){
        hasPlus = true;
        txt = txt.replace(/^\+\s*/, '');
      }
      // Replace only if the visible text is a known city (prevents accidental edits)
      if (isKnown(txt)){
        el.textContent = (hasPlus ? '+ ' : '') + city;
      }
    });

    // 3) fallback: replace exact city names in small chips/buttons within scopes
    root.querySelectorAll('a,button,span').forEach(el => {
      if (el.hasAttribute('data-gs-city-label')) return;
      if (el.hasAttribute('data-gs-open-catalog')) return;

      const txtRaw = (el.textContent || '').trim();
      if (!txtRaw) return;
      if (txtRaw.length > 40) return; // safety

      let hasPlus = false;
      let txt = txtRaw;
      if (txt.startsWith('+')){
        hasPlus = true;
        txt = txt.replace(/^\+\s*/, '');
      }
      if (isKnown(txt)){
        el.textContent = (hasPlus ? '+ ' : '') + city;
      }
    });
  });
}

function ensureMainAnchor(){
  // For skip-link / a11y: ensure #gsMain exists
  if (document.getElementById('gsMain')) return;
  const main = document.querySelector('main') || document.getElementById('primary') || document.querySelector('.site-main');
  if (main){
    main.id = 'gsMain';
  } else {
    // fallback: first major section
    const sec = document.querySelector('[role="main"]') || document.querySelector('body > .wp-site-blocks') || document.body;
    if (sec && sec !== document.body) sec.id = 'gsMain';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  attachPhoneMasks();
  attachDemoSubmits();
  ensureMainAnchor();
  syncCityControls();
  window.addEventListener('gs:cityChange', () => { try{ syncCityControls(); }catch(_){} });
});

})();


// --- GS UI Enhancements: phone mask + demo submit toast + header city sync ---
(function(){
  'use strict';

  function digits(s){ return String(s||'').replace(/\D+/g,''); }

  function formatRUPhone(raw){
    let d = digits(raw);
    if (!d) return '';
    if (d[0] === '8') d = '7' + d.slice(1);
    if (d[0] !== '7') d = '7' + d;
    d = d.slice(0, 11);

    const a = d.slice(1,4);
    const b = d.slice(4,7);
    const c = d.slice(7,9);
    const e = d.slice(9,11);

    let out = '+7';
    if (a) out += ' (' + a;
    if (a.length === 3) out += ')';
    if (b) out += ' ' + b;
    if (c) out += '-' + c;
    if (e) out += '-' + e;
    return out;
  }

  function attachPhoneMasks(){
    const inputs = Array.from(document.querySelectorAll('input[type="tel"], input[name*="phone" i], input[id*="phone" i]'));
    inputs.forEach(inp => {
      if (inp.dataset.gsMaskApplied === '1') return;
      inp.dataset.gsMaskApplied = '1';

      inp.addEventListener('input', () => {
        const v = inp.value || '';
        const f = formatRUPhone(v);
        // Avoid forcing +7 on empty value while user deletes
        if (!digits(v)) { inp.value = ''; return; }
        inp.value = f;
      });

      inp.addEventListener('blur', () => {
        const v = inp.value || '';
        if (!digits(v)) { inp.value = ''; return; }
        inp.value = formatRUPhone(v);
      });
    });
  }

  function ensureToastHost(){
    if (document.querySelector('.gs-toast-host')) return;
    const host = document.createElement('div');
    host.className = 'gs-toast-host';
    host.setAttribute('role','status');
    host.setAttribute('aria-live','polite');
    host.setAttribute('aria-atomic','true');
    document.body.appendChild(host);
  }

  function toast(msg){
    ensureToastHost();
    const host = document.querySelector('.gs-toast-host');
    const t = document.createElement('div');
    t.className = 'gs-toast';
    t.textContent = msg;
    host.appendChild(t);
    requestAnimationFrame(() => t.classList.add('is-in'));
    setTimeout(() => t.classList.remove('is-in'), 2400);
    setTimeout(() => t.remove(), 2850);
  }

  // Expose for other UI components
  window.GS_TOAST = toast;

  function attachDemoSubmits(){
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-gs-demo-submit]');
    if (!btn) return;
    e.preventDefault();
    try{
      const form = btn.closest('form');
      const tel = form ? form.querySelector('input[type="tel"], input[name*="phone" i], input[id*="phone" i]') : null;
      const v = tel ? (tel.value || '').replace(/\D+/g,'') : '';
      if (tel && (!v || v.length < 10)){
        toast('Введите номер телефона — и мы подключим отправку на backend-этапе.');
        tel.focus();
        return;
      }
    }catch(_){ }
    toast('Демо-режим: отправка будет подключена на backend-этапе.');
  }, {capture:true});
}

function syncHeaderCity(){
    const city = (window.GS_CTX && window.GS_CTX.city) ? String(window.GS_CTX.city).trim() : '';
    if (!city) return;
    // Only update text nodes inside header that match known cities, to avoid accidental replacements.
    const header = document.querySelector('header');
    if (!header) return;
    const known = ['Ростов-на-Дону','Краснодар','Воронеж'];
    if (!known.includes(city)) return;

    // Replace any occurrence of known city in header controls
    known.forEach(k => {
      if (k === city) return;
      header.querySelectorAll('a,button,span,div').forEach(el => {
        const txt = (el.textContent || '').trim();
        if (txt === k) el.textContent = city;
      });
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    attachPhoneMasks();
    attachDemoSubmits();
    syncHeaderCity();
  });


function getKnownCities(){
  // Prefer live catalog data (backend/front demo)
  try{
    const d = window.GS_CATALOG_DATA || window.GS_CATALOG || null;
    if (d && d.cities && typeof d.cities === 'object'){
      const keys = Object.keys(d.cities);
      if (keys && keys.length) return keys;
    }
  }catch(_){}
  // Safe fallback
  return ['Ростов-на-Дону','Краснодар','Воронеж','Москва','Санкт-Петербург'];
}

function syncCityControls(){
  const city = (window.GS_CTX && window.GS_CTX.city) ? String(window.GS_CTX.city).trim() : '';
  if (!city) return;

  const known = getKnownCities();
  // Small safety: don't touch unknown city names if we don't have a list
  if (!Array.isArray(known) || known.length < 1) return;

  // Scope: headers/navs + common "top" containers. Avoid scanning entire DOM.
  const scopes = Array.from(document.querySelectorAll('header, nav, [role="navigation"], .gs-topbar, .gs-hero, .gs-page-head'));
  if (!scopes.length) return;

  const isKnown = (t) => known.includes(t);

  scopes.forEach(root => {
    // 1) any dedicated labels
    root.querySelectorAll('[data-gs-city-label]').forEach(el => { el.textContent = city; });

    // 2) any controls that open the catalog (often show "+ CITY")
    root.querySelectorAll('[data-gs-open-catalog]').forEach(el => {
      const txtRaw = (el.textContent || '').trim();
      if (!txtRaw) return;

      let hasPlus = false;
      let txt = txtRaw;

      if (txt.startsWith('+')){
        hasPlus = true;
        txt = txt.replace(/^\+\s*/, '');
      }
      // Replace only if the visible text is a known city (prevents accidental edits)
      if (isKnown(txt)){
        el.textContent = (hasPlus ? '+ ' : '') + city;
      }
    });

    // 3) fallback: replace exact city names in small chips/buttons within scopes
    root.querySelectorAll('a,button,span').forEach(el => {
      if (el.hasAttribute('data-gs-city-label')) return;
      if (el.hasAttribute('data-gs-open-catalog')) return;

      const txtRaw = (el.textContent || '').trim();
      if (!txtRaw) return;
      if (txtRaw.length > 40) return; // safety

      let hasPlus = false;
      let txt = txtRaw;
      if (txt.startsWith('+')){
        hasPlus = true;
        txt = txt.replace(/^\+\s*/, '');
      }
      if (isKnown(txt)){
        el.textContent = (hasPlus ? '+ ' : '') + city;
      }
    });
  });
}

function ensureMainAnchor(){
  // For skip-link / a11y: ensure #gsMain exists
  if (document.getElementById('gsMain')) return;
  const main = document.querySelector('main') || document.getElementById('primary') || document.querySelector('.site-main');
  if (main){
    main.id = 'gsMain';
  } else {
    // fallback: first major section
    const sec = document.querySelector('[role="main"]') || document.querySelector('body > .wp-site-blocks') || document.body;
    if (sec && sec !== document.body) sec.id = 'gsMain';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  attachPhoneMasks();
  attachDemoSubmits();
  ensureMainAnchor();
  syncCityControls();
  window.addEventListener('gs:cityChange', () => { try{ syncCityControls(); }catch(_){} });
});

})();
