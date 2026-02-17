# DIRECT UPLOAD INSTRUCTIONS

## 📁 THEME PACKAGE LOCATION
```
/Users/slate22/antigravity/lauras-mercantile-theme/lauras-mercantile-theme/lauras-mercantile-hybrid-gpt.tar.gz
```

## 🚀 MANUAL UPLOAD STEPS

### STEP 1: UPLOAD VIA SFTP/FTP
1. Open FileZilla, Cyberduck, or similar FTP client
2. Connect to: `merc22.ssh.wpengine.net`
3. Port: `2222`
4. Username: `merc22`
5. Navigate to: `/wp-content/themes/`
6. Upload: `lauras-mercantile-hybrid-gpt.tar.gz`

### STEP 2: EXTRACT ON SERVER
1. Right-click the uploaded `.tar.gz` file
2. Select "Extract" or "Extract Here"
3. This creates: `/wp-content/themes/lauras-mercantile-hybrid-gpt/`
4. Delete the `.tar.gz` file

### STEP 3: ACTIVATE THEME
1. Go to: `https://laurasmercantile.com/wp-admin/`
2. Navigate: **Appearance → Themes**
3. Find: **"Laura's Mercantile Hybrid (GPT)"**
4. Click: **"Activate"**

### STEP 4: VERIFY
1. Check home page for larger logo (120px height)
2. Look for green promotional banner at top
3. Test responsive on mobile

## 🔍 ALTERNATIVE: WP ENGINE FILE MANAGER

If SFTP fails, use WP Engine admin:
1. Go to: WP Engine user portal
2. Use file manager to upload
3. Upload to: `/wp-content/themes/`

## ✅ EXPECTED RESULTS

After activation:
- ✅ Logo: 42% larger (120px vs 84px)
- ✅ Banner: "🍄 Take 20% off Functional Mushrooms and Functional Chocolates. No Coupon Needed. 🍄"
- ✅ Mobile: Responsive design working
- ✅ Checkout: Improved layout and readability

## 📞 IF THEME DOESN'T APPEAR

1. **Check file permissions**: SSH in and run `chmod -R 755 wp-content/themes/lauras-mercantile-hybrid-gpt/`
2. **Clear WordPress cache**: In WP Engine admin panel
3. **Clear browser cache**: Ctrl+F5 or Cmd+Shift+R
4. **Check theme status**: Run SSH command `wp theme list --status=lauras-mercantile-hybrid-gpt`

## 🎯 READY TO UPLOAD

Your theme package with all improvements is ready!
- File size: 36MB
- Contains: Logo changes, promotional banner, responsive design
- Location: Listed at top of this guide

Proceed with Step 1 to upload to your server.