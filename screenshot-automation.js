const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

// Configuration
const BASE_URL = 'http://localhost/COLLEGE-SPORTS-MANAGEMENT-SYSTEM/';
const SCREENSHOT_DIR = './assets/screenshots';
const VIEWPORT_DIR = path.join(SCREENSHOT_DIR, 'viewport');
const FULL_HEIGHT_DIR = path.join(SCREENSHOT_DIR, 'full-height');

// User credentials
const USERS = {
    admin: {
        username: 'admin',
        password: 'password',
        role: 'admin'
    },
    staff: {
        username: 'staff',
        password: 'password',
        role: 'staff'
    }
};

// Pages to capture for each role
const PAGES = {
    common: [
        { url: 'index.php', name: '01-login-page' }
    ],
    admin: [
        { url: 'admin/dashboard.php', name: '02-admin-dashboard' },
        { url: 'admin/manage_users.php', name: '03-admin-manage-users' },
        { url: 'admin/add_user.php', name: '04-admin-add-user' },
        { url: 'admin/manage_sports.php', name: '05-admin-manage-sports' },
        { url: 'admin/add_sport.php', name: '06-admin-add-sport' },
        { url: 'admin/manage_teams.php', name: '07-admin-manage-teams' },
        { url: 'admin/add_team.php', name: '08-admin-add-team' },
        { url: 'admin/team_roster.php', name: '09-admin-team-roster' },
        { url: 'admin/manage_players.php', name: '10-admin-manage-players' },
        { url: 'admin/add_player.php', name: '11-admin-add-player' },
        { url: 'admin/view_player.php?id=1', name: '12-admin-view-player' },
        { url: 'admin/manage_matches.php', name: '13-admin-manage-matches' },
        { url: 'admin/schedule_match.php', name: '14-admin-schedule-match' },
        { url: 'admin/view_results.php', name: '15-admin-view-results' },
        { url: 'admin/enter_results.php', name: '16-admin-enter-results' },
        { url: 'admin/reports.php', name: '17-admin-reports' },
        { url: 'admin/analytics.php', name: '18-admin-analytics' },
        { url: 'admin/performance_tracking.php', name: '19-admin-performance-tracking' },
        { url: 'admin/player_statistics.php', name: '20-admin-player-statistics' },
        { url: 'admin/calendar.php', name: '21-admin-calendar' },
        { url: 'admin/notifications.php', name: '22-admin-notifications' },
        { url: 'admin/generate_certificate.php', name: '23-admin-generate-certificate' },
        { url: 'admin/profile.php', name: '24-admin-profile' },
        { url: 'admin/settings.php', name: '25-admin-settings' }
    ],
    staff: [
        { url: 'staff/dashboard.php', name: '26-staff-dashboard' },
        { url: 'staff/view_teams.php', name: '27-staff-view-teams' },
        { url: 'staff/team_roster.php', name: '28-staff-team-roster' },
        { url: 'staff/view_players.php', name: '29-staff-view-players' },
        { url: 'staff/view_player.php?id=1', name: '30-staff-view-player' },
        { url: 'staff/view_matches.php', name: '31-staff-view-matches' },
        { url: 'staff/view_results.php', name: '32-staff-view-results' },
        { url: 'staff/enter_scores.php', name: '33-staff-enter-scores' },
        { url: 'staff/view_reports.php', name: '34-staff-view-reports' },
        { url: 'staff/generate_certificate.php', name: '35-staff-generate-certificate' },
        { url: 'staff/profile.php', name: '36-staff-profile' },
        { url: 'staff/settings.php', name: '37-staff-settings' }
    ]
};

// Create directories if they don't exist
function ensureDirectories() {
    [VIEWPORT_DIR, FULL_HEIGHT_DIR].forEach(dir => {
        if (!fs.existsSync(dir)) {
            fs.mkdirSync(dir, { recursive: true });
            console.log(`✓ Created directory: ${dir}`);
        }
    });
}

// Login function
async function login(page, username, password) {
    console.log(`\n🔐 Logging in as: ${username}`);
    
    await page.goto(BASE_URL + 'index.php', { waitUntil: 'networkidle2' });
    
    // Fill login form
    await page.type('input[name="username"]', username);
    await page.type('input[name="password"]', password);
    
    // Click login button
    await page.click('button[type="submit"]');
    
    // Wait for navigation
    await page.waitForNavigation({ waitUntil: 'networkidle2' });
    
    console.log(`✓ Successfully logged in as ${username}`);
}

// Logout function
async function logout(page) {
    console.log('\n🚪 Logging out...');
    await page.goto(BASE_URL + 'logout.php', { waitUntil: 'networkidle2' });
    console.log('✓ Successfully logged out');
}

