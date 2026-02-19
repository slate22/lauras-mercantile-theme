# Implementation Plan - Shop Sorting & Top Bar Links

The objective is to move Turmeric products to the middle of the shop grid and add a top bar with two specific links (Military and Loyalty programs).

## Proposed Changes

### 1. Product Sorting (functions.php)

- Update the `posts_orderby` filter logic.
- **New Priority Order:**
  - **10:** Functional Mushrooms (High priority)
  - **15:** Joe Tippens Protocol Products (High priority, moved up from 30)
  - **20:** CBD Products and Bundles (Main line)
  - **30:** Turmeric Products (Middle priority, moved down from 20)
  - **40:** All other products (Miscellaneous)
- This ensures Turmeric appears after the most specialized medical/wellness products but before the generic items, effectively placing them in the "middle" of the curated experience.

### 2. Header Enhancement (header.php)

- Add a `<div class="lm-top-bar">` above the `<header class="lm-header">`.
- Add two links:
  - **Military Discount** (`/military/`)
  - **Loyalty Program** (`/loyalty/`)
- Ensure these are displayed in a clean, minimal top bar as hinted by the `.top-bar` CSS class already present in the stylesheet.
- Add a new menu location `top_bar` in `functions.php` to allow future CMS control, but provide these as hardcoded defaults if no menu is assigned.

### 3. Styling (style.css)

- Refine the `.top-bar` styles to support the two links (flexbox, alignment).
- Ensure the promo banner and top bar work well together.

## Verification Plan

### Automated Tests

- N/A (Manual visual verification required)

### Manual Verification

1. **Shop Sorting:** Visit `/shop/` and verify the sequence of products:
    - First: Mushrooms
    - Second: Joe Tippens products
    - Third: CBD line
    - Fourth: Turmeric items
    - Fifth: Others
2. **Top Bar:** Verify the top bar appears at the very top of the page on all templates.
3. **Links:** Click the Military and Loyalty links to ensure they go to the correct pages (as defined in `App.jsx` routes).
