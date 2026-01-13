import React from 'react';
import { useParams, Link } from 'react-router-dom';
import ProductCard from '../components/ProductCard.jsx';

export default function Category() {
  const { slug } = useParams();
  const [state, setState] = React.useState({ 
    loading: true, 
    error: null, 
    category: null, 
    products: [] 
  });
  const [sortBy, setSortBy] = React.useState('default'); // default, price-asc, price-desc, name

  React.useEffect(() => {
    let cancelled = false;
    
    async function run() {
      try {
        // Fetch category info
        const catRes = await fetch(
          `/wp-json/wc/store/v1/products/categories?slug=${encodeURIComponent(slug)}`, 
          { credentials: 'same-origin' }
        );
        
        if (!catRes.ok) {
          throw new Error('Woo Store API not available.');
        }
        
        const cats = await catRes.json();
        const category = cats?.[0] ?? null;

        let products = [];
        if (category?.id) {
          // Fetch products in this category
          const prodRes = await fetch(
            `/wp-json/wc/store/v1/products?category=${category.id}&per_page=48`, 
            { credentials: 'same-origin' }
          );
          if (prodRes.ok) {
            products = await prodRes.json();
          }
        }

        if (!cancelled) {
          setState({ 
            loading: false, 
            error: null, 
            category, 
            products 
          });
        }
      } catch (e) {
        if (!cancelled) {
          setState({ 
            loading: false, 
            error: e.message, 
            category: null, 
            products: [] 
          });
        }
      }
    }
    
    run();
    return () => { cancelled = true; };
  }, [slug]);

  // Sort products based on selected option
  const sortedProducts = React.useMemo(() => {
    if (!state.products) return [];
    
    const products = [...state.products];
    
    switch (sortBy) {
      case 'price-asc':
        return products.sort((a, b) => {
          const priceA = parseFloat(a.prices?.price || a.price || 0);
          const priceB = parseFloat(b.prices?.price || b.price || 0);
          return priceA - priceB;
        });
      
      case 'price-desc':
        return products.sort((a, b) => {
          const priceA = parseFloat(a.prices?.price || a.price || 0);
          const priceB = parseFloat(b.prices?.price || b.price || 0);
          return priceB - priceA;
        });
      
      case 'name':
        return products.sort((a, b) => 
          (a.name || '').localeCompare(b.name || '')
        );
      
      default:
        return products;
    }
  }, [state.products, sortBy]);

  return (
    <div className="lm-page">
      <div className="lm-shell">
        {/* Breadcrumb */}
        <nav className="lm-breadcrumb" aria-label="Breadcrumb">
          <Link to="/">Home</Link>
          <span className="lm-breadcrumb-sep">›</span>
          <Link to="/shop">Shop</Link>
          {state.category && (
            <>
              <span className="lm-breadcrumb-sep">›</span>
              <span className="lm-breadcrumb-current">{state.category.name}</span>
            </>
          )}
        </nav>

        {/* Category Header */}
        <div className="lm-category-header">
          <div>
            <h1 className="lm-h1">
              {state.category?.name || 'Category'}
            </h1>
            {state.category?.description && (
              <div 
                className="lm-lede"
                dangerouslySetInnerHTML={{ __html: state.category.description }}
              />
            )}
            {state.products?.length > 0 && (
              <div className="lm-category-count-badge">
                {state.products.length} {state.products.length === 1 ? 'product' : 'products'}
              </div>
            )}
          </div>
          
          {/* Sort Controls */}
          {sortedProducts.length > 1 && !state.loading && (
            <div className="lm-sort-controls">
              <label htmlFor="sort-select" className="lm-sort-label">
                Sort by:
              </label>
              <select
                id="sort-select"
                value={sortBy}
                onChange={(e) => setSortBy(e.target.value)}
                className="lm-sort-select"
              >
                <option value="default">Default</option>
                <option value="name">Name (A-Z)</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
              </select>
            </div>
          )}
        </div>

        {/* Loading State */}
        {state.loading && (
          <div className="lm-notice lm-notice-loading">
            <div className="lm-spinner"></div>
            <span>Loading products...</span>
          </div>
        )}

        {/* Error State */}
        {state.error && (
          <div className="lm-notice lm-notice-error">
            <strong>Could not load category from Woo Store API.</strong>
            <div style={{ marginTop: 8 }}>
              You can still browse categories in the WordPress Shop at{' '}
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

        {/* Products Grid */}
        {!state.loading && !state.error && sortedProducts.length > 0 && (
          <div className="lm-product-grid">
            {sortedProducts.map((product) => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
        )}

        {/* Empty State */}
        {!state.loading && !state.error && sortedProducts.length === 0 && state.category && (
          <div className="lm-empty-state">
            <div className="lm-empty-icon">📦</div>
            <h2>No products in this category</h2>
            <p>Check back soon or explore other categories.</p>
            <Link to="/shop" className="lm-btn">
              Browse All Categories
            </Link>
          </div>
        )}
      </div>
    </div>
  );
}
