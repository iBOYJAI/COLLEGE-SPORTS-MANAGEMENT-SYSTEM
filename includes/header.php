<?php

/**
 * College Sports Management System
 * Header Component
 * Include this file at the top of each page
 */

// Ensure user is logged in
if (!isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

// Get user info from session
$current_user = [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['username'],
    'full_name' => $_SESSION['full_name'],
    'role' => $_SESSION['role']
];

// Get user photo from database
$photo_query = $conn->prepare("SELECT photo FROM users WHERE id = ?");
$photo_query->bind_param("i", $current_user['id']);
$photo_query->execute();
$photo_result = $photo_query->get_result();
$user_data = $photo_result->fetch_assoc();

// SESSION RECOVERY: Check if user actually exists in the database
// If a reseed occurred, the ID might still be in the session but gone from DB
if (!$user_data) {
    // Clear session and Force logout
    session_destroy();
    header('Location: ../index.php?error=session_expired');
    exit();
}

// Set avatar path logic
$user_photo = $user_data['photo'] ?? 'default-avatar.png';
$boy_avatars = ['boy-1.png', 'boy-2.png', 'boy-3.png', 'boy-4.png', 'boy-5.png', 'boy-6.png', 'boy-7.png'];
$girl_avatars = ['girl-1.png', 'girl-2.png', 'girl-3.png', 'girl-4.png'];
$all_avatars = array_merge($boy_avatars, $girl_avatars);

// Check if user has a valid system avatar selected
if ($user_photo && in_array($user_photo, $all_avatars)) {
    $avatar_file = $user_photo;
} else {
    // Fallback to ID-based assignment
    $avatar_file = $all_avatars[$current_user['id'] % count($all_avatars)];
}
$avatar_path = '../assets/images/Avatar/' . $avatar_file;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $page_title ?? 'Dashboard'; ?> - College Sports Management</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-medal.png">

    <!-- CSS Files - All Local, NO CDN -->
    <link rel="stylesheet" href="../assets/css/fonts.css">
    <link rel="stylesheet" href="../assets/css/variables.css">
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/premium.css">
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css_file): ?>
            <link rel="stylesheet" href="<?php echo $css_file; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <div class="app-wrapper">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="btn-toggle" id="sidebar-toggle">
                    ☰
                </button>
                <h2 class="header-title"><?php echo $page_title ?? 'Dashboard'; ?></h2>
            </div>

            <!-- Header Center: Live Search -->
            <div class="header-center">
                <div class="header-search">
                    <img src="<?php echo $icons['search']; ?>" alt="Search">
                    <input type="text" placeholder="Search players, teams..." id="global-search" autocomplete="off">

                    <!-- Search Suggestions Box -->
                    <div id="search-suggestions" class="search-suggestions-box"></div>
                </div>
            </div>

            <div class="header-right">
                <!-- Notifications -->
                <div class="header-action">
                    <div class="notification-trigger" id="notification-toggle">
                        <img src="<?php echo $icons['notification']; ?>" alt="Notifications" class="action-icon-header">
                        <span class="notification-badge" id="notif-count">0</span>
                    </div>

                    <div class="dropdown-menu notification-dropdown" id="notification-menu">
                        <div class="dropdown-header">
                            <span>System Notifications</span>
                            <span class="badge badge-primary" id="notif-label">NEW</span>
                        </div>
                        <div id="notification-list" class="notification-list-content">
                            <!-- Real-time notifications will load here -->
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo ($current_user['role'] === 'admin') ? 'reports.php' : 'view_reports.php'; ?>" class="dropdown-item text-center">View All Activities</a>
                    </div>
                </div>

                <!-- User Profile Pill -->
                <div class="dropdown">
                    <div class="user-profile-pill" id="user-menu-toggle">
                        <img src="<?php echo $avatar_path; ?>" alt="User Avatar" class="avatar-pill">
                        <span class="user-pill-name"><?php echo htmlspecialchars($current_user['full_name']); ?></span>
                        <span class="pill-arrow">▼</span>
                    </div>

                    <div class="dropdown-menu" id="user-dropdown">
                        <a href="profile.php" class="dropdown-item">
                            <img src="<?php echo $icons['users']; ?>" class="action-icon-sm" style="margin-right: 8px;">
                            My Profile
                        </a>
                        <a href="settings.php" class="dropdown-item">
                            <img src="<?php echo $icons['settings']; ?>" class="action-icon-sm" style="margin-right: 8px;">
                            Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="../logout.php" class="dropdown-item text-danger">
                            <img src="<?php echo $icons['logout']; ?>" class="action-icon-sm" style="margin-right: 8px; filter: invert(30%) sepia(80%) saturate(2000%) hue-rotate(340deg);">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>