(() => {
  /**
   * /shop only: deterministically groups Woo Store API products for the React grid.
   * Fail-open: if anything unexpected happens, return the original API response.
   */
  const API_RE = /\/wp-json\/wc\/store\/v1\/products(\?|$)/;

  function getCategorySlugs(p) {
    const cats = (p && p.categories) || [];
    const slugs = [];
    for (const c of cats) if (c && c.slug) slugs.push(String(c.slug));
    return slugs;
  }

  function isBundle(p) {
    const slug = String(p?.slug || '').toLowerCase();
    const name = String(p?.name || '').toLowerCase();
    const cats = getCategorySlugs(p).map((s) => s.toLowerCase());
    return slug.includes('bundle') || name.includes('bundle') || cats.includes('bundle') || cats.includes('bundles');
  }

  function rank(p) {
    const cats = new Set(getCategorySlugs(p).map((s) => s.toLowerCase()));

    // 1) Oils & tinctures, excluding bundles
    if (cats.has('full-spectrum-cbd-oil') && !isBundle(p)) return 1;

    // 2) Sweets
    if (cats.has('cbd-sweets')) return 2;

    // 3) Functional mushrooms
    if (cats.has('functional-mushrooms')) return 3;

    // 4) Tippens
    if (cats.has('joe-tippens-protocol-products')) return 4;

    // 5) Everything else (includes dogs + bundles)
    return 999;
  }

  async function sortResponse(resp) {
    // Clone so the caller can still read the body
    const clone = resp.clone();
    const contentType = (clone.headers.get('content-type') || '').toLowerCase();
    if (!contentType.includes('application/json')) return resp;

    const data = await clone.json();
    if (!Array.isArray(data)) return resp;

    const sorted = data
      .map((p, idx) => ({ p, idx, r: rank(p) }))
      .sort((a, b) => (a.r !== b.r ? a.r - b.r : a.idx - b.idx))
      .map((x) => x.p);

    // Rebuild response with the same headers/status
    return new Response(JSON.stringify(sorted), {
      status: resp.status,
      statusText: resp.statusText,
      headers: resp.headers,
    });
  }

  function shouldRun() {
    const path = (location.pathname || '').replace(/\/+$/, '');
    return path === '/shop';
  }

  const _fetch = window.fetch;
  window.fetch = async function(input, init) {
    try {
      if (!shouldRun()) return _fetch(input, init);

      const url = typeof input === 'string' ? input : (input && input.url) ? input.url : '';
      if (!API_RE.test(url)) return _fetch(input, init);

      const resp = await _fetch(input, init);
      try {
        return await sortResponse(resp);
      } catch {
        return resp;
      }
    } catch {
      return _fetch(input, init);
    }
  };
})();