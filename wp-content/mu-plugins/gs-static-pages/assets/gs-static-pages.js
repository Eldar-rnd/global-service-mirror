(function(){
  'use strict';

  const qs  = (s,r=document)=>r.querySelector(s);
  const qsa = (s,r=document)=>Array.from(r.querySelectorAll(s));

  const cfg = window.GS_STATIC_PAGES || {};
  const routes = cfg.routes || null;

  function addCtxToUrl(url){
    try{
      const u = new URL(url, window.location.origin);
      const cur = new URL(window.location.href);
      ['gs_city','gs_equipment','gs_brand'].forEach(k=>{
        const v = cur.searchParams.get(k);
        if (v) u.searchParams.set(k, v);
      });
      return u.pathname + u.search + (u.hash || '');
    }catch(_){
      return url;
    }
  }

  function openSearch(q){
    const v = (q || '').toString().trim();
    const fn = window.GS_SEARCH_OPEN || null;
    if (typeof fn === 'function'){
      fn(v || undefined);
      return true;
    }
    const u = new URL('/search/', window.location.origin);
    if (v) u.searchParams.set('q', v);
    window.location.href = addCtxToUrl(u.pathname + u.search);
    return true;
  }

  function openCatalog(){
    const fn = window.GS_CATALOG_OPEN || null;
    if (typeof fn === 'function'){
      fn();
      return true;
    }
    window.location.href = addCtxToUrl('/catalog/');
    return true;
  }

  function bindSmartLinks(){
    // rewrite plain anchors if they are placeholders
    if (!routes) return;
    qsa('a').forEach(a => {
      const href = a.getAttribute('href') || '';
      if (!href || href === '#' || href === 'javascript:void(0)' || href === 'javascript:;' ){
        const t = (a.textContent || '').trim();
        if (routes[t]) a.setAttribute('href', routes[t]);
      }
    });
  }

  function bindDataOpeners(){
    // Ensure these work even if other scripts change
    qsa('[data-gs-open-catalog]').forEach(el=>{
      el.addEventListener('click', (e)=>{ e.preventDefault(); openCatalog(); });
    });
    qsa('[data-gs-open-search]').forEach(el=>{
      el.addEventListener('click', (e)=>{
        e.preventDefault();
        const v = el.getAttribute('data-gs-open-search');
        openSearch((v && v !== '1') ? v : '');
      });
    });
  }

  function once(key){
    try{
      if (sessionStorage.getItem(key)) return false;
      sessionStorage.setItem(key, '1');
      return true;
    }catch(_){ return true; }
  }

  function autoOpen(){
    if (cfg.autoOpen === false) return;

    const path = (window.location.pathname || '/');
    const u = new URL(window.location.href);

    if (path === '/catalog/' && typeof window.GS_CATALOG_OPEN === 'function'){
      if (once('gs_auto_open_catalog')) openCatalog();
    }

    if (path === '/search/'){
      const q = (u.searchParams.get('q') || '').trim();
      if (q && typeof window.GS_SEARCH_OPEN === 'function'){
        if (once('gs_auto_open_search')) openSearch(q);
      }
    }
  }

  function bindSearchPage(){
    const path = (window.location.pathname || '/');
    if (path !== '/search/') return;

    const input = qs('[data-gs-search-input]');
    const btn = qs('[data-gs-search-go]');

    // Prefill from ?q=
    try{
      const u = new URL(window.location.href);
      const q = (u.searchParams.get('q') || '').trim();
      if (q && input) input.value = q;
    }catch(_){}

    if (input){
      input.addEventListener('keydown', (e)=>{
        if (e.key === 'Enter'){
          e.preventDefault();
          openSearch(input.value || '');
        }
      });
    }
    if (btn){
      btn.addEventListener('click', (e)=>{
        e.preventDefault();
        openSearch(input ? (input.value || '') : '');
      });
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    bindSmartLinks();
    bindDataOpeners();
    bindSearchPage();
    autoOpen();
  }, {once:true});
})();