import React from 'react';
import { Link, NavLink, useNavigate } from 'react-router-dom';

function IconSearch(props){
  return (
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" {...props}>
      <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" strokeWidth="1.8"/>
      <path d="M16.5 16.5 21 21" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round"/>
    </svg>
  );
}
function IconCart(props){
  return (
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" {...props}>
      <path d="M6.5 7h15l-1.2 7.2a2 2 0 0 1-2 1.7H9a2 2 0 0 1-2-1.6L5.7 3.8A1.5 1.5 0 0 0 4.2 2.5H2.8" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"/>
      <path d="M9.5 21a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4ZM18 21a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z" fill="currentColor"/>
    </svg>
  );
}

export default function SiteHeader(){
  const lm = window.__LM__ || {};
  const [loggedIn, setLoggedIn] = React.useState(!!lm.loggedIn);
  const [cartCount, setCartCount] = React.useState(null);

  React.useEffect(() => {
    // Light-touch cart count via Woo Store API. If blocked, we just don't show a number.
    async function loadCart(){
      try{
        const res = await fetch('/wp-json/wc/store/v1/cart', { credentials: 'same-origin' });
        if(!res.ok) return;
        const data = await res.json();
        const count = data?.items_count ?? null;
        setCartCount(typeof count === 'number' ? count : null);
      }catch(e){ /* ignore */ }
    }
    loadCart();
  }, []);

  // If the user navigates within the SPA after login, we can optionally re-check.
  // We keep it conservative to avoid security-plugin edge cases.

  return (
    <>
      <div className="lm-topbar">FREE SHIPPING ON ORDERS OVER $50</div>
      <header className="lm-header">
        <div className="lm-shell">
          <div className="lm-header-inner">
            <Link className="lm-brand" to="/">
              <span className="lm-brand-mark" aria-hidden="true" />
              <span>Laura’s Mercantile</span>
            </Link>

            <nav className="lm-nav" aria-label="Primary">
              <NavLink to="/shop" end>Shop</NavLink>
              <NavLink to="/our-approach">Our Approach</NavLink>
              <NavLink to="/lab-results">Lab Results</NavLink>
              <NavLink to="/education">Education</NavLink>
              <NavLink to="/about-laura">About Laura</NavLink>
            </nav>

            <div className="lm-actions">
              <Link className="lm-icon-btn" to="/search" aria-label="Search">
                <IconSearch />
              </Link>

              <a className="lm-icon-btn" href={lm.cartUrl || '/cart/'} aria-label="Cart">
                <span style={{display:'inline-flex', alignItems:'center', gap:8}}>
                  <IconCart />
                  {typeof cartCount === 'number' && cartCount > 0 ? (
                    <span style={{
                      fontSize:12,
                      background:'rgba(60,75,61,0.12)',
                      color:'var(--lm-sage-2)',
                      padding:'2px 8px',
                      borderRadius:999
                    }}>{cartCount}</span>
                  ) : null}
                </span>
              </a>

              {loggedIn ? (
                <a className="lm-icon-btn" href={lm.accountUrl || '/my-account/'} aria-label="My Account" style={{padding:'8px 10px'}}>
                  Account
                </a>
              ) : (
                <a className="lm-icon-btn" href={lm.accountUrl || '/my-account/'} aria-label="Sign In" style={{padding:'8px 10px'}}>
                  Sign In
                </a>
              )}
            </div>
          </div>
        </div>
      </header>
    </>
  );
}
