Hero Refinement – Founder & Steward (Iconic Portrait)

Why
- The previous hero portrait read as processed/inset (strong vignette/halo), which undermined “heritage + integrity.”
- This pass removes the vignette and reframes the portrait so Laura reads as founder + steward—presence without UI tricks.

Files changed
- assets/images/hero-laura-portrait-hero-v6.jpg
  - New crop/export derived from the existing portrait source that removes the vignette and centers the moment on Laura + the hemp.
- assets/dist/index.BwyvtTPa.js
  - Updated the homepage hero image filename to hero-laura-portrait-hero-v6.jpg (cache-busting via new filename).
- assets/base.css
  - Homepage-only hero art styling: sets a deliberate portrait frame (4:5), uses object-fit: cover with higher object-position, removes filters/masks, and replaces glow with a subtle border + shadow.

Safety / scope
- Homepage-only selectors (prefixed with .home). No changes to checkout/cart/product templates, global typography, or navigation.