// Capture screenshot function
async function captureScreenshot(page, url, name, type = 'viewport') {
    try {
        const fullUrl = BASE_URL + url;
        console.log(`  📸 Capturing: ${name} (${type})`);
        
        await page.goto(fullUrl, { 
            waitUntil: 'networkidle2',
            timeout: 30000 
        });
        
        // Wait a bit for any animations or dynamic content
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        const screenshotPath = path.join(
            type === 'viewport' ? VIEWPORT_DIR : FULL_HEIGHT_DIR,
            `${name}.png`
        );
        
        if (type === 'viewport') {
            // Viewport screenshot (1920x1080)
            await page.screenshot({
                path: screenshotPath,
                fullPage: false
            });
        } else {
            // Full height screenshot
            await page.screenshot({
                path: screenshotPath,
                fullPage: true
            });
        }
        
        console.log(`  ✓ Saved: ${screenshotPath}`);
        return true;
    } catch (error) {
        console.error(`  ✗ Error capturing ${name}: ${error.message}`);
        return false;
    }
}

// Main function
async function main() {
    console.log('🚀 Starting Screenshot Automation');
    console.log('================================\n');
    
    // Ensure directories exist
    ensureDirectories();
    
    // Launch browser
    const headlessMode = process.argv.includes('--headless');
    console.log(`Browser mode: ${headlessMode ? 'Headless' : 'Visible'}`);
    
    const browser = await puppeteer.launch({
        headless: headlessMode,
        defaultViewport: {
            width: 1920,
            height: 1080
        },
        args: ['--start-maximized', '--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    
    let stats = {
        total: 0,
        success: 0,
        failed: 0
    };
    
    try {
        // ==========================================
        // 1. CAPTURE LOGIN PAGE (No authentication)
        // ==========================================
        console.log('\n📋 CAPTURING COMMON PAGES');
        console.log('─────────────────────────');
        
        for (const pageInfo of PAGES.common) {
            stats.total += 2; // viewport + full-height
            
            // Viewport
            if (await captureScreenshot(page, pageInfo.url, pageInfo.name, 'viewport')) {
                stats.success++;
            } else {
                stats.failed++;
            }
            
            // Full height
            if (await captureScreenshot(page, pageInfo.url, pageInfo.name, 'full-height')) {
                stats.success++;
            } else {
                stats.failed++;
            }
        }
        
        // ==========================================
        // 2. CAPTURE ADMIN PAGES
        // ==========================================
        console.log('\n\n📋 CAPTURING ADMIN PAGES');
        console.log('─────────────────────────');
        
        await login(page, USERS.admin.username, USERS.admin.password);
        
        for (const pageInfo of PAGES.admin) {
            stats.total += 2;
            
            // Viewport
            if (await captureScreenshot(page, pageInfo.url, pageInfo.name, 'viewport')) {
                stats.success++;
            } else {
                stats.failed++;
            }
            
            // Full height
            if (await captureScreenshot(page, pageInfo.url, pageInfo.name, 'full-height')) {
                stats.success++;
            } else {
                stats.failed++;
            }
        }
        
        await logout(page);
        
        // ==========================================
        // 3. CAPTURE STAFF PAGES
        // ==========================================
        console.log('\n\n📋 CAPTURING STAFF PAGES');
        console.log('─────────────────────────');
        
        await login(page, USERS.staff.username, USERS.staff.password);
        
        for (const pageInfo of PAGES.staff) {
            stats.total += 2;
            
            // Viewport
            if (await captureScreenshot(page, pageInfo.url, pageInfo.name, 'viewport')) {
                stats.success++;
            } else {
                stats.failed++;
            }
            
            // Full height
            if (await captureScreenshot(page, pageInfo.url, pageInfo.name, 'full-height')) {
                stats.success++;
            } else {
                stats.failed++;
            }
        }
        
        await logout(page);
        
    } catch (error) {
        console.error('\n❌ Fatal error:', error);
    } finally {
        await browser.close();
    }
    
    // ==========================================
    // SUMMARY
    // ==========================================
    console.log('\n\n📊 SCREENSHOT SUMMARY');
    console.log('════════════════════════════════');
    console.log(`Total Screenshots: ${stats.total}`);
    console.log(`✓ Successful: ${stats.success}`);
    console.log(`✗ Failed: ${stats.failed}`);
    console.log(`\n📁 Screenshots saved in:`);
    console.log(`   • Viewport: ${VIEWPORT_DIR}`);
    console.log(`   • Full Height: ${FULL_HEIGHT_DIR}`);
    console.log('\n✅ Screenshot automation completed!\n');
}

// Run the script
main().catch(console.error);
