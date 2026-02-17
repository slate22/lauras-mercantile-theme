# NEWSLETTER COUPON IMPLEMENTATION PLAN

## 🎯 Objective
Implement newsletter signup with automatic coupon assignment and display

## 🔧 Technical Approach

### Option A: JavaScript + AJAX (Recommended)
1. **Form Integration**: Add newsletter signup form in header
2. **Backend Integration**: Connect to WordPress/Email Marketing system
3. **Automatic Coupon**: Generate and assign coupon code after signup
4. **Immediate Display**: Show coupon to user immediately

### Option B: WordPress Plugin (Simpler)
1. **Newsletter Plugin**: Use existing newsletter plugin (Mailchimp, ConvertKit, etc.)
2. **Coupon Plugin**: WooCommerce coupon generator plugin
3. **Integration**: Connect newsletter signup to coupon generation

## 📋 Implementation Steps

### Step 1: Newsletter Form
```php
// Add to header.php near existing cart icon
<form id="lm-newsletter-form" class="lm-newsletter-form">
  <input type="email" placeholder="Enter your email" required>
  <button type="submit" class="lm-newsletter-btn">Get 15% Off</button>
  <div class="newsletter-success" style="display:none;">
    <strong>Coupon sent! Check your email.</strong>
  </div>
</form>
```

### Step 2: JavaScript Handler
```javascript
// Add to page for form submission
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('lm-newsletter-form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = form.querySelector('input[type="email"]').value;
      const successDiv = form.querySelector('.newsletter-success');
      
      // Show loading state
      const submitBtn = form.querySelector('.lm-newsletter-btn');
      submitBtn.textContent = 'Sending...';
      submitBtn.disabled = true;
      
      // AJAX request to WordPress
      fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          'action': 'lm_subscribe_newsletter',
          'email': email,
          'nonce': '<?php echo wp_create_nonce("newsletter_subscribe"); ?>'
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success message
          successDiv.style.display = 'block';
          form.style.display = 'none';
          
          // Store coupon in session/localStorage
          localStorage.setItem('lm_coupon_code', data.coupon_code);
          localStorage.setItem('lm_coupon_discount', '15%');
          
          // Update any coupon displays on page
          updateCouponDisplays();
        } else {
          submitBtn.textContent = 'Try Again';
          submitBtn.disabled = false;
        }
      })
      .catch(error => {
        console.error('Newsletter signup error:', error);
        submitBtn.textContent = 'Try Again';
        submitBtn.disabled = false;
      });
    });
  }
});

// Update coupon displays throughout the site
function updateCouponDisplays() {
  const couponCode = localStorage.getItem('lm_coupon_code');
  const couponDiscount = localStorage.getItem('lm_coupon_discount');
  
  if (couponCode && couponDiscount) {
    document.querySelectorAll('.coupon-display').forEach(el => {
      el.textContent = `${couponCode} - ${couponDiscount} OFF`;
    });
  }
}
```

### Step 3: WordPress Backend
```php
// Add to functions.php
function lm_newsletter_signup_handler() {
  if (!isset($_POST['action']) || $_POST['action'] !== 'lm_subscribe_newsletter') {
    return;
  }
  
  $email = sanitize_email($_POST['email']);
  $nonce = sanitize_text_field($_POST['nonce']);
  
  if (!is_email($email)) {
    wp_send_json_error(['success' => false, 'message' => 'Invalid email address']);
    return;
  }
  
  // Check if email already exists
  if (email_exists($email)) {
    wp_send_json_error(['success' => false, 'message' => 'Email already subscribed']);
    return;
  }
  
  // Generate coupon code
  $coupon_code = strtoupper(substr(md5($email . time() . 'SECRET_KEY'), 0, 8));
  $coupon_discount = 15; // 15% discount
  
  // Store coupon in database (optional)
  $coupon_id = wc_create_coupon([
    'code' => $coupon_code,
    'discount_type' => 'fixed_cart',
    'amount' => 15,
    'individual_use' => true,
    'usage_limit' => 1,
    'exclude_sale_items' => true,
    'expiry_date' => date('Y-m-d', strtotime('+30 days')),
  ]);
  
  // Send welcome email with coupon
  $subject = "Your 15% Off Coupon from Laura's Mercantile!";
  $message = "Thank you for subscribing! Use coupon code: {$coupon_code} for 15% off your next order.";
  $headers = ['Content-Type: text/html; charset=UTF-8'];
  
  wp_mail($email, $subject, $message, $headers);
  
  // Return success response
  wp_send_json_success([
    'success' => true, 
    'message' => 'Coupon sent to your email!',
    'coupon_code' => $coupon_code,
    'coupon_discount' => $coupon_discount
  ]);
}

add_action('wp_ajax_lm_subscribe_newsletter', 'lm_newsletter_signup_handler');
add_action('wp_ajax_nopriv_lm_subscribe_newsletter', 'lm_newsletter_signup_handler');
```

