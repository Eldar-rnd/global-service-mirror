/* GS Header Rebuild v0.6.3 */
(function(){
  'use strict';

  const $ = (sel, root=document) => root.querySelector(sel);

  function setCookie(name, value, days=30){
    const maxAge = days*24*60*60;
    const enc = encodeURIComponent(value);
    document.cookie = `${name}=${enc}; path=/; max-age=${maxAge}; samesite=lax`;
  }

  function getCities(){
    // Prefer catalog data if available (MU gs-ui-catalog)
    try{
      if (window.GS_CATALOG && window.GS_CATALOG.cities && Array.isArray(window.GS_CATALOG.cities)) {
        return window.GS_CATALOG.cities;
      }
      if (window.GS_CATALOG_DATA && window.GS_CATALOG_DATA.cities && typeof window.GS_CATALOG_DATA.cities === 'object') {
        return Object.keys(window.GS_CATALOG_DATA.cities);
      }
    }catch(e){}
    return (window.GS_HDR2 && Array.isArray(window.GS_HDR2.cities)) ? window.GS_HDR2.cities : ['Ростов-на-Дону','Краснодар','Воронеж'];
  }

  function openDrawer(){
    const drawer = $('[data-gs-hdr2-drawer]');
    const burger = $('[data-gs-hdr2-burger]');
    if (!drawer || !burger) return;
    drawer.hidden = false;
    burger.setAttribute('aria-expanded','true');
    document.documentElement.classList.add('gs-hdr2-drawer-open');
  }

  function closeDrawer(){
    const drawer = $('[data-gs-hdr2-drawer]');
    const burger = $('[data-gs-hdr2-burger]');
    if (!drawer || !burger) return;
    drawer.hidden = true;
    burger.setAttribute('aria-expanded','false');
    document.documentElement.classList.remove('gs-hdr2-drawer-open');
  }

  function openCityDlg(){
    const dlg = $('[data-gs-hdr2-citydlg]');
    if (!dlg) return;
    // rebuild city list from live catalog data
    const list = $('[data-gs-hdr2-citylist]', dlg);
    if (list) {
      const cities = getCities();
      list.innerHTML = cities.map(c => `<button type="button" class="gs-hdr2__cityItem" data-gs-hdr2-city="${String(c).replace(/"/g,'&quot;')}">${c}</button>`).join('');
    }
    dlg.hidden = false;
    document.documentElement.classList.add('gs-hdr2-city-open');
  }

  function closeCityDlg(){
    const dlg = $('[data-gs-hdr2-citydlg]');
    if (!dlg) return;
    dlg.hidden = true;
    document.documentElement.classList.remove('gs-hdr2-city-open');
  }

  function applyCity(city){
    if (!city) return;
    setCookie('gs_city', city, 30);
    // Update labels immediately
    document.querySelectorAll('[data-gs-city-label]').forEach(el => el.textContent = city);
    // Notify other scripts
    try {
      window.GS_CTX = window.GS_CTX || {};
      window.GS_CTX.city = city;
      window.dispatchEvent(new CustomEvent('gs:cityChange', { detail: { city } }));
    } catch(e) {}
  }

  function attach(){
    const burger = $('[data-gs-hdr2-burger]');
    const closeBtn = $('[data-gs-hdr2-close]');
    if (burger) burger.addEventListener('click', () => {
      const drawer = $('[data-gs-hdr2-drawer]');
      if (!drawer) return;
      drawer.hidden ? openDrawer() : closeDrawer();
    });
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);

    // Close drawer when clicking a link
    document.addEventListener('click', (e) => {
      const a = e.target && e.target.closest ? e.target.closest('a') : null;
      if (a && a.classList && a.classList.contains('gs-hdr2__drawerLink')) {
        closeDrawer();
      }
    });

    // City dialog buttons (both desktop and mobile)
    document.querySelectorAll('[data-gs-hdr2-citybtn],[data-gs-hdr2-citybtn2]').forEach(btn => {
      btn.addEventListener('click', () => {
        closeDrawer();
        openCityDlg();
        // aria-expanded
        document.querySelectorAll('[data-gs-hdr2-citybtn]').forEach(b => b.setAttribute('aria-expanded','true'));
      });
    });
    const cityClose = $('[data-gs-hdr2-cityclose]');
    if (cityClose) cityClose.addEventListener('click', () => {
      closeCityDlg();
      document.querySelectorAll('[data-gs-hdr2-citybtn]').forEach(b => b.setAttribute('aria-expanded','false'));
    });
    const dlg = $('[data-gs-hdr2-citydlg]');
    if (dlg) {
      dlg.addEventListener('click', (e) => {
        if (e.target === dlg) {
          closeCityDlg();
          document.querySelectorAll('[data-gs-hdr2-citybtn]').forEach(b => b.setAttribute('aria-expanded','false'));
        }
      });
      dlg.addEventListener('click', (e) => {
        const btn = e.target && e.target.closest ? e.target.closest('[data-gs-hdr2-city]') : null;
        if (!btn) return;
        applyCity(btn.getAttribute('data-gs-hdr2-city'));
        closeCityDlg();
        document.querySelectorAll('[data-gs-hdr2-citybtn]').forEach(b => b.setAttribute('aria-expanded','false'));
      });
    }

    // Esc closes
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeCityDlg();
        closeDrawer();
        document.querySelectorAll('[data-gs-hdr2-citybtn]').forEach(b => b.setAttribute('aria-expanded','false'));
      }
    });

    // Catalog open hook (if existing overlay)
    document.addEventListener('click', (e) => {
      const el = e.target && e.target.closest ? e.target.closest('[data-gs-open-catalog]') : null;
      if (!el) return;
      // If catalog overlay exists, try open it by clicking its opener or dispatching a custom event
      try {
        window.dispatchEvent(new CustomEvent('gs:catalogOpen'));
      } catch(_e) {}
    });

    // Initial city label from GS_CTX or GS_HDR2
    const city = (window.GS_CTX && window.GS_CTX.city) ? window.GS_CTX.city : ((window.GS_HDR2 && window.GS_HDR2.city) ? window.GS_HDR2.city : '');
    if (city) document.querySelectorAll('[data-gs-city-label]').forEach(el => el.textContent = city);
  }

  document.addEventListener('DOMContentLoaded', attach);
})();
