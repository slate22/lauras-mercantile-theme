import React from 'react';

export default function ProductCard({ product }) {
  if (!product) return null;

  // Parse price - handle both string and object formats
  const priceDisplay = product.prices?.price || product.price || '';
  const salePrice = product.prices?.sale_price || product.sale_price;
  const regularPrice = product.prices?.regular_price || product.regular_price;
  
  // Format price for display
  const formatPrice = (price) => {
    if (!price) return '';
    // If it's already formatted (contains currency symbol), return as is
    if (typeof price === 'string' && (price.includes('$') || price.includes('£') || price.includes('€'))) {
      return price;
    }
    // Otherwise format as USD
    const numPrice = parseFloat(price) / 100; // WooCommerce Store API returns price in cents
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD'
    }).format(numPrice);
  };

  const onSale = salePrice && salePrice !== regularPrice;
  const image = product.images?.[0];
  
  return (
    <article className="lm-product-card">
      <a href={product.permalink} className="lm-product-link">
        <div className="lm-product-image">
          {image?.src ? (
            <img 
              src={image.src} 
              alt={image.alt || product.name}
              loading="lazy"
            />
          ) : (
            <div className="lm-product-placeholder">
              <span className="lm-product-icon">🌿</span>
            </div>
          )}
          
          {onSale && (
            <div className="lm-product-badge lm-product-badge-sale">
              Sale
            </div>
          )}
          
          {product.is_featured && (
            <div className="lm-product-badge lm-product-badge-featured">
              Featured
            </div>
          )}
        </div>
        
        <div className="lm-product-body">
          <h3 
            className="lm-product-name" 
            dangerouslySetInnerHTML={{ __html: product.name }}
          />
          
          {product.short_description && (
            <div 
              className="lm-product-excerpt"
              dangerouslySetInnerHTML={{ 
                __html: product.short_description.replace(/<[^>]*>/g, '').substring(0, 100) + '...'
              }}
            />
          )}
          
          <div className="lm-product-footer">
            <div className="lm-product-price">
              {onSale ? (
                <>
                  <span className="lm-product-price-sale">
                    {formatPrice(salePrice)}
                  </span>
                  <span className="lm-product-price-regular">
                    {formatPrice(regularPrice)}
                  </span>
                </>
              ) : (
                <span className="lm-product-price-current">
                  {formatPrice(priceDisplay || regularPrice)}
                </span>
              )}
            </div>
            
            <span className="lm-product-cta">
              View Product →
            </span>
          </div>
        </div>
      </a>
    </article>
  );
}
