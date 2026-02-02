import React from 'react';
import { Link } from 'react-router-dom';
import ProductCard from '../components/ProductCard.jsx';



function getCatSlugs(p) {
  return (p?.categories || [])
    .map((c) => (c?.slug || '').toLowerCase())
    .filter(Boolean);
}

function isBundle(p) {
  const slug = (p?.slug || '').toLowerCase();
  const name = (p?.name || '').toLowerCase();
  const cats = new Set(getCatSlugs(p));
  return (
    slug.includes('bundle') ||
    name.includes('bundle') ||
    cats.has('bundle') ||
    cats.has('bundles')
  );
}

function rankProduct(p) {
  const cats = new Set(getCatSlugs(p));

  // 1) CBD oils & tinctures (exclude bundles)
  if (cats.has('full-spectrum-cbd-oil') && !isBundle(p)) return 1;

  // 2) Functional Chocolates + Caramels
  if (cats.has('cbd-sweets')) return 2;

  // 3) Functional Mushrooms
  if (cats.has('functional-mushrooms')) return 3;

  // 4) Onco / Tippens
  if (cats.has('joe-tippens-protocol-products')) return 4;

  // 5) Everything else (includes dogs + bundles)
  return 999;
}

function groupSortProducts(products) {
  return (products || [])
    .map((p, idx) => ({ p, idx, r: rankProduct(p) }))
    .sort((a, b) => (a.r !== b.r ? a.r - b.r : a.idx - b.idx))
    .map((x) => x.p);
}

