# Homepage Outcomes Editorial Triptych — Changelog

## Goal
Redesign **homepage outcomes section** into an editorial triptych with rustic, ultra-premium heritage feel.

## Non-negotiables respected
- No checkout/cart/product page changes
- No product data changes (titles/descriptions/pricing/SKUs)
- No header/nav/mega menu changes
- No global typography scale changes

## Files touched

### 1) `functions.php`
**Why:** Enqueue a tiny DOM-transform script **only on the front page**.
- Adds `lm_enqueue_home_outcomes_editorial()` guarded by `is_front_page()` and `lm_should_mount_app()`.

### 2) `assets/js/home-outcomes-editorial.js` (new)
**Why:** Removes non-compliant sub-elements from the outcomes section after React renders.
- Removes `.lm-kicker` (explicit funnel guidance)
- Removes `.lm-outcome-media` (illustration wrapper)
- Removes `.lm-outcome-micro` (extra UI copy)
- Ensures each panel contains **only**: `.lm-outcome-eyebrow`, `.lm-outcome-title`, `.lm-outcome-cta`

### 3) `assets/base.css`
**Why:** Adds **strictly-scoped** styling under `.home .lm-outcomes.lm-outcomes--editorial`.
- Removes the old "Step 1 of 3" funnel pill on this section
- Replaces legacy card/overlay styling with a simple triptych layout that matches the site
- Parchment background texture (subtle)
- Photography rendered via `::before` as a plain image surface (no gradients/overlays)
- CTA styled as an editorial link (no pill/button, no icon chrome)

### 4) `assets/images/*` (new)
- `parchment-subtle.png`
- `outcome-sleep-photo.jpg`
- `outcome-move-photo.jpg`
- `outcome-brain-photo.jpg`

**Why:** Real lifestyle photography + subtle heritage background.

## Regression safety
- React bundle in `assets/dist/*` is unchanged from your v22 baseline.
- Script runs only on homepage and only targets the outcomes section.

## Iteration: Rounded corners + refreshed imagery

### `assets/base.css`
- Rounded the outcomes section parchment field (`border-radius` + `overflow: hidden`) so it sits more naturally in the page.
- Added gentle corner radius to the photo moments (print-like softness; not a card).
- Tweaked background-position per outcome so the motion reads well at the fixed aspect ratio.

### `assets/images/outcome-*-photo.jpg` (previous iteration)
- Replaced all three outcome images with motion-forward photography.
- Later changed to versioned filenames (`outcome-*-photo-v2.jpg`) to bypass caching; see next section.

## Iteration: Cache-bust image filenames (v2)

### `assets/base.css`
- Updated outcomes background-image URLs to new versioned filenames to bypass CDN/browser caching.

### `assets/images/outcome-*-photo-v2.jpg` (new)
- Added new, striking motion-forward photography exports:
  - `outcome-sleep-photo-v2.jpg`
  - `outcome-move-photo-v2.jpg`
  - `outcome-brain-photo-v2.jpg`
- These are the images now referenced by the homepage outcomes section.
