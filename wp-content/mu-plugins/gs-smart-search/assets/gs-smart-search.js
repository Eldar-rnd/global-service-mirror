(function(){
  'use strict';

  const data = window.GS_SMART_SEARCH || null;
  const $ = (s,r=document)=>r.querySelector(s);
  const $$ = (s,r=document)=>Array.from(r.querySelectorAll(s));

  const root = $('#gs-ss');
  if (!root || !data) return;

  const input = $('.gs-ss__input', root);
  const cityEl = $('.gs-ss__city', root);
  const cityBtn = $('[data-gs-ss-citybtn]', root);
  const cityMenu = $('.gs-ss__citymenu', root);
  const results = $('.gs-ss__results', root);

  let city = (data.defaultCity || 'Ростов-на-Дону');
  if (cityEl) cityEl.textContent = city;
  // keep local variable in sync
  Object.defineProperty(data, '__city', {get:()=> (data.defaultCity || city), set:(v)=>{city=v;}});

  // Build index
  const equipments = [];
  const brandToEquip = new Map();

  (data.categories || []).forEach(cat => {
    (cat.children || []).forEach(ch => {
      equipments.push({
        title: ch.title,
        slug: ch.slug,
        brands: (ch.brands || []).slice()
      });
      (ch.brands || []).forEach(b => {
        const k = String(b);
        if (!brandToEquip.has(k)) brandToEquip.set(k, []);
        brandToEquip.get(k).push(ch.title);
      });
    });
  });

  const brands = Array.from(brandToEquip.keys());

function populateCityMenu(){
  if (!cityMenu) return;
  cityMenu.innerHTML = '';
  const cities = (data.cities || []).slice();
  cities.forEach(c => {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'gs-ss__cityopt' + ((data.defaultCity || '') === c ? ' is-active' : '');
    b.textContent = c;
    b.setAttribute('role','option');
    b.setAttribute('aria-selected', ((data.defaultCity || '') === c) ? 'true' : 'false');
    b.addEventListener('click', () => {
      setCity(c);
      populateCityMenu();
      focusedChipIndex = -1;
    toggleCityMenu(false);
    render(input ? input.value : '');
      // Keep menu open for quick switching
      if (input) { input.focus(); input.select(); }
    });
    cityMenu.appendChild(b);
  });
}

function toggleCityMenu(force){
  if (!cityMenu) return;
  const want = (typeof force === 'boolean') ? force : !!cityMenu.hasAttribute('hidden');
  if (want){
    cityMenu.removeAttribute('hidden');
    populateCityMenu();
  } else {
    cityMenu.setAttribute('hidden','');
  }
}

function setCookieCity(name){
  try{
    const enc = encodeURIComponent(String(name||'').trim());
    if (enc){
      document.cookie = 'gs_city=' + enc + ';path=/;max-age=' + (60*60*24*30) + ';samesite=lax' + (location.protocol === 'https:' ? ';secure' : '');
    }
  }catch(e){}
}

function setCity(name){
  const n = String(name||'').trim();
  if (!n) return;
  // Accept only from dataset
  if ((data.cities || []).indexOf(n) === -1) return;
  data.defaultCity = n;
  if (cityEl) cityEl.textContent = n;
  setCookieCity(n);
  try{ window.dispatchEvent(new CustomEvent('gs:cityChange', {detail:{city:n}})); }catch(e){}
  // Keep shared ctx consistent if present
  try{
    if (window.GS_CTX) window.GS_CTX.city = n;
  }catch(e){}
}

let focusedChipIndex = -1;

function getChips(){
  return Array.from(root.querySelectorAll('.gs-ss__chip'));
}

function focusChip(i){
  const chips = getChips();
  if (!chips.length) return;
  i = Math.max(0, Math.min(i, chips.length - 1));
  focusedChipIndex = i;
  chips[i].focus({preventScroll:false});
}

  const HIST_KEY = 'gs_ss_history_v1';

function loadHistory(){
  try{
    const raw = localStorage.getItem(HIST_KEY);
    const arr = JSON.parse(raw || '[]');
    if (!Array.isArray(arr)) return [];
    return arr.filter(x => typeof x === 'string' && x.trim()).slice(0, 8);
  }catch(_){ return []; }
}

function saveHistory(q){
  q = String(q||'').trim();
  if (!q) return;
  try{
    const arr = loadHistory().filter(x => x.toLowerCase() !== q.toLowerCase());
    arr.unshift(q);
    localStorage.setItem(HIST_KEY, JSON.stringify(arr.slice(0, 8)));
  }catch(_){}
}

const SYN = new Map([
  // minimal Russian morphological helpers for demo (no heavy stemming)
  ['кофемашина','кофемашины'],
  ['кофемашины','кофемашина'],
  ['пылесос','пылесосы'],
  ['пылесосы','пылесос'],
  ['телефон','смартфон'],
  ['смартфон','телефон'],
]);

function expandSynonyms(q){
  const out = new Set([q]);
  const w = q.split(/\s+/).filter(Boolean);
  w.forEach(word => {
    const s = SYN.get(word);
    if (s) out.add(q.replace(word, s));
  });
  return Array.from(out);
}

function norm(s){
    return String(s||'').toLowerCase().replace(/ё/g,'е').trim();
  }

  function buildLandingUrl(eq, br){
    const u = new URL(window.location.origin + '/');
    const c = (data.defaultCity || 'Ростов-на-Дону');
    u.searchParams.set('gs_city', c);
    if (eq) u.searchParams.set('gs_equipment', eq);
    if (br) u.searchParams.set('gs_brand', br);
    return u.pathname + u.search;
  }

function containsAny(hay, needles){
  for (const n of needles){
    if (n && hay.indexOf(n) !== -1) return true;
  }
  return false;
}


  function el(tag, cls, txt){
    const e = document.createElement(tag);
    if (cls) e.className = cls;
    if (txt != null) e.textContent = txt;
    return e;
  }

  function clearResults(){
    results.innerHTML = '';
  }

  function group(title, sub){
    const g = el('div','gs-ss__group');
    const t = el('div','gs-ss__title', title);
    g.appendChild(t);
    if (sub){
      const s = el('div','gs-ss__sub', sub);
      g.appendChild(s);
    }
    const chips = el('div','gs-ss__chips');
    g.appendChild(chips);
    results.appendChild(g);
    return chips;
  }

  function chip(label, href, accent){
    const a = el('a','gs-ss__chip' + (accent ? ' gs-ss__chip--accent' : ''), label);
    a.href = href;
    a.addEventListener('click', () => { close(); });
    return a;
  }

  function render(q){
    q = norm(q);
    clearResults();

    if (!q){

const hist = loadHistory();
if (hist && hist.length){
  const h = group('История запросов', 'Последние запросы на этом устройстве');
  hist.slice(0, 8).forEach((q, idx) => {
    // use chip as "fill input"
    const a = chip(q, '#', idx === 0);
    a.addEventListener('click', (e) => {
      e.preventDefault();
      input.value = q;
      render(q);
      input.focus();
    });
    h.appendChild(a);
  });
}

      // Default suggestions
      const g1 = group('Популярное', 'Быстрый переход к популярным связкам');
      const popular = [
        ['Кофемашины','DeLonghi'],
        ['Пылесосы','Dyson'],
        ['Робот‑пылесосы','Xiaomi'],
        ['Электроинструмент','Makita'],
      ];
      popular.forEach(([eq,br], idx) => {
        g1.appendChild(chip(eq + ' · ' + br, buildLandingUrl(eq, br), idx === 0));
      });

      const g2 = group('По бренду', 'Начните вводить бренд: Samsung, Bosch…');
      brands.slice(0, 8).forEach((b, idx) => {
        // link goes to catalog (full list), brand is too broad in demo
        g2.appendChild(chip(b, '/catalog/', false));
      });
      return;
    }

    // Brand match
    const brandMatches = brands
      .filter(b => norm(b).includes(q))
      .slice(0, 4);

    brandMatches.forEach((b, i) => {
      const eqs = (brandToEquip.get(b) || []).slice(0, 10);
      const ch = group('Бренд: ' + b, 'Выберите технику для ремонта');
      eqs.forEach((eq, idx) => ch.appendChild(chip(eq, buildLandingUrl(eq, b), (i===0 && idx===0))));
    });

    // Equipment match
    const eqMatches = equipments
      .filter(e => norm(e.title).includes(q))
      .slice(0, 4);

    eqMatches.forEach((e, i) => {
      const ch = group('Техника: ' + e.title, 'Выберите бренд');
      (e.brands || []).slice(0, 10).forEach((b, idx) => ch.appendChild(chip(b, buildLandingUrl(e.title, b), (brandMatches.length===0 && i===0 && idx===0))));
    });

    if (!brandMatches.length && !eqMatches.length){
      const ch = group('Ничего не найдено', 'Попробуйте другой запрос или откройте каталог');
      ch.appendChild(chip('Открыть каталог', '/catalog/', true));
      ch.appendChild(chip('Контакты', '/contacts/', false));
    }
  }

  // Modal open/close
  let lastActive = null;

  function getFocusable(){
  return Array.from(root.querySelectorAll('button, a, input, [tabindex]:not([tabindex="-1"])'))
    .filter(el => !el.hasAttribute('disabled') && el.offsetParent !== null);
}

function trapKeydown(e){
  if (!root.classList.contains('is-open')) return;

  if (e.key === 'Tab'){
    const f = getFocusable();
    if (!f.length) return;
    const first = f[0];
    const last = f[f.length - 1];
    const active = document.activeElement;

    if (e.shiftKey){
      if (active === first || !root.contains(active)){
        e.preventDefault();
        last.focus();
      }
    } else {
      if (active === last){
        e.preventDefault();
        first.focus();
      }
    }
    return;
  }

  // Arrow navigation among result chips
  if (e.key === 'ArrowDown'){
    const chips = getChips();
    if (!chips.length) return;
    e.preventDefault();
    if (focusedChipIndex < 0) focusChip(0);
    else focusChip(focusedChipIndex + 1);
    return;
  }
  if (e.key === 'ArrowUp'){
    const chips = getChips();
    if (!chips.length) return;
    e.preventDefault();
    if (focusedChipIndex < 0) focusChip(chips.length - 1);
    else focusChip(focusedChipIndex - 1);
    return;
  }
  if (e.key === 'Enter'){
    // If a chip is focused, activate it
    const a = document.activeElement;
    if (a && a.classList && a.classList.contains('gs-ss__chip')){
      e.preventDefault();
      a.click();
    }
  }
  if (e.key === 'Escape'){
    toggleCityMenu(false);
  }
}

  function prefillFromQuery(){
  try{
    const u = new URL(window.location.href);
    const q = u.searchParams.get('q') || '';
    if (q && input){
      input.value = q;
    }
  }catch(_){}
}

  function open(initialQ){
    if (root.classList.contains('is-open')) return;
    lastActive = document.activeElement;
    root.classList.add('is-open');
    root.setAttribute('aria-hidden','false');
    document.documentElement.classList.add('gs-uic-lock'); // reuse lock class
    document.body.classList.add('gs-uic-lock');
    prefillFromQuery();
    try{ if (initialQ && input){ input.value = String(initialQ); } }catch(_){ }
    setTimeout(() => { input && input.focus(); input && input.select(); }, 0);
    focusedChipIndex = -1;
    toggleCityMenu(false);
    render(input ? input.value : '');
  }

  function close(){
    if (!root.classList.contains('is-open')) return;
    root.classList.remove('is-open');
    root.setAttribute('aria-hidden','true');
    document.documentElement.classList.remove('gs-uic-lock');
    document.body.classList.remove('gs-uic-lock');
    toggleCityMenu(false);
    try{ lastActive && lastActive.focus && lastActive.focus(); }catch(e){}
    lastActive = null;
  }

  window.GS_SEARCH_OPEN = open;
  window.GS_SEARCH_CLOSE = close;

  // Events
  if (cityBtn){
    cityBtn.addEventListener('click', () => toggleCityMenu());
  }

  root.addEventListener('click', (e) => {
    if (e.target && e.target.closest('[data-gs-ss-close]')) close();
  });

  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k'){
      e.preventDefault();
      open();
    }
    if (e.key === 'Escape') close();
  });

  if (input){
    input.addEventListener('input', () => { focusedChipIndex = -1; render(input.value); });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter'){
        const q = (input.value || '').trim();
        if (q) saveHistory(q);
      }
    });
  }

  // Any element can open
  document.addEventListener('click', (e) => {
    const t = e.target.closest('[data-gs-open-search]');
    if (!t) return;
    e.preventDefault();
    open();
  }, {capture:true});



function autoOpenOnSearchPage(){
  try{
    const p = (window.location.pathname || '/');
    const path = p.endsWith('/') ? p : (p + '/');
    const u = new URL(window.location.href);
    const q = (u.searchParams.get('q') || '').trim();
    if (path === '/search/' && q){
      // open after paint
      setTimeout(() => open(), 60);
    }
  }catch(_){}
}

autoOpenOnSearchPage();
})();
