# Theme Troubleshooting Guide

## 🚨 Theme Not Showing After Upload?

### Step 1: Verify Upload Success

#### Check if files are on server:
```bash
ssh merc22@merc22.ssh.wpengine.net "ls -la wp-content/themes/lauras-mercantile-hybrid-gpt/"
```

Expected output:
- style.css ✅
- header.php ✅  
- functions.php ✅
- index.php ✅
- assets/ folder ✅

#### Check theme activation:
```bash
ssh merc22@merc22.ssh.wpengine.net "wp theme list --status=lauras-mercantile-hybrid-gpt"
```

### Step 2: Check WordPress Theme Activation

#### Method 1: WordPress Admin
1. Go to: `https://laurasmercantile.com/wp-admin/`
2. Navigate: **Appearance → Themes**  
3. Look for: **"Laura's Mercantile Hybrid (GPT)"**
4. Click: **"Activate"** (not "Network Enable")

#### Method 2: WP-CLI
```bash
ssh merc22@merc22.ssh.wpengine.net "wp theme activate lauras-mercantile-hybrid-gpt"
```

### Step 3: Verify Promotional Banner

#### Check if JavaScript is loading:
```bash
ssh merc22@merc22.ssh.wpengine.net "grep -r 'promo-banner' wp-content/themes/lauras-mercantile-hybrid-gpt/header.php"
```

#### Clear all caches:
```bash
# Clear WP Engine cache
ssh merc22@merc22.ssh.wpengine.net "wp cache flush"

# Clear WordPress object cache (if using)
ssh merc22@merc22.ssh.wpengine.net "wp plugin list | grep -i cache"
```

### Step 4: Test Specific Files

#### Check logo size:
```bash
ssh merc22@merc22.ssh.wpengine.net "grep -n 'height.*120px' wp-content/themes/lauras-mercantile-hybrid-gpt/header.php"
```

#### Check CSS loading:
```bash
ssh merc22@merc22.ssh.wpengine.net "grep -n 'home-page.*banner' wp-content/themes/lauras-mercantile-hybrid-gpt/style.css"
```

### Step 5: Debug Mode (if still not working)

Add this to header.php for debug mode:
```php
<?php
// DEBUG MODE - Add to header.php after <?php wp_body_open(); ?>
<div id="debug-banner" style="background: red; color: white; padding: 10px; text-align: center;">DEBUG MODE ACTIVE</div>
```

### Common Issues & Solutions:

#### ❌ Theme not appearing:
- **Problem**: Wrong permissions
- **Fix**: `ssh merc22@merc22.ssh.wpengine.net "chmod -R 755 wp-content/themes/lauras-mercantile-hybrid-gpt"`

#### ❌ Banner not showing:
- **Problem**: CSS not loading  
- **Fix**: Check if CSS file exists and is enqueued

#### ❌ Logo still small:
- **Problem**: Browser cache
- **Fix**: Hard refresh (Ctrl+F5 or Cmd+Shift+R)

#### ❌ White screen:
- **Problem**: PHP syntax error
- **Fix**: Check WordPress debug logs

### Quick Tests:

1. **View source**: Check if banner HTML is in page source
2. **Browser console**: Look for JavaScript errors
3. **Network tab**: Check if CSS file loads (200 status)

### If All Else Fails:

#### Manual HTML injection:
Add this to theme's `functions.php`:
```php
function lauras_promo_banner() {
    if (is_front_page()) {
        echo '<div style="position: fixed; top: 0; left: 0; right: 0; background: #28a745; color: white; padding: 20px; text-align: center; font-weight: 700; z-index: 999999;">🍄 Take 20% off Functional Mushrooms and Functional Chocolates. No Coupon Needed. 🍄</div>';
    }
}
add_action('wp_head', 'lauras_promo_banner');
```

### Contact Support:
If theme activation fails completely, contact WP Engine support or use the **"Network Enable"** option instead of "Activate" in WordPress admin.