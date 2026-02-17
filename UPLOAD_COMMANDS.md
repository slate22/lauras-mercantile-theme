# Theme Upload Commands

## Option 1: Using the theme package

### SFTP Upload (Most Reliable)
```bash
# Upload to WP Engine
sftp -o Port=2222 merc22@merc22.ssh.wpengine.net:/wp-content/themes/ ~/lauras-mercantile-theme/wp-content/themes/lauras-mercantile-hybrid-gpt.tar.gz

# Extract on server
ssh merc22@merc22.ssh.wpengine.net "cd wp-content/themes && tar -xzf lauras-mercantile-hybrid-gpt.tar.gz && rm lauras-mercantile-hybrid-gpt.tar.gz"
```

### Alternative: Direct File Upload
If SFTP doesn't work, you can upload individual files via SFTP:

1. Extract the tar.gz locally:
```bash
tar -xzf lauras-mercantile-hybrid-gpt.tar.gz
```

2. Upload the entire `lauras-mercantile-hybrid-gpt/` folder via SFTP client like:
   - FileZilla
   - Cyberduck
   - Transmit

3. Upload to: `/wp-content/themes/lauras-mercantile-hybrid-gpt/`

### Server Details from Deployment Config:
- **Production Host**: merc22.ssh.wpengine.net
- **Port**: 2222
- **User**: merc22
- **Path**: /wp-content/themes/

## After Upload:

1. **Activate Theme**: Go to WordPress Admin → Appearance → Themes → Activate "Laura's Mercantile Hybrid (GPT)"
2. **Clear Cache**: If using caching plugins, clear them
3. **Test**: Check laurasmercantile.com for:
   - Larger logo (120px)
   - Promotional banner on home page
   - Responsive design

## Troubleshooting:
If theme doesn't appear, check:
1. File permissions (should be 755 for folders, 644 for files)
2. WP Engine cache
3. WordPress cache via WP Engine admin panel