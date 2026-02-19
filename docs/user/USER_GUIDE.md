# College Sports Management System - User Guide

## Table of Contents
1. [Getting Started](#getting-started)
2. [Login & Navigation](#login--navigation)
3. [User Management](#user-management)
4. [Sports Management](#sports-management)
5. [Player Management](#player-management)
6. [Team Management](#team-management)
7. [Match Management](#match-management)
8. [Performance & Analytics](#performance--analytics)
9. [Reports & Certificates](#reports--certificates)
10. [Troubleshooting](#troubleshooting)

---

## Getting Started

### System Requirements
- XAMPP (Apache + MySQL)
- Modern web browser (Chrome, Firefox, Edge)
- Minimum 2GB RAM
- 500MB disk space

### Installation Steps

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache and MySQL services

2. **Create Database**
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Create new database: `sports_management`
   - Import file: `database/sports_management.sql`

3. **Access System**
   - Open browser: `http://localhost/COLLEGE-SPORTS-MANAGEMENT-SYSTEM/`
   - Login with default credentials:
     - **Username:** `admin`
     - **Password:** `password`

4. **First-Time Setup**
   - Change default admin password
   - Add sports categories
   - Create additional users if needed

---

## Login & Navigation

### Logging In
1. Enter username and password
2. Click "Login" button
3. System redirects based on role:
   - **Admin** → Admin Dashboard
   - **Staff** → Staff Dashboard

### Dashboard Overview
- **Statistics Cards:** Quick view of system metrics
- **Sidebar Menu:** Navigate to different modules
- **User Menu:** Profile, settings, logout

### Logging Out
- Click your name in top-right corner
- Select "Logout" from dropdown menu

---

## User Management
📁 **Location:** Admin Panel → Users

### Adding a New User
1. Click "**+ Add New User**"
2. Fill in required fields:
   - Full Name
   - Username (4-20 characters)
   - Email
   - Password (minimum 8 characters)
   - Role (Admin/Staff)
   - Status (Active/Inactive)
3. Optional: Upload profile photo
4. Click "**Create User**"

### Editing Users
1. Click "✏️ **Edit**" button next to user
2. Modify desired fields
3. Click "**Update User**"

**Note:** You cannot:
- Change your own role
- Deactivate your own account
- Delete the last admin user

### Searching Users
- Use search box to find by name, username, or email
- Filter by role (Admin/Staff)
- Filter by status (Active/Inactive)

---

## Sports Management
📁 **Location:** Admin Panel → Sports

### Adding a Sport
1. Click "**+ Add New Sport**"
2. Enter sport details:
   - Sport Name
   - Category Type (Team/Individual/Both)
   - Minimum Players
   - Maximum Players
   - Description
3. Click "**Create Sport**"

### Managing Sports
- View all sports in grid layout
- See player count and team count per sport
- Edit or delete sports
- **Note:** Cannot delete if players/teams are assigned

---

## Player Management
📁 **Location:** Admin Panel → Players

### Registering a New Player

1. Click "**+ Add New Player**"
2. **Personal Information:**
   - Full Name
   - Registration Number
   - Date of Birth (age calculated automatically)
   - Gender
   - Blood Group

3. **Academic Details:**
   - Department
   - Year (I, II, III, IV)

4. **Contact Information:**
   - Mobile Number
   - Email
   - Address
   - Emergency Contact

5. **Sports Selection:**
   - Check all sports the player participates in
   - Must select at least one sport

6. Click "**Register Player**"

### Viewing Player Profile
1. Click "👁️ **View**" next to player
2. See complete profile:
   - Personal information
   - Registered sports
   - Team assignments
   - Match history

### Searching Players
- Search by name or registration number
- Filter by:
  - Sport
  - Department
  - Year
  - Status

### Editing Players
1. Click "✏️ **Edit**" button
2. Modify information
3. Update sport assignments
4. Click "**Update Player**"

---

## Team Management
📁 **Location:** Admin Panel → Teams

### Creating a Team
1. Click "**+ Create New Team**"
2. Enter team details:
   - Team Name
   - Select Sport
   - Coach Name (optional)
3. Click "**Create Team**"

### Assigning Players to Team
1. Click "👥 **Assign Players**" on team card
2. Select players from the list
   - Only shows players registered for that sport
3. Click "**Save Assignments**"

### Setting Team Captain
1. Click on team card or use "Team Roster" view
2. Select player from captain dropdown
3. Click "**Set as Captain**"
4. Captain is highlighted with 👑 crown icon

### Team Statistics
- **Matches Played:** Total games
- **Matches Won:** Victories count
- **Player Count:** Current roster size

---

## Match Management
📁 **Location:** Admin Panel → Matches

### Scheduling a Match

1. Click "**+ Schedule Match**"
2. **Match Details:**
   - Select Sport
   - Select Team 1
   - Select Team 2
   - Match Date
   - Match Time
   - Venue

3. **Conflict Detection:**
   - System checks for venue conflicts
   - System checks for team scheduling conflicts
   - Conflicts are shown with error messages

4. Click "**Schedule Match**"

### Calendar View
📁 **Location:** Admin Panel → Calendar

- View month-by-month schedule
- Navigate between months using arrows
- Matches color-coded by sport
- Today is highlighted
- Click match for details

### Editing Matches
1. Find match in list
2. Click "Edit" or on calendar
3. Modify date, time, or venue
4. System validates for conflicts
5. Click "**Update Match**"

### Entering Match Results
1. Find scheduled match
2. Click "**Enter Results**"
3. Enter scores for both teams
4. Select result status:
   - Final
   - Draw
   - Walkover
5. Add match notes (optional)
6. Click "**Submit Results**"

**Automatic Updates:**
- Winner determined automatically
- Team statistics updated (matches played/won)
- Player performance tracked

---

## Performance & Analytics
📁 **Location:** Admin Panel → Performance / Analytics

### Performance Tracking
View comprehensive statistics:
- **Top Performers:** 🥇🥈🥉 Top 10 players
- **Team Standings:** Win percentage rankings
- **Match Participation:** Player involvement

### Analytics Dashboard
Interactive visual charts:
- **Sport Distribution:** Pie chart showing players per sport
- **Match Trends:** Line graph of monthly activity
- **Team Performance:** Bar chart of team wins

**Charts Features:**
- Pure JavaScript (no external libraries)
- Fully responsive
- Interactive hover effects

### Player Statistics
- Complete player performance data
- Matches played
- Team assignments
- Sport participation

---

## Reports & Certificates
📁 **Location:** Admin Panel → Reports / Certificates

### Generating Reports
1. Navigate to "**Reports**"
2. View system-wide statistics:
   - Total players
   - Active teams
   - Completed matches
3. Sport-wise breakdown table
4. Export options:
   - Print Report
   - Export to Excel (button)
   - Generate PDF (button)

### Creating Certificates
1. Click "**Certificates**"
2. **Select Details:**
   - Player Name
   - Certificate Type:
     * Participation
     * Achievement
     * Winner
     * Runner-Up
   - Sport
   - Issue Date
   - Achievement Details (optional)

3. Click "**Generate Certificate**"
4. Preview appears with professional design
5. Click "🖨️ **Print Certificate**"

**Certificate Features:**
- Beautiful gradient design
- Player name prominently displayed
- Sport and achievement details
- Date and signature placeholders
- Print-ready format

---

## Troubleshooting

### Login Issues
**Problem:** Cannot login
- **Check:** Username and password correct
- **Check:** XAMPP MySQL is running
- **Check:** Database imported correctly
- **Reset:** Use phpMyAdmin to reset password

### Database Connection Error
**Problem:** "Connection failed" message
- **Check:** MySQL service in XAMPP is running
- **Check:** Database name is `sports_management`
- **Check:** `config.php` settings match your setup

### Page Not Loading
**Problem:** Blank page or errors
- **Check:** Apache service in XAMPP is running
- **Check:** PHP errors in XAMPP control panel logs
- **Check:** All files uploaded correctly

### Images Not Showing
**Problem:** Avatar images missing
- **Check:** `assets/images/Avatar/` folder exists
- **Check:** Image files present (boy-1.png through boy-7.png, girl-1.png through girl-4.png)
- **Check:** File permissions allow reading

### Search Not Working
**Problem:** Search returns no results
- **Clear browser cache**
- **Check:** JavaScript console for errors (F12)
- **Try:** Resetting filters

---

## Tips & Best Practices

### Data Entry
✅ **DO:**
- Use consistent naming conventions
- Fill all required fields
- Verify data before submitting
- Keep backup of important data

❌ **DON'T:**
- Delete records with dependencies
- Use special characters in names
- Leave critical fields empty

### Security
🔒 **Important:**
- Change default admin password immediately
- Use strong passwords (8+ characters, mix of letters/numbers)
- Logout when finished
- Create separate accounts for each user
- Regularly backup database

### Performance
⚡ **Optimization:**
- Close unused browser tabs
- Clear browser cache periodically
- Limit concurrent users during peak times
- Archive old data regularly

---

## Keyboard Shortcuts

| Key | Action |
|-----|--------|
| `Esc` | Close modal/dropdown |
| `Ctrl + F` | Quick search (browser) |
| `F5` | Refresh page |
| `Ctrl + P` | Print certificate/report |

---

## Support & Maintenance

### Regular Maintenance
**Daily:**
- Check for new players/matches
- Update match results
- Review pending items

**Weekly:**
- Generate reports
- Review team rosters
- Check system statistics

**Monthly:**
- Backup database
- Archive old data
- Update user accounts

### Database Backup
1. Open phpMyAdmin
2. Select `sports_management` database
3. Click "Export" tab
4. Choose "Quick" method
5. Click "Go"
6. Save `.sql` file with date

### Restoring Backup
1. Open phpMyAdmin
2. Select `sports_management` database
3. Click "Import" tab
4. Choose backup file
5. Click "Go"

---

## System Limits

| Item | Limit |
|------|-------|
| Users | Unlimited |
| Players | Unlimited |
| Teams per Sport | Unlimited |
| Matches per Day | Unlimited |
| Photo Size | 2MB |
| Username Length | 4-20 characters |
| Password Length | Minimum 8 characters |

---

## Quick Reference Card

### Common Tasks
```
🏃 Register Player    → Players → Add New Player
👥 Create Team        → Teams → Create New Team
📅 Schedule Match     → Matches → Schedule Match
📊 View Analytics     → Analytics & Graphs
🏅 Generate Certificate → Certificates
📄 Print Report       → Reports → Print
```

### Color Codes
- 🟢 **Green Badge:** Active/Completed/Success
- 🔴 **Red Badge:** Inactive/Cancelled/Danger
- 🟡 **Yellow Badge:** Scheduled/Warning
- 🔵 **Blue Badge:** Sport/Information

---

## Got Questions?

For additional help:
1. Check this user guide
2. Review the walkthrough.md file
3. Check database schema documentation
4. Contact system administrator

**System Version:** 1.0
**Last Updated:** January 2026

---

**Congratulations!** You're now ready to use the College Sports Management System effectively. Happy managing! 🏆
