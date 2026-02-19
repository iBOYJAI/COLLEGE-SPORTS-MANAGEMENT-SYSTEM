<?php

/**
 * College Sports Management System
 * Sidebar Navigation Component - Refined Tooltip Edition
 */
?>
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="dashboard.php" class="sidebar-logo">
            🏆 <span>Sports MS</span>
        </a>
    </div>

    <!-- Sidebar Menu -->
    <nav class="sidebar-menu">
        <?php if ($current_user['role'] === 'admin'): ?>
            <!-- Admin Menu -->
            <div class="menu-section">
                <h6 class="menu-section-title">Main</h6>
                <a href="dashboard.php" class="menu-item <?php echo ($current_page === 'dashboard') ? 'active' : ''; ?>" data-tooltip="Dashboard">
                    <img src="<?php echo $icons['dashboard']; ?>" alt="Dashboard" class="menu-icon-img">
                    <span class="menu-text">Dashboard</span>
                </a>
            </div>

            <div class="menu-section">
                <h6 class="menu-section-title">Management</h6>
                <a href="manage_users.php" class="menu-item <?php echo ($current_page === 'users') ? 'active' : ''; ?>" data-tooltip="User Management">
                    <img src="<?php echo $icons['users']; ?>" alt="Users" class="menu-icon-img">
                    <span class="menu-text">User Management</span>
                </a>
                <a href="manage_sports.php" class="menu-item <?php echo ($current_page === 'sports') ? 'active' : ''; ?>" data-tooltip="Sports Categories">
                    <img src="<?php echo $icons['sports']; ?>" alt="Sports" class="menu-icon-img">
                    <span class="menu-text">Sports Categories</span>
                </a>
                <a href="manage_players.php" class="menu-item <?php echo ($current_page === 'players') ? 'active' : ''; ?>" data-tooltip="Player Management">
                    <img src="<?php echo $icons['players']; ?>" alt="Players" class="menu-icon-img">
                    <span class="menu-text">Player Management</span>
                </a>
                <a href="manage_teams.php" class="menu-item <?php echo ($current_page === 'teams') ? 'active' : ''; ?>" data-tooltip="Team Management">
                    <img src="<?php echo $icons['teams']; ?>" alt="Teams" class="menu-icon-img">
                    <span class="menu-text">Team Management</span>
                </a>
            </div>

            <div class="menu-section">
                <h6 class="menu-section-title">Matches</h6>
                <a href="manage_matches.php" class="menu-item <?php echo ($current_page === 'matches') ? 'active' : ''; ?>" data-tooltip="Match Schedule">
                    <img src="<?php echo $icons['matches']; ?>" alt="Matches" class="menu-icon-img">
                    <span class="menu-text">Match Schedule</span>
                </a>
                <a href="enter_results.php" class="menu-item <?php echo ($current_page === 'results') ? 'active' : ''; ?>" data-tooltip="Enter Results">
                    <img src="<?php echo $icons['results']; ?>" alt="Results" class="menu-icon-img">
                    <span class="menu-text">Enter Results</span>
                </a>
            </div>

            <div class="menu-section">
                <h6 class="menu-section-title">Analytics</h6>
                <a href="performance_tracking.php" class="menu-item <?php echo ($current_page === 'performance') ? 'active' : ''; ?>" data-tooltip="Performance Tracking">
                    <img src="<?php echo $icons['performance']; ?>" alt="Performance" class="menu-icon-img">
                    <span class="menu-text">Performance Tracking</span>
                </a>
                <a href="player_statistics.php" class="menu-item <?php echo ($current_page === 'statistics') ? 'active' : ''; ?>" data-tooltip="Player Statistics">
                    <img src="<?php echo $icons['statistics']; ?>" alt="Statistics" class="menu-icon-img">
                    <span class="menu-text">Player Statistics</span>
                </a>
            </div>

            <div class="menu-section">
                <h6 class="menu-section-title">Reports</h6>
                <a href="reports.php" class="menu-item <?php echo ($current_page === 'reports') ? 'active' : ''; ?>" data-tooltip="Generate Reports">
                    <img src="<?php echo $icons['reports']; ?>" alt="Reports" class="menu-icon-img">
                    <span class="menu-text">Generate Reports</span>
                </a>
                <a href="generate_certificate.php" class="menu-item <?php echo ($current_page === 'certificates') ? 'active' : ''; ?>" data-tooltip="Certificates">
                    <img src="<?php echo $icons['certificates']; ?>" alt="Certificates" class="menu-icon-img">
                    <span class="menu-text">Certificates</span>
                </a>
            </div>

        <?php else: ?>
            <!-- Staff Menu (Limited Access) -->
            <div class="menu-section">
                <h6 class="menu-section-title">Main</h6>
                <a href="dashboard.php" class="menu-item <?php echo ($current_page === 'dashboard') ? 'active' : ''; ?>" data-tooltip="Dashboard">
                    <img src="<?php echo $icons['dashboard']; ?>" alt="Dashboard" class="menu-icon-img">
                    <span class="menu-text">Dashboard</span>
                </a>
            </div>

            <div class="menu-section">
                <h6 class="menu-section-title">View</h6>
                <a href="view_matches.php" class="menu-item <?php echo ($current_page === 'matches') ? 'active' : ''; ?>" data-tooltip="View Matches">
                    <img src="<?php echo $icons['matches']; ?>" alt="Matches" class="menu-icon-img">
                    <span class="menu-text">View Matches</span>
                </a>
                <a href="view_players.php" class="menu-item <?php echo ($current_page === 'players') ? 'active' : ''; ?>" data-tooltip="View Players">
                    <img src="<?php echo $icons['players']; ?>" alt="Players" class="menu-icon-img">
                    <span class="menu-text">View Players</span>
                </a>
            </div>

            <div class="menu-section">
                <h6 class="menu-section-title">Actions</h6>
                <a href="enter_scores.php" class="menu-item <?php echo ($current_page === 'scores') ? 'active' : ''; ?>" data-tooltip="Enter Scores">
                    <img src="<?php echo $icons['results']; ?>" alt="Scores" class="menu-icon-img">
                    <span class="menu-text">Enter Scores</span>
                </a>
                <a href="view_reports.php" class="menu-item <?php echo ($current_page === 'reports') ? 'active' : ''; ?>" data-tooltip="View Reports">
                    <img src="<?php echo $icons['reports']; ?>" alt="Reports" class="menu-icon-img">
                    <span class="menu-text">View Reports</span>
                </a>
                <a href="generate_certificate.php" class="menu-item <?php echo ($current_page === 'certificates') ? 'active' : ''; ?>" data-tooltip="Certificates">
                    <img src="<?php echo $icons['certificates']; ?>" alt="Certificates" class="menu-icon-img">
                    <span class="menu-text">Certificates</span>
                </a>
            </div>
        <?php endif; ?>

        <div class="menu-section">
            <h6 class="menu-section-title">Account</h6>
            <a href="settings.php" class="menu-item <?php echo ($current_page === 'settings') ? 'active' : ''; ?>" data-tooltip="Settings">
                <img src="<?php echo $icons['settings']; ?>" alt="Settings" class="menu-icon-img">
                <span class="menu-text">Settings</span>
            </a>
            <a href="../logout.php" class="menu-item logout-item" data-tooltip="Logout">
                <img src="<?php echo $icons['logout']; ?>" alt="Logout" class="menu-icon-img">
                <span class="menu-text">Logout</span>
            </a>
        </div>
    </nav>
</aside>

<div class="main-content" id="main-content">