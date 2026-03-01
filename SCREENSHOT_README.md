# 📸 Automated Screenshot Capture System

This automated system uses **Puppeteer** (Node.js library) to capture screenshots of all pages in the College Sports Management System for both **Admin** and **Staff** roles.

## 🎯 Features

- ✅ Automatic login/logout for different user roles
- ✅ Captures **all pages** for Admin and Staff roles
- ✅ Two screenshot formats:
  - **Viewport** (1920x1080) - Standard screen size
  - **Full-Height** - Complete page from top to bottom
- ✅ Organized folder structure with descriptive filenames
- ✅ Progress tracking and error handling
- ✅ Summary statistics after completion

## 📁 Directory Structure

```
assets/screenshots/
├── viewport/          # Standard viewport screenshots (1920x1080)
│   ├── 01-login-page.png
│   ├── 02-admin-dashboard.png
│   ├── 03-admin-manage-users.png
│   └── ...
└── full-height/       # Full page screenshots
    ├── 01-login-page.png
    ├── 02-admin-dashboard.png
    ├── 03-admin-manage-users.png
    └── ...
```

## 🚀 Quick Start

### Prerequisites

1. **XAMPP** must be running (Apache + MySQL)
2. **Node.js** installed (v14 or higher)
3. **Database** populated with test data
4. **Users** must exist:
   - Admin: `admin` / `password`
   - Staff: `staff` / `password`

### Installation

```bash
# Install dependencies (already done)
npm install
```

### Running the Script

```bash
# Run the screenshot automation
npm run screenshot

# OR directly with node
node screenshot-automation.js
```

## 📋 Pages Captured

### Common Pages (1 page)
- Login Page

### Admin Pages (23 pages)
1. Dashboard
2. Manage Users
3. Add User
4. Manage Sports
5. Add Sport
6. Manage Teams
7. Add Team
8. Team Roster
9. Manage Players
10. Add Player
11. Manage Matches
12. Schedule Match
13. View Results
14. Enter Results
15. Reports
16. Analytics
17. Performance Tracking
18. Player Statistics
19. Calendar
20. Notifications
21. Generate Certificate
22. Profile
23. Settings

### Staff Pages (11 pages)
1. Dashboard
2. View Teams
3. Team Roster
4. View Players
5. View Matches
6. View Results
7. Enter Scores
8. View Reports
9. Generate Certificate
10. Profile
11. Settings

**Total: 35 pages × 2 formats = 70 screenshots**

## ⚙️ Configuration

Edit `screenshot-automation.js` to customize:

```javascript
// Base URL
const BASE_URL = 'http://localhost/COLLEGE-SPORTS-MANAGEMENT-SYSTEM/';

// Screenshot directories
const VIEWPORT_DIR = './assets/screenshots/viewport';
const FULL_HEIGHT_DIR = './assets/screenshots/full-height';

// User credentials
const USERS = {
    admin: { username: 'admin', password: 'password' },
    staff: { username: 'staff', password: 'password' }
};

// Viewport size
defaultViewport: {
    width: 1920,
    height: 1080
}
```

## 🔧 Troubleshooting

### Browser doesn't close automatically
- The script runs in **non-headless mode** by default (you can see the browser)
- Change `headless: false` to `headless: true` in the script for background execution

### Screenshots are blank or incomplete
- Increase `waitForTimeout` value (default: 1000ms)
- Check if XAMPP is running
- Verify database has data

### Login fails
- Verify user credentials in the database
- Check if users are active (`status = 'active'`)
- Ensure BASE_URL matches your local setup

### Page not found errors
- Some pages might require specific data (e.g., player ID for view_player.php)
- Comment out problematic pages in the PAGES object

## 📊 Output

After completion, you'll see:

```
📊 SCREENSHOT SUMMARY
════════════════════════════════
Total Screenshots: 70
✓ Successful: 70
✗ Failed: 0

📁 Screenshots saved in:
   • Viewport: ./assets/screenshots/viewport
   • Full Height: ./assets/screenshots/full-height

✅ Screenshot automation completed!
```

## 🎨 Customization

### Add More Pages

Edit the `PAGES` object in `screenshot-automation.js`:

```javascript
const PAGES = {
    admin: [
        { url: 'admin/new_page.php', name: '25-admin-new-page' },
        // ... add more pages
    ]
};
```

### Change Screenshot Quality

```javascript
await page.screenshot({
    path: screenshotPath,
    fullPage: true,
    quality: 90,  // For JPEG (0-100)
    type: 'jpeg'  // 'png' or 'jpeg'
});
```

### Add Wait Conditions

```javascript
// Wait for specific element
await page.waitForSelector('.dashboard-stats', { timeout: 5000 });

// Wait for specific time
await page.waitForTimeout(2000);

// Wait for network to be idle
await page.goto(url, { waitUntil: 'networkidle0' });
```

## 📝 Notes

- Screenshots are saved with sequential numbering for easy organization
- The script automatically creates directories if they don't exist
- Failed screenshots are logged but don't stop the entire process
- Each page is captured twice (viewport + full-height)

## 🔒 Security Note

This script is for **development/documentation purposes only**. Do not use in production with real credentials.

## 📞 Support

If you encounter issues:
1. Check XAMPP is running
2. Verify Node.js version: `node --version`
3. Check Puppeteer installation: `npm list puppeteer`
4. Review error messages in console

---

**Happy Screenshot Capturing! 📸**
