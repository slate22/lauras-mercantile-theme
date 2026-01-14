import React from 'react';
import { useSearchParams } from 'react-router-dom';

export default function Search() {
  const [searchParams, setSearchParams] = useSearchParams();
  const queryParam = searchParams.get('q') || '';
  
  const [q, setQ] = React.useState(queryParam);
  const [results, setResults] = React.useState([]);
  const [status, setStatus] = React.useState('idle'); // idle, loading, done, error
  const inputRef = React.useRef(null);

  // Auto-focus search input on mount
  React.useEffect(() => {
    inputRef.current?.focus();
  }, []);

  // Run search if there's a query param on mount
  React.useEffect(() => {
    if (queryParam && queryParam.trim()) {
      setQ(queryParam);
      performSearch(queryParam);
    }
  }, [queryParam]);

  async function performSearch(query) {
    const searchQuery = query.trim();
    if (!searchQuery) return;
    
    setStatus('loading');
    
    try {
      // Search both pages/posts and products
      const restUrl = window.__LM__?.restUrl || '/wp-json/';
      
      const [contentRes, productRes] = await Promise.all([
        // WP content search
        fetch(
          `${restUrl}wp/v2/search?search=${encodeURIComponent(searchQuery)}&per_page=20`,
          { credentials: 'same-origin' }
        ),
        // WooCommerce product search
        fetch(
          `/wp-json/wc/store/v1/products?search=${encodeURIComponent(searchQuery)}&per_page=12`,
          { credentials: 'same-origin' }
        ).catch(() => null) // Gracefully handle if Woo Store API is blocked
      ]);

      if (!contentRes.ok) {
        throw new Error(`Search failed ${contentRes.status}`);
      }

      const contentData = await contentRes.json();
      const contentResults = Array.isArray(contentData) ? contentData : [];

      let productResults = [];
      if (productRes?.ok) {
        const productData = await productRes.json();
        productResults = Array.isArray(productData) ? productData.map(p => ({
          id: `product-${p.id}`,
          title: p.name,
          url: p.permalink,
          type: 'product',
          subtype: 'product',
          price: p.prices?.price || p.price,
          image: p.images?.[0]?.src
        })) : [];
      }

      // Combine and categorize results
      const combined = [
        ...productResults,
        ...contentResults.map(r => ({
          ...r,
          type: r.type || 'page'
        }))
      ];

      setResults(combined);
      setStatus('done');
    } catch (e) {
      console.error('Search error:', e);
      setResults([]);
      setStatus('error');
    }
  }

  async function runSearch(e) {
    e.preventDefault();
    const searchQuery = q.trim();
    if (!searchQuery) return;
    
    // Update URL with search query
    setSearchParams({ q: searchQuery });
    performSearch(searchQuery);
  }

  function clearSearch() {
    setQ('');
    setResults([]);
    setStatus('idle');
    setSearchParams({});
    inputRef.current?.focus();
  }

  // Group results by type
  const productResults = results.filter(r => r.type === 'product');
  const contentResults = results.filter(r => r.type !== 'product');

  const formatPrice = (price) => {
    if (!price) return '';
    const numPrice = parseFloat(price) / 100;
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD'
    }).format(numPrice);
  };

  return (
    <div className="lm-page">
      <div className="lm-shell">
        <div className="lm-search-container">
          {/* Search Header */}
          <div className="lm-search-header">
            <h1 className="lm-h1">Search</h1>
            <p className="lm-lede">
              Find products, articles, and information across Laura's Mercantile.
            </p>
          </div>

          {/* Search Form */}
          <form onSubmit={runSearch} className="lm-search-form">
            <div className="lm-search-input-wrapper">
              <svg 
                className="lm-search-icon" 
                width="20" 
                height="20" 
                viewBox="0 0 24 24" 
                fill="none"
              >
                <path 
                  d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" 
                  stroke="currentColor" 
                  strokeWidth="1.8"
                />
                <path 
                  d="M16.5 16.5 21 21" 
                  stroke="currentColor" 
                  strokeWidth="1.8" 
                  strokeLinecap="round"
                />
              </svg>
              
              <input
                ref={inputRef}
                type="search"
                value={q}
                onChange={(e) => setQ(e.target.value)}
                placeholder="Search for products, pages, articles..."
                className="lm-search-input"
                aria-label="Search query"
              />
              
              {q && (
                <button
                  type="button"
                  onClick={clearSearch}
                  className="lm-search-clear"
                  aria-label="Clear search"
                >
                  ✕
                </button>
              )}
            </div>
            
            <button className="lm-btn" type="submit" disabled={!q.trim()}>
              Search
            </button>
          </form>

          {/* Loading State */}
          {status === 'loading' && (
            <div className="lm-notice lm-notice-loading">
              <div className="lm-spinner"></div>
              <span>Searching...</span>
            </div>
          )}

          {/* Error State */}
          {status === 'error' && (
            <div className="lm-notice lm-notice-error">
              <strong>Search is temporarily unavailable.</strong>
              <div style={{ marginTop: 8 }}>
                Please try again later or browse our{' '}
                <a href="/shop/">shop categories</a>.
              </div>
            </div>
          )}

          {/* Results */}
          {status === 'done' && results.length > 0 && (
            <div className="lm-search-results">
              <div className="lm-search-results-header">
                <h2>Found {results.length} results for "{queryParam}"</h2>
              </div>

              {/* Product Results */}
              {productResults.length > 0 && (
                <section className="lm-search-section">
                  <h3 className="lm-search-section-title">
                    Products ({productResults.length})
                  </h3>
                  <div className="lm-search-product-grid">
                    {productResults.map((result) => (
                      <a 
                        key={result.id} 
                        href={result.url} 
                        className="lm-search-product-card"
                      >
                        {result.image ? (
                          <div className="lm-search-product-image">
                            <img src={result.image} alt="" loading="lazy" />
                          </div>
                        ) : (
                          <div className="lm-search-product-placeholder">
                            🌿
                          </div>
                        )}
                        <div className="lm-search-product-body">
                          <h4 
                            className="lm-search-product-title"
                            dangerouslySetInnerHTML={{ __html: result.title }}
                          />
                          {result.price && (
                            <div className="lm-search-product-price">
                              {formatPrice(result.price)}
                            </div>
                          )}
                        </div>
                      </a>
                    ))}
                  </div>
                </section>
              )}

              {/* Content Results */}
              {contentResults.length > 0 && (
                <section className="lm-search-section">
                  <h3 className="lm-search-section-title">
                    Pages & Articles ({contentResults.length})
                  </h3>
                  <ul className="lm-search-list">
                    {contentResults.map((result) => (
                      <li key={result.id} className="lm-search-list-item">
                        <a href={result.url} className="lm-search-list-link">
                          <div className="lm-search-list-content">
                            <h4 
                              className="lm-search-list-title"
                              dangerouslySetInnerHTML={{ __html: result.title }}
                            />
                            <div className="lm-search-list-meta">
                              <span className="lm-search-list-type">
                                {result.subtype === 'page' ? 'Page' : 
                                 result.subtype === 'post' ? 'Article' : 
                                 result.subtype || 'Content'}
                              </span>
                            </div>
                          </div>
                          <svg 
                            className="lm-search-list-arrow" 
                            width="20" 
                            height="20" 
                            viewBox="0 0 24 24" 
                            fill="none"
                          >
                            <path 
                              d="M5 12h14m-7-7 7 7-7 7" 
                              stroke="currentColor" 
                              strokeWidth="2" 
                              strokeLinecap="round" 
                              strokeLinejoin="round"
                            />
                          </svg>
                        </a>
                      </li>
                    ))}
                  </ul>
                </section>
              )}
            </div>
          )}

          {/* No Results */}
          {status === 'done' && results.length === 0 && (
            <div className="lm-empty-state">
              <div className="lm-empty-icon">🔍</div>
              <h2>No results found for "{queryParam}"</h2>
              <p>Try different keywords or browse our categories.</p>
              <div style={{ display: 'flex', gap: 12, justifyContent: 'center', marginTop: 16 }}>
                <a href="/shop/" className="lm-btn">
                  Browse Shop
                </a>
                <button 
                  type="button" 
                  onClick={clearSearch} 
                  className="lm-btn secondary"
                >
                  Clear Search
                </button>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
