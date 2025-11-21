# 🚀 Storm Breaker V4 - Quick Start Guide

## What's Been Upgraded?

Your Storm Breaker has been enhanced with **Version 4.0** - a complete UI overhaul with powerful new features!

## 📁 New Files Created

```
storm-web/
├── panel-v4.php              ⚡ Enhanced main dashboard
├── login-v4.php              ⚡ Modern login page
├── index-selector.php        ⚡ Version chooser page
├── assets/js/
│   └── enhanced-features.js  ⚡ New features JavaScript
```

## 🎯 How to Access V4

### Method 1: Version Selector (Recommended)
```
http://localhost:2525/index-selector.php
```
- Beautiful page to choose between V3 and V4
- Shows feature comparison
- Easy navigation

### Method 2: Direct Access
```
http://localhost:2525/login-v4.php
```
- Login: admin
- Password: admin

### Method 3: Make V4 Default
Replace original files to make V4 your default:

**Windows (PowerShell):**
```powershell
cd "d:\10 day 10 webiste\Storm-Breaker\storm-web"
Copy-Item panel.php panel-v3-backup.php
Copy-Item login.php login-v3-backup.php
Copy-Item panel-v4.php panel.php
Copy-Item login-v4.php login.php
```

**Linux/Mac:**
```bash
cd storm-web
cp panel.php panel-v3-backup.php
cp login.php login-v3-backup.php
cp panel-v4.php panel.php
cp login-v4.php login.php
```

## ✨ New Features At a Glance

### 1. 📊 Statistics Dashboard
- **Active Links** - Real-time count of phishing templates
- **Total Logs** - Persistent log counter (saved in browser)
- **Session Time** - Live session duration timer
- **Recent Activity** - Last 5 seconds of events

### 2. 🌙 Dark Mode
- Click moon icon in header to toggle
- Automatically saves your preference
- Eye-friendly colors
- All elements adapt beautifully

### 3. 🔍 Smart Filtering
- Search logs by keyword
- Filter by type: Location, Images, Audio, Device Info
- Instant results
- Easy reset

### 4. 💾 Export Options
- **Download TXT** - Save as timestamped text file
- **Export JSON** - Structured data with metadata
- **Copy All** - One-click clipboard copy
- **Clear with Confirm** - Prevent accidents

### 5. 🎨 Modern UI
- Gradient backgrounds
- Card-based design
- Smooth animations
- Mobile responsive
- Professional look

## 🎮 Using V4

### Dashboard Overview
```
┌─────────────────────────────────────────────┐
│ 🌟 Storm Breaker V4      [Stats] [🌙] [Logout]│
├─────────────────────────────────────────────┤
│ [📊 Stats] [📊 Stats] [📊 Stats] [📊 Stats] │
├─────────────────────────────────────────────┤
│ 🔗 Generated Phishing Links                 │
│  • http://localhost:2525/templates/...      │
│  • http://localhost:2525/templates/...      │
├─────────────────────────────────────────────┤
│ 📝 Activity Logs                            │
│  [Search] [Filter] [🔍 Apply]               │
│  [Log Area]                                 │
│  [Stop] [Download] [JSON] [Clear] [Copy]   │
└─────────────────────────────────────────────┘
```

### Quick Actions
1. **Copy Link** - Click "Copy" button next to any link
2. **Toggle Listener** - Click "Stop/Start Listener" button
3. **Search Logs** - Type keyword, click "Filter"
4. **Export Data** - Click "Export JSON" for structured export
5. **Switch Theme** - Click moon/sun icon

## 🔧 Compatibility

✅ **Fully Compatible With:**
- All existing templates
- Original authentication
- V3 backup/restore
- All browsers
- Mobile devices

✅ **No Changes Needed To:**
- config.php
- receiver.php
- Templates
- Authentication system

## 📱 Mobile Experience

V4 is fully responsive:
- Touch-friendly buttons
- Stacked statistics cards
- Scrollable logs area
- Adaptive header
- Easy navigation

## 🎨 Customization

### Change Theme Colors
Edit in `panel-v4.php`:
```css
:root {
    --primary-color: #4a90e2;    /* Main blue */
    --success-color: #2ecc71;    /* Green */
    --warning-color: #f39c12;    /* Orange */
    --danger-color: #e74c3c;     /* Red */
}
```

### Adjust Session Timer
Edit in `assets/js/enhanced-features.js`:
```javascript
var sessionStartTime = new Date();
```

## 🐛 Troubleshooting

### "Page not found" error?
- Make sure you're accessing through PHP server
- Verify files are in `storm-web/` directory
- Check the server is running on port 2525

### Stats not updating?
- Hard refresh: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
- Clear browser cache
- Check browser console for errors

### Dark mode not saving?
- Enable cookies in browser
- Check localStorage is not blocked
- Try in different browser

### Links not showing?
- Verify templates exist in `/templates/` folder
- Check permissions on directories
- Ensure `list_templates.php` works

## 🎓 Pro Tips

1. **Export Regularly** - Save logs as JSON for better organization
2. **Use Filters** - Find specific data types quickly
3. **Dark Mode** - Easier on eyes during long sessions
4. **Mobile Testing** - Test phishing links on actual phones
5. **Clear Periodically** - Keep performance optimal

## 📊 Performance Tips

- Clear logs when they exceed 1000 lines
- Export before clearing for backup
- Use filters instead of searching manually
- Restart listener if stats seem frozen
- Keep browser tabs minimal

## 🔒 Security Reminders

- Change default password in `config.php`
- Don't expose to public internet without proper security
- Use only in controlled environments
- Keep logs confidential
- Regular security audits

## 📚 Documentation

- **Full Feature Guide**: `UPGRADE_NOTES.md`
- **Original Docs**: `README.md`
- **Configuration**: `config.php`

## 🎉 Features Comparison

| Feature | V3 | V4 |
|---------|----|----|
| Modern UI | ❌ | ✅ |
| Dark Mode | ❌ | ✅ |
| Statistics | ❌ | ✅ |
| Filtering | ❌ | ✅ |
| JSON Export | ❌ | ✅ |
| Session Timer | ❌ | ✅ |
| Mobile Optimized | ⚠️ | ✅ |
| Search Logs | ❌ | ✅ |
| Copy Functions | Basic | Advanced |
| Visual Status | ❌ | ✅ |

## 🚀 Next Steps

1. **Test V4**: Login and explore the new interface
2. **Compare**: Try both V3 and V4 to see differences
3. **Customize**: Adjust colors to your preference
4. **Export**: Try the new export features
5. **Share**: Show off the enhanced UI!

## 💡 Need Help?

- Check `UPGRADE_NOTES.md` for detailed feature docs
- See browser console (F12) for errors
- Original issues: https://github.com/ultrasecurity/Storm-Breaker/issues

## 🎊 Enjoy!

You now have a **professional-grade security testing platform** with:
- ⚡ Modern design
- 📊 Real-time analytics
- 🌙 Dark mode
- 🔍 Smart filtering
- 💾 Multiple export formats
- 📱 Mobile responsive

**Happy Testing! 🚀**

---

*Storm Breaker V4 Enhanced Edition - November 2025*
