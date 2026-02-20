import React from 'react';

export default function SiteFooter() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="lm-footer">
      <div className="lm-shell">
        <div className="lm-footer-grid">
          {/* Brand Column */}
          <div className="lm-footer-col lm-footer-brand">
            <div style={{ fontFamily: 'var(--lm-serif)', fontSize: 22, color: '#fff', marginBottom: 16 }}>
              Laura’s Mercantile
            </div>
            <p>
              Restoring the earth and healing bodies with regenerative hemp farming in Winchester, Kentucky.
              Plant-powered wellness, sustainably sourced and third-party tested.
            </p>
          </div>

          {/* Information Column */}
          <div className="lm-footer-col">
            <h3>Information</h3>
            <div className="lm-footer-links">
              <a href="/shop">Shop All</a>
              <a href="/product-category/cbd-oil-tinctures">CBD Oil</a>
              <a href="/product-category/cbd-chocolates">Chocolates</a>
              <a href="https://laurasmercantile.com/cbd-legal/">Lab Results</a>
              <a href="/faq">FAQ</a>
            </div>
          </div>

          {/* Company Column */}
          <div className="lm-footer-col">
            <h3>Company</h3>
            <div className="lm-footer-links">
              <a href="/our-approach">Our Approach</a>
              <a href="/lauras-story-from-lauras-lean-beef-to-full-spectrum-cbd">Meet Laura</a>
              <a href="/education">Education</a>
              <a href="/contact-us">Contact Us</a>
            </div>
          </div>

          {/* Contact Column */}
          <div className="lm-footer-col">
            <h3>Contact</h3>
            <div className="lm-footer-links">
              <a href="mailto:support@laurasmercantile.com">support@laurasmercantile.com</a>
              <div style={{ color: 'rgba(255,255,255,0.75)', lineHeight: 1.6, marginTop: 8 }}>
                Mt. Folly Farm<br />
                Winchester, KY 40391
              </div>
            </div>
          </div>
        </div>

        {/* Footer Bottom */}
        <div className="lm-footer-bottom">
          <div>
            &copy; {currentYear} Laura’s Mercantile. All rights reserved.
          </div>
          <div className="lm-footer-legal">
            <a href="/privacy-policy">Privacy Policy</a>
            <a href="/terms-of-service">Terms of Service</a>
          </div>
        </div>
      </div>
    </footer>
  );
}
