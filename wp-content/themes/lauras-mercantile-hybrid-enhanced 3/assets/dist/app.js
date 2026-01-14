(function(){
  if (!window.wp || !wp.element) {
    console.error('wp.element not available. This theme requires WordPress\' wp-element script.');
    return;
  }

  const { createElement: h, useEffect, useMemo, useState } = wp.element;

  const LM = window.__LM__ || {};
  const ROOT_ID = 'lm-react-root';

  const protectedPaths = ['/cart', '/checkout', '/my-account'];

  function isProtected(pathname){
    return protectedPaths.some(p => pathname === p || pathname.startsWith(p + '/'));
  }

  function hardRedirect(to){
    window.location.assign(to);
  }

  function usePath(){
    const [path, setPath] = useState(window.location.pathname.replace(/\/+$/, '') || '/');
    useEffect(() => {
      const onPop = () => setPath(window.location.pathname.replace(/\/+$/, '') || '/');
      window.addEventListener('popstate', onPop);
      return () => window.removeEventListener('popstate', onPop);
    }, []);
    return [path, setPath];
  }

  function Link({ to, children, className, style, 'aria-label': ariaLabel }){
    return h('a', {
      href: to,
      className,
      style,
      'aria-label': ariaLabel,
      onClick: (e) => {
        // Allow cmd/ctrl click etc
        if (e.defaultPrevented) return;
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;

        const url = new URL(to, window.location.origin);
        if (url.origin !== window.location.origin) return;

        if (isProtected(url.pathname)) return; // let normal nav happen
        e.preventDefault();
        window.history.pushState({}, '', url.pathname);
        window.dispatchEvent(new PopStateEvent('popstate'));
      }
    }, children);
  }

  function IconSearch(){
    return h('svg', { width:20, height:20, viewBox:'0 0 24 24', fill:'none' },
      h('path', { d:'M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z', stroke:'currentColor', 'stroke-width':1.8 }),
      h('path', { d:'M16.5 16.5 21 21', stroke:'currentColor', 'stroke-width':1.8, 'stroke-linecap':'round' })
    );
  }
  function IconCart(){
    return h('svg', { width:20, height:20, viewBox:'0 0 24 24', fill:'none' },
      h('path', { d:'M6.5 7h15l-1.2 7.2a2 2 0 0 1-2 1.7H9a2 2 0 0 1-2-1.6L5.7 3.8A1.5 1.5 0 0 0 4.2 2.5H2.8', stroke:'currentColor', 'stroke-width':1.8, 'stroke-linecap':'round', 'stroke-linejoin':'round' }),
      h('path', { d:'M9.5 21a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4ZM18 21a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z', fill:'currentColor' })
    );
  }

  function SiteHeader(){
    const [cartCount, setCartCount] = useState(null);
    const loggedIn = !!LM.loggedIn;

    useEffect(() => {
      (async () => {
        try{
          const res = await fetch('/wp-json/wc/store/v1/cart', { credentials:'same-origin' });
          if(!res.ok) return;
          const data = await res.json();
          const count = data && typeof data.items_count === 'number' ? data.items_count : null;
          setCartCount(count);
        }catch(e){}
      })();
    }, []);

    return h(wp.element.Fragment, null,
      h('div', { className:'lm-topbar' }, 'FREE SHIPPING ON ORDERS OVER $50'),
      h('header', { className:'lm-header' },
        h('div', { className:'lm-shell' },
          h('div', { className:'lm-header-inner' },
            h(Link, { className:'lm-brand', to:'/' },
              h('span', { className:'lm-brand-mark', 'aria-hidden':true }),
              h('span', null, "Laura’s Mercantile")
            ),
            h('nav', { className:'lm-nav', 'aria-label':'Primary' },
              h(Link, { to:'/shop' }, 'Shop'),
              h(Link, { to:'/our-approach' }, 'Our Approach'),
              h(Link, { to:'/lab-results' }, 'Lab Results'),
              h(Link, { to:'/education' }, 'Education'),
              h(Link, { to:'/about-laura' }, 'About Laura')
            ),
            h('div', { className:'lm-actions' },
              h(Link, { className:'lm-icon-btn', to:'/search', 'aria-label':'Search' }, h(IconSearch)),
              h('a', { className:'lm-icon-btn', href: LM.cartUrl || '/cart/', 'aria-label':'Cart' },
                h('span', { style:{ display:'inline-flex', alignItems:'center', gap:8 } },
                  h(IconCart),
                  (typeof cartCount === 'number' && cartCount > 0)
                    ? h('span', { style:{ fontSize:12, background:'rgba(60,75,61,0.12)', color:'var(--lm-sage-2)', padding:'2px 8px', borderRadius:999 } }, String(cartCount))
                    : null
                )
              ),
              h('a', { className:'lm-icon-btn', href: LM.accountUrl || '/my-account/', 'aria-label': loggedIn ? 'My Account' : 'Sign In', style:{ padding:'8px 10px' } }, loggedIn ? 'Account' : 'Sign In')
            )
          )
        )
      )
    );
  }

  function SiteFooter(){
    return h('footer', { className:'lm-footer' },
      h('div', { className:'lm-shell' },
        h('div', { style:{ display:'flex', gap:24, flexWrap:'wrap', alignItems:'flex-start', justifyContent:'space-between' } },
          h('div', { style:{ maxWidth:420 } },
            h('div', { style:{ fontFamily:'var(--lm-serif)', fontSize:18, color:'var(--lm-ink)', marginBottom:8 } }, "Laura’s Mercantile"),
            h('div', null, 'Plant-powered wellness from the farm. Sustainably sourced, clearly labeled, third-party tested.')
          ),
          h('div', { style:{ display:'flex', gap:16, flexWrap:'wrap' } },
            h('a', { href:'/privacy/' }, 'Privacy'),
            h('a', { href:'/terms/' }, 'Terms'),
            h('a', { href:'/contact-us/' }, 'Contact')
          )
        )
      )
    );
  }

  function Home(){
    const benefits = [
      { title:'Sleep & Recovery', cta:'Explore Sleep', to:'/product-category/sleep' },
      { title:'Relief & Balance', cta:'Explore Relief', to:'/product-category/relief' },
      { title:'Energy & Endurance', cta:'Explore Energy', to:'/product-category/energy' },
      { title:'Brain Health & Focus', cta:'Explore Focus', to:'/product-category/focus' },
    ];

    return h('div', { className:'lm-page' },
      h('div', { className:'lm-shell' },
        h('section', { className:'lm-hero' },
          h('div', { className:'lm-hero-left' },
            h('h1', { className:'lm-h1' }, 'Powered by Nature.', h('br'), 'Proven by Science.', h('br'), 'Pioneering Longevity.'),
            h('p', { className:'lm-lede' }, 'Laura changed the way American eats by introducing no-antibiotic, no growth hormone beef to a national audience while championing regenerative agriculture. Now in her 60s, her drive and vitality are proof that choosing integrity in food and farming leads to a longer, stronger life.'),
            h('div', { style:{ marginTop:18, display:'flex', gap:12, flexWrap:'wrap' } },
              h('a', { className:'lm-btn', href: LM.shopUrl || '/shop/' }, 'Shop Now'),
              h(Link, { className:'lm-btn secondary', to:'/our-approach' }, 'Our Approach')
            )
          ),
          h('div', { className:'lm-hero-right', 'aria-hidden':true })
        ),


        h('section', { className:'lm-founder' },
          h('div', { className:'lm-founder-card' },
            h('div', { className:'lm-founder-media' },
              h('img', { src:(LM.assetBase || '') + 'images/laura-field-1200.jpg', alt:'Laura in the field', loading:'lazy' })
            ),
            h('div', { className:'lm-founder-body' },
              h('div', { className:'lm-founder-eyebrow' }, 'Meet Laura'),
              h('h2', { className:'lm-founder-title' }, 'A farmer\'s integrity, a scientist\'s standard.'),
              h('p', { className:'lm-founder-text' }, "For decades, Laura has pushed for cleaner food systems\u2014standing up for farmers, demanding transparency, and holding every ingredient to a higher standard. That same integrity guides everything we make: responsibly sourced, clearly labeled, and third-party tested."),
            h('div', { className:'lm-founder-actions' },
                h(Link, { className:'lm-btn', to:'/meet-laura' }, 'Read Laura\'s Story'),
                h('a', { className:'lm-btn secondary', href: LM.shopUrl || '/shop/' }, 'Explore the Shop')
              )
            )
          )
        ),


        h('h2', { className:'lm-section-title' }, 'Shop By Health Benefit'),
        h('div', { className:'lm-kicker' }, 'Find the right product for your goals.'),

        h('div', { className:'lm-grid-4', style:{ marginTop:18 } },
          benefits.map(b =>
            h('div', { className:'lm-benefit-card', key:b.title },
              h('div', { className:'lm-benefit-img' }),
              h('div', { className:'lm-benefit-body' },
                h('div', { className:'lm-benefit-name' }, b.title),
                h(Link, { className:'lm-btn secondary', to:b.to, style:{ width:'100%' } }, b.cta)
              )
            )
          )
        ),

        h('h2', { className:'lm-section-title', style:{ marginTop:44 } }, "What Sets Laura’s Apart"),
        h('div', { className:'lm-kicker' }, 'Plant-powered and responsibly sourced — every batch, every time.'),

        h('section', { className:'lm-features', style:{ marginTop:18 } },
          h('div', { className:'lm-card lm-feature-row' },
            h('div', { className:'lm-feature' },
              h('div', { style:{ fontSize:26 } }, '🌿'),
              h('h3', null, 'U.S. Grown Hemp'),
              h('p', null, 'Plant-powered and responsibly sourced.')
            ),
            h('div', { className:'lm-feature' },
              h('div', { style:{ fontSize:26 } }, '🧪'),
              h('h3', null, 'Third-Party Tested'),
              h('p', null, 'Every batch, every time.')
            ),
            h('div', { className:'lm-feature' },
              h('div', { style:{ fontSize:26 } }, '🏷️'),
              h('h3', null, 'Clearly Labeled'),
              h('p', null, 'Nothing hidden. Nothing exaggerated.')
            ),
            h('div', { style:{ gridColumn:'1 / -1', textAlign:'center', paddingTop:10 } },
              h('a', { className:'lm-btn', href: LM.shopUrl || '/shop/' }, 'Shop Our Products')
            )
          ),
          h('div', { className:'lm-product-shot' }, h('div', { className:'img', 'aria-hidden':true }))
        ),

        h('h2', { className:'lm-section-title', style:{ marginTop:44 } }, 'Botanical Wellness From the Farm'),
        h('div', { className:'lm-kicker' }, 'CBD and mushrooms to support sleep, reducing inflammation, energy, and focus — naturally sourced.'),
        h('div', { style:{ marginTop:18, textAlign:'center' } },
          h('a', { className:'lm-btn', href: LM.shopUrl || '/shop/' }, 'Explore the Shop')
        )
      )
    );
  }

  function ContentPage({ slug, titleFallback }){
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [page, setPage] = useState(null);

    useEffect(() => {
      let cancelled=false;
      (async () => {
        try{
          const restUrl = LM.restUrl || '/wp-json/';
          const url = restUrl.replace(/\/?$/, '/') + 'wp/v2/pages?slug=' + encodeURIComponent(slug) + '&_fields=title,content,slug';
          const res = await fetch(url, { credentials:'same-origin' });
          if(!res.ok) throw new Error('WP REST error ' + res.status);
          const data = await res.json();
          const p = (data && data[0]) ? data[0] : null;
          if(!cancelled){ setPage(p); setLoading(false); setError(null); }
        }catch(e){
          if(!cancelled){ setPage(null); setLoading(false); setError(e.message); }
        }
      })();
      return ()=>{ cancelled=true; };
    }, [slug]);

    return h('div', { className:'lm-page' },
      h('div', { className:'lm-shell lm-content' },
        loading ? h('div', { className:'lm-notice' }, 'Loading…') : null,
        error ? h('div', { className:'lm-notice' }, 'Could not load this page from WordPress. ('+ error +')') : null,
        page ? h('div', { className:'wp-content' },
          h('h1', { dangerouslySetInnerHTML:{ __html: (page.title && page.title.rendered) ? page.title.rendered : (titleFallback || '') } }),
          h('div', { dangerouslySetInnerHTML:{ __html: (page.content && page.content.rendered) ? page.content.rendered : '' } })
        ) : (!loading ? h('div', { className:'wp-content' },
          h('h1', null, titleFallback || 'Page'),
          h('p', null, 'This route is wired in the app. To show real content here, create a WordPress page with slug "', slug, '".')
        ) : null)
      )
    );
  }

  function Shop(){
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [categories, setCategories] = useState([]);
    const [products, setProducts] = useState([]);

    useEffect(() => {
      let cancelled=false;
      (async () => {
        try{
          const [catRes, prodRes] = await Promise.all([
            fetch('/wp-json/wc/store/v1/products/categories?per_page=40', { credentials:'same-origin' }),
            fetch('/wp-json/wc/store/v1/products?per_page=12', { credentials:'same-origin' }),
          ]);
          if(!catRes.ok || !prodRes.ok) throw new Error('Woo Store API not available (blocked or disabled).');
          const cats = await catRes.json();
          const prods = await prodRes.json();
          if(!cancelled){
            setCategories(Array.isArray(cats) ? cats : []);
            setProducts(Array.isArray(prods) ? prods : []);
            setLoading(false);
          }
        }catch(e){
          if(!cancelled){
            setError(e.message);
            setLoading(false);
          }
        }
      })();
      return ()=>{ cancelled=true; };
    }, []);

    return h('div', { className:'lm-page' },
      h('div', { className:'lm-shell' },
        h('h1', { className:'lm-h1', style:{ fontSize:38 } }, 'Shop'),
        h('p', { className:'lm-lede' }, 'Browse categories and featured products.'),

        loading ? h('div', { className:'lm-notice', style:{ marginTop:14 } }, 'Loading shop…') : null,
        error ? h('div', { className:'lm-notice', style:{ marginTop:14 } },
          h('strong', null, 'Shop data isn’t available via the Woo Store API.'),
          h('div', { style:{ marginTop:8 } }, 'That’s okay for the hybrid rollout. You can still use the WordPress shop at ', h('a', { href:'/shop/' }, '/shop/'), '.'),
          h('div', { style:{ marginTop:8 } }, 'Error: ', error)
        ) : null,

        (categories && categories.length) ? h(wp.element.Fragment, null,
          h('h2', { className:'lm-section-title', style:{ textAlign:'left', margin:'26px 0 14px' } }, 'Categories'),
          h('div', { className:'lm-grid-4' },
            categories.filter(c => !c.parent).slice(0,12).map(c =>
              h('div', { className:'lm-benefit-card', key:c.id },
                h('div', { className:'lm-benefit-img' }),
                h('div', { className:'lm-benefit-body' },
                  h('div', { className:'lm-benefit-name' }, c.name),
                  h(Link, { className:'lm-btn secondary', to:'/product-category/' + c.slug }, 'Browse')
                )
              )
            )
          )
        ) : null,

        (products && products.length) ? h(wp.element.Fragment, null,
          h('h2', { className:'lm-section-title', style:{ textAlign:'left', margin:'34px 0 14px' } }, 'Featured'),
          h('div', { className:'lm-grid-4' },
            products.slice(0,8).map(p =>
              h('div', { className:'lm-benefit-card', key:p.id },
                h('div', { className:'lm-benefit-img', style: p.images && p.images[0] && p.images[0].src ? {
                  backgroundImage:'url(' + p.images[0].src + ')',
                  backgroundSize:'cover',
                  backgroundPosition:'center'
                } : null }),
                h('div', { className:'lm-benefit-body' },
                  h('div', { className:'lm-benefit-name', dangerouslySetInnerHTML:{ __html: p.name } }),
                  h('a', { className:'lm-btn secondary', href:p.permalink }, 'View')
                )
              )
            )
          )
        ) : null
      )
    );
  }

  function Category({ slug }){
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [category, setCategory] = useState(null);
    const [products, setProducts] = useState([]);

    useEffect(() => {
      let cancelled=false;
      (async () => {
        try{
          const catRes = await fetch('/wp-json/wc/store/v1/products/categories?slug=' + encodeURIComponent(slug), { credentials:'same-origin' });
          if(!catRes.ok) throw new Error('Woo Store API not available.');
          const cats = await catRes.json();
          const c = (cats && cats[0]) ? cats[0] : null;
          let prods = [];
          if (c && c.id) {
            const prodRes = await fetch('/wp-json/wc/store/v1/products?category=' + c.id + '&per_page=24', { credentials:'same-origin' });
            if (prodRes.ok) prods = await prodRes.json();
          }
          if(!cancelled){
            setCategory(c);
            setProducts(Array.isArray(prods) ? prods : []);
            setLoading(false);
          }
        }catch(e){
          if(!cancelled){
            setError(e.message);
            setLoading(false);
          }
        }
      })();
      return ()=>{ cancelled=true; };
    }, [slug]);

    return h('div', { className:'lm-page' },
      h('div', { className:'lm-shell' },
        h('h1', { className:'lm-h1', style:{ fontSize:38 } }, category ? category.name : 'Category'),
        loading ? h('div', { className:'lm-notice' }, 'Loading…') : null,
        error ? h('div', { className:'lm-notice' }, 'Could not load category from Woo Store API. Error: ' + error) : null,

        (products && products.length) ? h('div', { className:'lm-grid-4', style:{ marginTop:18 } },
          products.map(p =>
            h('div', { className:'lm-benefit-card', key:p.id },
              h('div', { className:'lm-benefit-img', style: p.images && p.images[0] && p.images[0].src ? {
                backgroundImage:'url(' + p.images[0].src + ')',
                backgroundSize:'cover',
                backgroundPosition:'center'
              } : null }),
              h('div', { className:'lm-benefit-body' },
                h('div', { className:'lm-benefit-name', dangerouslySetInnerHTML:{ __html: p.name } }),
                h('a', { className:'lm-btn secondary', href:p.permalink }, 'View')
              )
            )
          )
        ) : null
      )
    );
  }

  function Search(){
    const [q, setQ] = useState('');
    const [results, setResults] = useState([]);
    const [status, setStatus] = useState('idle');

    async function runSearch(e){
      e.preventDefault();
      const query = q.trim();
      if(!query) return;
      setStatus('loading');
      try{
        const restUrl = LM.restUrl || '/wp-json/';
        const url = restUrl.replace(/\/?$/, '/') + 'wp/v2/search?search=' + encodeURIComponent(query) + '&per_page=10';
        const res = await fetch(url, { credentials:'same-origin' });
        if(!res.ok) throw new Error('Search failed ' + res.status);
        const data = await res.json();
        setResults(Array.isArray(data) ? data : []);
        setStatus('done');
      }catch(e){
        setResults([]);
        setStatus('error');
      }
    }

    return h('div', { className:'lm-page' },
      h('div', { className:'lm-shell lm-content' },
        h('div', { className:'wp-content' },
          h('h1', { style:{ fontFamily:'var(--lm-serif)' } }, 'Search'),
          h('form', { onSubmit: runSearch, style:{ display:'flex', gap:10, flexWrap:'wrap', marginTop:10 } },
            h('input', {
              value:q,
              onChange:(e)=>setQ(e.target.value),
              placeholder:'Search pages and posts…',
              style:{ flex:'1 1 260px', padding:'12px 14px', borderRadius:12, border:'1px solid var(--lm-border)' }
            }),
            h('button', { className:'lm-btn', type:'submit' }, 'Search')
          ),
          status === 'loading' ? h('div', { className:'lm-notice', style:{ marginTop:14 } }, 'Searching…') : null,
          status === 'error' ? h('div', { className:'lm-notice', style:{ marginTop:14 } }, 'Search is unavailable.') : null,
          (results && results.length) ? h('ul', { style:{ marginTop:14 } },
            results.map(r => h('li', { key:r.id, style:{ margin:'10px 0' } },
              h('a', { href:r.url, dangerouslySetInnerHTML:{ __html: r.title } })
            ))
          ) : (status === 'done' ? h('div', { className:'lm-notice', style:{ marginTop:14 } }, 'No results.') : null)
        )
      )
    );
  }

  function Router(){
    const [path] = usePath();

    // normalize
    const p = path === '' ? '/' : path;

    if (isProtected(p)) {
      return hardRedirect(p + '/');
    }

    // Static routes
    if (p === '/' ) return h(Home);
    if (p === '/shop') return h(Shop);
    if (p === '/search') return h(Search);

    // Category route
    const catMatch = p.match(/^\/product-category\/([^\/]+)$/);
    if (catMatch) return h(Category, { slug: catMatch[1] });

    // Content pages
    const contentMap = {
      '/our-approach': ['our-approach', 'Our Approach'],
      '/lab-results': ['lab-results', 'Lab Results'],
      '/education': ['education', 'Education'],
      '/about-laura': ['about-laura', 'About Laura'],
      '/questions': ['questions', 'Questions'],
      '/faq': ['faq', 'FAQ'],
      '/meet-laura': ['meet-laura', 'Meet Laura'],
      '/military': ['military', 'Military Program'],
      '/loyalty': ['loyalty', 'Loyalty Program'],
    };
    if (contentMap[p]) {
      const [slug, title] = contentMap[p];
      return h(ContentPage, { slug, titleFallback:title });
    }

    // Unknown -> Home
    return h(Home);
  }

  function App(){
    // PHP theme owns the global header/footer. React renders only the page body.
    return h(Router);
  }

  function mount(){
    const el = document.getElementById(ROOT_ID);
    if (!el) return;
    wp.element.render(h(App), el);
  }

  // Mount when DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mount);
  } else {
    mount();
  }
})(); 