export default function Shop() {
  const [state, setState] = React.useState({ 
    loading: true, 
    error: null, 
    categories: [], 
    products: [] 
  });
  const [view, setView] = React.useState('categories'); // 'categories' or 'products'

  React.useEffect(() => {
    let cancelled = false;
    async function run() {
      try {
        // Try Woo Store API first
        const [catRes, prodRes] = await Promise.all([
          fetch('/wp-json/wc/store/v1/products/categories?per_page=40', { 
            credentials: 'same-origin' 
          }),
          fetch('/wp-json/wc/store/v1/products?per_page=100&orderby=menu_order&order=asc', { 
            credentials: 'same-origin' 
          }),
        ]);
        
        if (!catRes.ok || !prodRes.ok) {
          throw new Error('Woo Store API not available (blocked or disabled).');
        }

        const [categories, products] = await Promise.all([
          catRes.json(), 
          prodRes.json()
        ]);
        
        if (!cancelled) {
          setState({ 
            loading: false, 
            error: null, 
            categories, 
            products: groupSortProducts(products) 
          });
        }
      } catch (e) {
        if (!cancelled) {
          setState({ 
            loading: false, 
            error: e.message, 
            categories: [], 
            products: [] 
          });
        }
      }
    }
    run();
    return () => { cancelled = true; };
  }, []);

  const topLevelCategories = state.categories?.filter(c => !c?.parent) || [];

  return (
    <div className="lm-page">
      <div className="lm-shell">
        {/* Shop Header */}
        <div className="lm-shop-header">
          <div>
            <h1 className="lm-h1">Shop Natural Wellness</h1>
            <p className="lm-lede">
              Premium CBD and functional mushroom products, sustainably sourced and third-party tested.
            </p>
          </div>
          
          {/* View Toggle */}
          {!state.loading && !state.error && (
            <div className="lm-view-toggle">
              <button
                className={`lm-toggle-btn ${view === 'categories' ? 'active' : ''}`}
                onClick={() => setView('categories')}
                aria-pressed={view === 'categories'}
              >
                Categories
              </button>
              <button
                className={`lm-toggle-btn ${view === 'products' ? 'active' : ''}`}
                onClick={() => setView('products')}
                aria-pressed={view === 'products'}
              >
                All Products
              </button>
            </div>
          )}
        </div>

        {/* Loading State */}
        {state.loading && (
          <div className="lm-notice lm-notice-loading">
            <div className="lm-spinner"></div>
            <span>Loading shop...</span>
          </div>
        )}

        {/* Error State */}
        {state.error && (
          <div className="lm-notice lm-notice-error">
            <strong>Shop data isn't available via the Woo Store API.</strong>
            <div style={{ marginTop: 8 }}>
              That's okay for the hybrid rollout. You can still use the WordPress shop at{' '}
              <a href="/shop/">laurasmercantile.com/shop/</a>.
            </div>
            <details style={{ marginTop: 12, fontSize: 14 }}>
              <summary style={{ cursor: 'pointer', color: 'var(--lm-muted)' }}>
                Technical Details
              </summary>
              <div style={{ marginTop: 8, color: 'var(--lm-muted-light)' }}>
                {state.error}
              </div>
            </details>
          </div>
        )}

        {/* Categories View */}
        {!state.loading && !state.error && view === 'categories' && (
          <>
            {topLevelCategories.length > 0 ? (
              <>
                <h2 className="lm-section-title" style={{ textAlign: 'left', marginTop: 32 }}>
                  Shop by Category
                </h2>
                <div className="lm-kicker" style={{ textAlign: 'left', maxWidth: '100%' }}>
                  Browse our curated collections of CBD oils, topicals, and wellness products.
                </div>
                
                <div className="lm-category-grid">
                  {topLevelCategories.slice(0, 12).map((category) => (
                    <Link
                      key={category.id}
                      to={`/product-category/${category.slug}`}
                      className="lm-category-card"
                    >
                      <div className="lm-category-image">
                        {category.image?.src ? (
                          <img 
                            src={category.image.src} 
                            alt={category.name}
                            loading="lazy"
                          />
                        ) : (
                          <div className="lm-category-placeholder">
                            <span className="lm-category-icon">🌿</span>
                          </div>
                        )}
                      </div>
                      <div className="lm-category-body">
                        <h3 className="lm-category-name">{category.name}</h3>
                        {category.count > 0 && (
                          <div className="lm-category-count">
                            {category.count} {category.count === 1 ? 'product' : 'products'}
                          </div>
                        )}
                        <div className="lm-category-arrow">→</div>
                      </div>
                    </Link>
                  ))}
                </div>
              </>
            ) : (
              <div className="lm-notice">No categories found.</div>
            )}
          </>
        )}

        {/* Products View */}
        {!state.loading && !state.error && view === 'products' && (
          <>
            {state.products?.length > 0 ? (
              <>
                <h2 className="lm-section-title" style={{ textAlign: 'left', marginTop: 32 }}>
                  Featured Products
                </h2>
                <div className="lm-kicker" style={{ textAlign: 'left', maxWidth: '100%' }}>
                  Our most popular wellness products, loved by customers.
                </div>
                
                <div className="lm-product-grid">
                  {state.products.slice(0, 12).map((product) => (
                    <ProductCard key={product.id} product={product} />
                  ))}
                </div>

                {state.products.length > 12 && (
                  <div style={{ textAlign: 'center', marginTop: 32 }}>
                    <a href="/shop/" className="lm-btn">
                      View All Products
                    </a>
                  </div>
                )}
              </>
            ) : (
              <div className="lm-notice">No products found.</div>
            )}
          </>
        )}

        {/* Shop Features */}
        {!state.loading && !state.error && (
          <section className="lm-shop-features">
            <div className="lm-shop-feature">
              <div className="lm-shop-feature-icon">🌱</div>
              <div>
                <h3>U.S. Grown Hemp</h3>
                <p>Organically farmed and sustainably sourced</p>
              </div>
            </div>
            <div className="lm-shop-feature">
              <div className="lm-shop-feature-icon">🧪</div>
              <div>
                <h3>Lab Tested</h3>
                <p>Third-party verified for purity and potency</p>
              </div>
            </div>
            <div className="lm-shop-feature">
              <div className="lm-shop-feature-icon">📦</div>
              <div>
                <h3>Free Shipping</h3>
                <p>On all orders over $50</p>
              </div>
            </div>
          </section>
        )}
      </div>
    </div>
  );
}
