# Laura's Mercantile Hybrid Theme - Enhanced Version

## ✅ This is a Complete WordPress Theme

This theme is ready to install via WordPress Admin → Appearance → Themes → Add New → Upload Theme.

### What's Included

This is your complete Laura's Mercantile hybrid theme WITH all the enhancements already integrated:

✅ Enhanced CSS design system
✅ Improved Shop page with category/product toggle
✅ Advanced Category pages with sorting
✅ Professional Search with dual search (products + content)
✅ Reusable ProductCard component
✅ All original theme functionality

### Installation

1. **WordPress Admin** → Appearance → Themes → Add New
2. Click **Upload Theme**
3. Choose this **lauras-mercantile-hybrid-enhanced.zip** file
4. Click **Install Now**
5. Click **Activate**

### After Installation

#### Build the React App (Required!)

The theme includes React components that need to be built:

```bash
# SSH into your server or use local development
cd /path/to/wordpress/wp-content/themes/lauras-mercantile-hybrid-enhanced

# Build React app
cd react-src
npm install
npm run build
```

#### Test the Enhanced Features

Visit these pages to see the improvements:
- `/shop` - Category/Product toggle, enhanced layout
- `/product-category/[any-category]` - Sorting options
- `/search?q=test` - Dual search functionality

### What's Enhanced

#### Shop Page
- Toggle between Categories and Products view
- Visual category cards with hover effects
- Shop features section (organic, tested, shipping)
- Professional loading states

#### Category Pages
- Breadcrumb navigation (Home › Shop › Category)
- Sort by: Default, Name, Price (Low to High / High to Low)
- Product count badges
- Enhanced product grid

#### Search Page
- Search both products AND content
- Grouped results display
- URL parameters for shareable searches
- Visual product cards in results

#### Product Display
- Reusable ProductCard component
- Sale and featured badges
- Proper price formatting
- Lazy loading images
- Smooth hover animations

#### Design System
- Extended color palette (terracotta, gold, sage)
- 4-level shadow system
- Responsive typography
- Consistent spacing
- Better accessibility

### Documentation

- **IMPROVEMENTS.md** - Full technical documentation
- **IMPLEMENTATION-GUIDE.md** - Detailed guide (for reference)

### Requirements

- WordPress 6.0+
- WooCommerce 8.0+
- Node.js 16+ (for building React)
- PHP 7.4+

### Troubleshooting

**Theme looks broken after install?**
You need to build the React app! See "After Installation" above.

**Products not showing?**
- Ensure WooCommerce is installed and activated
- Check WooCommerce → Settings → Advanced → REST API is enabled

**Styles not applying?**
- Clear browser cache
- Rebuild React: `cd react-src && npm run build`

### Support

For issues:
1. Check browser console for errors
2. Verify React build completed successfully
3. Review WordPress error logs
4. Check WooCommerce API is accessible

---

**Version**: Enhanced 2.0
**Original**: Laura's Mercantile Hybrid iter9
**Enhanced**: January 2026

Enjoy your upgraded theme! 🌿
