import React from 'react';
import { Link } from 'react-router-dom';

export default function Home() {
  const lm = window.__LM__ || {};
  return (
    <div className="lm-page">
      <div className="lm-shell">
        <section className="lm-hero">
          <div className="lm-hero-left">
            <h1 className="lm-h1">Powered by Nature.<br />Proven by Science.<br />Pioneering Longevity.</h1>
            <p className="lm-lede">Laura changed the way American eats by introducing no-antibiotic, no growth hormone beef to a national audience while championing regenerative agriculture. Now in her 60s, her drive and vitality are proof that choosing integrity in food and farming leads to a longer, stronger life.</p>
            <div style={{ marginTop: 18, display: 'flex', gap: 12, flexWrap: 'wrap' }}>
              <a className="lm-btn" href={lm.shopUrl || '/shop/'}>Shop Now</a>
              <Link className="lm-btn secondary" to="/our-approach">Our Approach</Link>
            </div>
          </div>
          <div className="lm-hero-right" aria-hidden="true" />
        </section>


        <section className="lm-founder">
          <div className="lm-founder-card">
            <div className="lm-founder-media">
              <img
                src={(lm.assetBase || '/wp-content/themes/lauras-mercantile-hybrid/assets/') + 'images/laura-field-1200.jpg'}
                alt="Laura in the field"
                loading="lazy"
              />
            </div>
            <div className="lm-founder-body">
              <div className="lm-founder-eyebrow">Meet Laura</div>
              <h2 className="lm-founder-title">A farmer’s integrity, a scientist’s standard.</h2>
              <p className="lm-founder-text">For decades, Laura has pushed for cleaner food systems—standing up for farmers, demanding transparency, and holding every ingredient to a higher standard. That same integrity guides everything we make: responsibly sourced, clearly labeled, and third-party tested.</p>
              <div className="lm-founder-actions">
                <Link className="lm-btn" to="/meet-laura">Read Laura’s Story</Link>
                <a className="lm-btn secondary" href={lm.shopUrl || '/shop/'}>Explore the Shop</a>
              </div>
            </div>
          </div>
        </section>



        <h2 className="lm-section-title" style={{ marginTop: 44 }}>What Sets Laura’s Apart</h2>
        <div className="lm-kicker">Plant-powered and responsibly sourced — every batch, every time.</div>

        <section className="lm-features" style={{ marginTop: 18 }}>
          <div className="lm-card lm-feature-row">
            <div className="lm-feature">
              <div style={{ fontSize: 26 }}>🌿</div>
              <h3>U.S. Grown Hemp</h3>
              <p>Plant-powered and responsibly sourced.</p>
            </div>
            <div className="lm-feature">
              <div style={{ fontSize: 26 }}>🧪</div>
              <h3>Third-Party Tested</h3>
              <p>Every batch, every time.</p>
            </div>
            <div className="lm-feature">
              <div style={{ fontSize: 26 }}>🏷️</div>
              <h3>Clearly Labeled</h3>
              <p>Nothing hidden. Nothing exaggerated.</p>
            </div>
            <div style={{ gridColumn: '1 / -1', textAlign: 'center', paddingTop: 10 }}>
              <a className="lm-btn" href={lm.shopUrl || '/shop/'}>Shop Our Products</a>
            </div>
          </div>

          <div className="lm-product-shot">
            <div className="img" aria-hidden="true"></div>
          </div>
        </section>

        <h2 className="lm-section-title" style={{ marginTop: 44 }}>Botanical Wellness From the Farm</h2>
        <div className="lm-kicker">CBD and mushrooms to support sleep, reducing inflammation, energy, and focus — naturally sourced.</div>

        <div style={{ marginTop: 18, textAlign: 'center' }}>
          <a className="lm-btn" href={lm.shopUrl || '/shop/'}>Explore the Shop</a>
        </div>
      </div>
    </div>
  );
}
