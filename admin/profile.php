<?php

/**
 * College Sports Management System
 * User Profile Page
 */

require_once '../config.php';
requireAdmin();

$page_title = 'My Profile';
$current_page = 'profile';

// Get current user data
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Get activity log
$activities = $conn->query("SELECT * FROM activity_log WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text"><?php echo htmlspecialchars($user['full_name']); ?></h1>
            <p class="subtitle-text">View your profile and recent activity</p>
        </div>
        <div class="header-actions">
            <a href="settings.php" class="elite-action-btn" style="text-decoration: none; padding: 12px 25px;">
                Edit Profile
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <div class="profile-avatar-box">
                    <?php
                    // Use same avatar logic as header for consistency
                    $user_photo = $user['photo'];
                    $boy_avatars = ['boy-1.png', 'boy-2.png', 'boy-3.png', 'boy-4.png', 'boy-5.png', 'boy-6.png', 'boy-7.png'];
                    $girl_avatars = ['girl-1.png', 'girl-2.png', 'girl-3.png', 'girl-4.png'];
                    $all_avatars = array_merge($boy_avatars, $girl_avatars);

                    if ($user_photo && in_array($user_photo, $all_avatars)) {
                        $avatar_file = $user_photo;
                    } else {
                        $avatar_file = $all_avatars[$user['id'] % count($all_avatars)];
                    }
                    $profile_avatar_path = '../assets/images/Avatar/' . $avatar_file;
                    ?>
                    <img src="<?php echo $profile_avatar_path; ?>" class="profile-avatar-main">
                    <div class="status-indicator active"></div>
                </div>

                <h2 class="meta-handle" style="font-size: 24px; margin-top: 20px;"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                <p class="meta-subtext" style="color: var(--primary-color); font-weight: 700;">@<?php echo htmlspecialchars($user['username']); ?></p>

                <div class="premium-divider"></div>

                <div class="contact-node-list">
                    <div class="contact-node">
                        <span class="c-label">Email</span>
                        <span class="c-val"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="contact-node">
                        <span class="c-label">Mobile</span>
                        <span class="c-val"><?php echo htmlspecialchars($user['mobile'] ?? '') ?: 'Not provided'; ?></span>
                    </div>
                    <div class="contact-node">
                        <span class="c-label">Role</span>
                        <span class="c-val"><?php echo ucfirst($user['role']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="charts-column">
            <div class="glass-card">
                <div class="card-header pb-6">
                    <h3 class="card-title">Recent Activity</h3>
                </div>

                <div class="activity-list">
                    <?php if (count($activities) > 0): ?>
                        <?php foreach ($activities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <?php
                                    $icon = '📝';
                                    if ($activity['action_type'] === 'create') $icon = '➕';
                                    if ($activity['action_type'] === 'update') $icon = '✏️';
                                    if ($activity['action_type'] === 'delete') $icon = '🗑️';
                                    if ($activity['action_type'] === 'login') $icon = '🔐';
                                    echo $icon;
                                    ?>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-desc"><?php echo htmlspecialchars($activity['description']); ?></p>
                                    <span class="activity-time"><?php echo time_elapsed_string($activity['created_at']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 60px; text-align: center;">
                            <p class="text-secondary">No recent activity</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-avatar-box {
        position: relative;
        display: inline-block;
        margin-bottom: 10px;
    }

    .profile-avatar-main {
        width: 160px;
        height: 160px;
        border-radius: 50px;
        object-fit: cover;
        border: 6px solid white;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        background: #f8fafc;
    }

    .status-indicator {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 4px solid white;
    }

    .status-indicator.active {
        background: var(--neon-green);
        box-shadow: 0 0 15px var(--neon-green);
    }

    .contact-node-list {
        text-align: left;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .contact-node .c-label {
        display: block;
        font-size: 10px;
        color: #94a3b8;
        font-weight: 700;
    }

    .contact-node .c-val {
        font-weight: 700;
        font-size: 14px;
        color: var(--slate-deep);
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 15px;
        border: 1px solid #eef2ff;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-desc {
        font-weight: 600;
        font-size: 14px;
        color: var(--slate-deep);
        margin-bottom: 4px;
    }

    .activity-time {
        font-size: 11px;
        color: #94a3b8;
    }
</style>

<?php include '../includes/footer.php'; ?>