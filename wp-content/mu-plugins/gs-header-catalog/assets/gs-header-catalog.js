(function(){
  'use strict';

  const cfg = window.GS_HEADER_CATALOG || { catalogUrl: '/catalog/', label: 'Каталог' };
  const catalogUrl = cfg.catalogUrl || '/catalog/';

  const normalize = (s) => String(s || '')
    .replace(/\s+/g, ' ')
    .trim()
    .toLowerCase();

  // Find header/nav candidates
  const roots = [
    document.querySelector('header'),
    document.querySelector('.site-header'),
    document.querySelector('#masthead'),
    document.querySelector('.gs-header'),
    document.querySelector('nav')
  ].filter(Boolean);

  const seen = new Set();

  function ensureButton(anchor){
    const parent = anchor.parentElement;
    if (!parent) return;

    // Avoid duplicates
    if (parent.querySelector('.gs-hc-open')) return;

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'gs-hc-open';
    btn.setAttribute('data-gs-open-catalog','');
    btn.setAttribute('aria-label','Открыть каталог (оверлей)');
    btn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h7v7H4V6zm9 0h7v7h-7V6zM4 15h7v7H4v-7zm9 0h7v7h-7v-7z"/></svg>';

    // Insert right after the anchor
    if (anchor.nextSibling) parent.insertBefore(btn, anchor.nextSibling);
    else parent.appendChild(btn);
  }

  function processRoot(root){
    const nodes = root.querySelectorAll('a,button');
    nodes.forEach((el) => {
      if (seen.has(el)) return;

      const t = normalize(el.textContent);
      if (t !== 'каталог') return;

      // Prefer anchors for navigation
      if (el.tagName === 'A') {
        try {
          el.setAttribute('href', catalogUrl);
          // Do NOT mark it as overlay trigger
          el.removeAttribute('data-gs-open-catalog');
        } catch(e){}
        ensureButton(el);
      } else {
        // If it's a button labeled "Каталог" (rare): do nothing to avoid breaking menus.
      }

      seen.add(el);
    });
  }

  // Run once and also after SPA-like updates
  roots.forEach(processRoot);

  const obs = new MutationObserver(() => roots.forEach(processRoot));
  obs.observe(document.documentElement, { childList:true, subtree:true });
})();