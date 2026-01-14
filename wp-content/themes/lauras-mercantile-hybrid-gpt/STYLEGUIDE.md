# Laura's Mercantile Theme Style Guide

## 🎨 Color Palette

### Primary Colors

- **Forest Green**: `#2c3e23` (`--color-primary`)
- **Gold Accent**: `#d4a574` (`--color-accent`)

### Context

- **Product Cards**: `#fdfaf4` background
- **Buttons**: `#2c3e23` background, white text

## Typography

### Fonts

- **Serif** (Headings): `'Libre Baskerville', serif`
- **Sans-serif** (Body): `'Montserrat', sans-serif`

### Usage

- Headings (`h1`, `h2`, `h3`, `product_title`): Serif
- Body text, UI elements, Buttons: Sans-serif

## UI Components

### Buttons

- **Style**: Solid background, rounded corners (4px radius).
- **Default State**: Background `#2c3e23`, Text `white`, Uppercase, Semi-bold (600).
- **Hover State**: Darker green `#1a2515`.
- **Selector**: `.button`, `.lm-btn`, `.single_add_to_cart_button`

### Product Cards

- **Layout**: Clean card with image on top.
- **Background**: Off-white `#fdfaf4`.
- **Spacing**: 20px padding bottom.
- **Shadow**: Subtle on default, lifted on hover.
- **Border**: Thin `#eee`.

## Layout Principles

### Shop Grid

- **Columns**:
  - Desktop: 4 columns
  - Tablet: 3 or 2 columns
  - Mobile: 1 column
- **Gap**: 30px spacing between cards.
- **Alignment**: Buttons align at the bottom of cards.

### Single Product Page

- **Structure**: Two-column layout (Left: Image, Right: Details).
- **Image Sizing**: Controlled max-width (480px) to prevent oversized images.
- **Typology**: Large serif title, clear pricing.
