(function(){
  'use strict';

  const $ = (s,r=document)=>r.querySelector(s);
  const $$ = (s,r=document)=>Array.from(r.querySelectorAll(s));

  const header = $('.gs-header');

  function cssPxVar(name){
    const v = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    const n = parseInt(String(v).replace('px',''), 10);
    return Number.isFinite(n) ? n : 0;
  }

  function updateHeaderH(){
    const hh = header ? header.offsetHeight : 72;
    document.documentElement.style.setProperty('--gs-header-h', hh + 'px');
  }
  updateHeaderH();
  window.addEventListener('resize', updateHeaderH);

  // Accordion
  $$('[data-gs-acc]').forEach(acc => {
    const btn = $('[data-gs-acc-btn]', acc);
    const panel = $('[data-gs-acc-panel]', acc);
    if (!btn || !panel) return;

    btn.addEventListener('click', () => {
      const isOpen = acc.classList.contains('is-open');
      const group = acc.closest('[data-gs-acc-group]');
      if (group) {
        $$('[data-gs-acc].is-open', group).forEach(x => {
          if (x === acc) return;
          x.classList.remove('is-open');
          const p = $('[data-gs-acc-panel]', x);
          if (p) p.style.maxHeight = '0px';
          const b = $('[data-gs-acc-btn]', x);
          if (b) b.setAttribute('aria-expanded', 'false');
        });
      }

      if (isOpen){
        acc.classList.remove('is-open');
        btn.setAttribute('aria-expanded', 'false');
        panel.style.maxHeight = '0px';
      } else {
        acc.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
        panel.style.maxHeight = panel.scrollHeight + 'px';
      }
    });
  });

  // Subnav smooth scroll + active section
  const subnav = $('[data-gs-subnav]');
  if (subnav){
    const links = $$('a[href^="#"]', subnav).filter(a => (a.getAttribute('href') || '').length > 1);

    function setActive(id){
      links.forEach(a => a.classList.toggle('is-active', a.getAttribute('href') === '#' + id));
    }

    function scrollToId(id){
      const target = document.getElementById(id);
      if (!target) return;

      const adminH = cssPxVar('--gs-adminbar-h');
      const headerH = header ? header.offsetHeight : cssPxVar('--gs-header-h');
      const subH = subnav.offsetHeight;
      const offset = adminH + headerH + subH + 10;

      const top = target.getBoundingClientRect().top + window.pageYOffset - offset + 2;
      window.scrollTo({ top, behavior: 'smooth' });
    }

    links.forEach(a => {
      a.addEventListener('click', (e) => {
        const href = a.getAttribute('href');
        if (!href || href === '#') return;
        e.preventDefault();
        scrollToId(href.slice(1));
      });
    });

    const sections = links.map(a => document.getElementById((a.getAttribute('href')||'').slice(1))).filter(Boolean);

    function onScroll(){
      const adminH = cssPxVar('--gs-adminbar-h');
      const headerH = header ? header.offsetHeight : cssPxVar('--gs-header-h');
      const subH = subnav.offsetHeight;
      const y = window.pageYOffset + adminH + headerH + subH + 40;

      let current = sections[0]?.id || '';
      for (const s of sections){
        const top = s.getBoundingClientRect().top + window.pageYOffset;
        if (top <= y) current = s.id;
      }
      if (current) setActive(current);
    }

    document.addEventListener('scroll', () => { window.requestAnimationFrame(onScroll); }, { passive: true });
    window.addEventListener('load', onScroll);
    onScroll();
  }

  // Fill mobile bar context
  const ctx = window.GS_LD_CTX || null;
  const el = document.querySelector('[data-gs-landingbar-ctx]');
  if (ctx && el){
    el.textContent = [ctx.equipment, ctx.brand, ctx.city].filter(Boolean).join(' · ');
  }
})();


(function(){
  'use strict';

  function prefersReduced(){
    return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  function initSubnavSpy(){
    const nav = document.querySelector('[data-gs-subnav]');
    if (!nav) return;

    const links = Array.from(nav.querySelectorAll('a[href^="#"]'));
    const ids = links.map(a => (a.getAttribute('href') || '').slice(1)).filter(Boolean);

    const sections = ids.map(id => document.getElementById(id)).filter(Boolean);
    if (!sections.length) return;

    function setActive(id){
      links.forEach(a => {
        const is = (a.getAttribute('href') === '#' + id);
        a.classList.toggle('is-active', is);
        if (is) a.setAttribute('aria-current','true'); else a.removeAttribute('aria-current');
      });
    }

    // Smooth scroll with offset (native scroll-margin handles most cases)
    links.forEach(a => {
      a.addEventListener('click', (e) => {
        const id = (a.getAttribute('href') || '').slice(1);
        const el = document.getElementById(id);
        if (!el) return;
        e.preventDefault();
        el.scrollIntoView({behavior: prefersReduced() ? 'auto' : 'smooth', block:'start'});
        history.replaceState(null, '', '#' + id);
        setActive(id);
      });
    });

    const io = new IntersectionObserver((entries) => {
      // choose the top-most visible
      const visible = entries.filter(e => e.isIntersecting).sort((a,b)=> b.intersectionRatio - a.intersectionRatio);
      if (visible[0] && visible[0].target && visible[0].target.id){
        setActive(visible[0].target.id);
      }
    }, {root:null, threshold:[0.2,0.35,0.5,0.65]});

    sections.forEach(s => io.observe(s));

    // initial
    const hash = (location.hash || '').replace('#','');
    if (hash && document.getElementById(hash)) setActive(hash);
    else setActive(sections[0].id);
  }

  document.addEventListener('DOMContentLoaded', initSubnavSpy);
})();