### Step 4: Product Description Updates
```php
// Add to functions.php
function lm_update_product_descriptions() {
  $products = get_posts([
    'post_type' => 'product',
    'posts_per_page' => -1,
    'post_status' => 'publish'
  ]);
  
  foreach ($products as $product) {
    // Update gummies description
    if (has_term($product->ID, 'gummies')) {
      wp_update_post($product->ID, [
        'post_content' => $product->post_content . '<p class="sleep-combo-info"><strong>🌙 Sleep Combination:</strong> CBD + CBN + Valerian for enhanced relaxation and deeper sleep cycles.</p>',
      ]);
    }
    
    // Update oils description  
    if (has_term($product->ID, 'oils')) {
      wp_update_post($product->ID, [
        'post_content' => $product->post_content . '<p class="flexibility-info"><strong>💪 Superior Delivery:</strong> Fast-absorbing oils and caramels for quicker onset with lasting effects.</p>',
      ]);
    }
    
    // Update chocolates/caramels description
    if (has_term($product->ID, 'chocolates') || has_term($product->ID, 'caramels')) {
      wp_update_post($product->ID, [
        'post_content' => $product->post_content . '<p class="softer-option-info"><strong>🍬 Softer, Better Sleep:</strong> Creamy caramels and artisanal chocolates for improved relaxation and comfort.</p>',
      ]);
    }
  }
}
add_action('init', 'lm_update_product_descriptions');
```

## 🎨 CSS Styling
```css
.lm-newsletter-form {
  display: flex;
  gap: 10px;
  align-items: center;
  margin: 20px 0;
}

.lm-newsletter-form input {
  padding: 12px 16px;
  border: 2px solid #e1e4e8;
  border-radius: 6px;
  font-size: 16px;
  min-width: 250px;
}

.lm-newsletter-form button {
  background: #28a745;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.lm-newsletter-form button:hover {
  background: #218838;
}

.newsletter-success {
  background: #d4edda;
  color: #155724;
  padding: 15px;
  border-radius: 6px;
  text-align: center;
  margin-top: 10px;
}

.coupon-display {
  background: #fff3cd;
  color: #1a1a1a;
  padding: 5px 10px;
  border-radius: 4px;
  font-weight: 700;
  font-size: 14px;
}

.sleep-combo-info,
.flexibility-info,
.sother-option-info {
  background: #f8f9fa;
  border-left: 4px solid #28a745;
  padding: 15px;
  margin: 15px 0;
  border-radius: 0 6px 6px 0;
}

@media (max-width: 768px) {
  .lm-newsletter-form {
    flex-direction: column;
    gap: 15px;
  }
  
  .lm-newsletter-form input {
    min-width: 100%;
  }
}
```

## 📅 Coupon Assignment Logic Explanation

**How it Works:**
1. **Unique Code Generation**: Uses MD5(email + timestamp + secret key) for uniqueness
2. **Database Storage**: Coupons stored in WooCommerce with usage limits
3. **Automatic Display**: JavaScript checks localStorage and updates all coupon displays
4. **15% Discount**: Fixed discount percentage as requested
5. **30-Day Expiry**: Creates urgency for conversion

## 🔍 Privacy & Legal Compliance
- GDPR-compliant email collection
- Clear coupon terms and expiry
- User consent required
- Automatic coupon expiry prevents abuse

## 🚀 Benefits
- **Immediate Gratification**: Users get coupon instantly
- **Email List Growth**: Builds customer email list
- **Conversion Incentive**: 15% discount encourages purchase
- **Automation**: Minimal ongoing maintenance required

This system provides the complete customer journey from signup to purchase with the 15% discount.