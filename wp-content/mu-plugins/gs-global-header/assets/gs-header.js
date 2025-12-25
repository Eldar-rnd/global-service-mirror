(function(){
  'use strict';

  const GS_HDR_VER = '0.5.4';

  const qs = (s,r=document)=>r.querySelector(s);
  const qsa = (s,r=document)=>Array.from(r.querySelectorAll(s));

  const drawer = qs('[data-gs-hdr-drawer]');
  const openBtn = qs('[data-gs-hdr-open]');
  const closeEls = qsa('[data-gs-hdr-close]');
  if (!drawer || !openBtn) return;

  const panel = qs('.gs-drawer__panel', drawer);
  const header = qs('[data-gs-hdr]');
  let lastActive = null;

function onScroll(){
  if (!header) return;
  const sc = (window.scrollY || document.documentElement.scrollTop || 0);
  header.classList.toggle('is-scrolled', sc > 8);
}
window.addEventListener('scroll', onScroll, {passive:true});
onScroll();

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

  function lockScroll(on){
    document.documentElement.classList.toggle('gs-uic-lock', on);
    document.body.classList.toggle('gs-uic-lock', on);
  }

  function getFocusable(){
    if (!panel) return [];
    const sel = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
    return Array.from(panel.querySelectorAll(sel)).filter(el => el.offsetParent !== null);
  }

  function trapTab(e){
    if (drawer.hasAttribute('hidden')) return;
    if (e.key !== 'Tab') return;
    const f = getFocusable();
    if (!f.length) return;
    const first = f[0];
    const last = f[f.length - 1];
    const active = document.activeElement;

    if (e.shiftKey){
      if (active === first || !panel.contains(active)){
        e.preventDefault();
        last.focus();
      }
    } else {
      if (active === last){
        e.preventDefault();
        first.focus();
      }
    }
  }

  function open(){
    if (!drawer.hasAttribute('hidden')) return;
    lastActive = document.activeElement;
    setHidden(drawer, false);
    lockScroll(true);
    openBtn.setAttribute('aria-expanded','true');
    setTimeout(()=>{ try{ (getFocusable()[0] || panel).focus(); }catch(_){ } }, 0);
  }

  function close(){
    if (drawer.hasAttribute('hidden')) return;
    setHidden(drawer, true);
    lockScroll(false);
    openBtn.setAttribute('aria-expanded','false');
    try{ lastActive && lastActive.focus && lastActive.focus(); }catch(_){}
    lastActive = null;
  }

  openBtn.addEventListener('click', (e)=>{ e.preventDefault(); open(); });
  closeEls.forEach(el=>el.addEventListener('click', (e)=>{ e.preventDefault(); close(); }));

  drawer.addEventListener('click', (e)=>{
    if (e.target === drawer) close();
  });
  const backdrop = qs('.gs-drawer__backdrop', drawer);
  if (backdrop){
    backdrop.addEventListener('click', (e)=>{ e.preventDefault(); close(); });
  }

  // Close on nav link click
  qsa('a', drawer).forEach(a=>{
    a.addEventListener('click', ()=>{ close(); });
  });

function captureOverlayClose(e){
  // If user clicks elements that open overlays, close drawer first to avoid double-lock.
  const t = e.target && (e.target.closest ? e.target.closest('[data-gs-open-search], [data-gs-open-catalog]') : null);
  if (t && !drawer.hasAttribute('hidden')){
    close();
  }
}
drawer.addEventListener('click', captureOverlayClose, true);

  document.addEventListener('keydown', (e)=>{
    if (drawer.hasAttribute('hidden')) return;
    if (e.key === 'Escape'){ e.preventDefault(); close(); return; }
    trapTab(e);
  });

  // Active link highlighting in desktop nav
  try{
    const path = (window.location.pathname || '/').replace(/\/+$/,'/') || '/';
    qsa('.gs-hdr__link, .gs-drawer__link').forEach(a=>{
      try{
        const u = new URL(a.getAttribute('href') || '', window.location.origin);
        const p = (u.pathname || '/').replace(/\/+$/,'/') || '/';
        if (p === path){
          a.setAttribute('aria-current','page');
        }
      }catch(_){}
    });
  }catch(_){}
})();