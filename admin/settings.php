<?php

/**
 * College Sports Management System
 * Settings Page
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Settings';
$current_page = 'settings';

$success_msg = '';
$error_msg = '';

// Get current user data
$user_id = $_SESSION['user_id'];
$user_result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_result->fetch_assoc();

// Safety check for reseeded/deleted users
if (!$user) {
    header("Location: ../logout.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $full_name = sanitize($_POST['full_name']);
        $email = sanitize($_POST['email']);
        $mobile = sanitize($_POST['mobile']);

        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, mobile = ? WHERE id = ?");
        $stmt->bind_param("sssi", $full_name, $email, $mobile, $user_id);

        if ($stmt->execute()) {
            $success_msg = 'Profile updated successfully';
            $user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
        } else {
            $error_msg = 'Failed to update profile';
        }
        $stmt->close();
    }

    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $hashed_password, $user_id);

                if ($stmt->execute()) {
                    $success_msg = 'Password changed successfully';
                } else {
                    $error_msg = 'Failed to change password';
                }
                $stmt->close();
            } else {
                $error_msg = 'New passwords do not match';
            }
        } else {
            $error_msg = 'Current password is incorrect';
        }
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Settings</h1>
            <p class="subtitle-text">Manage your account settings and preferences</p>
        </div>
    </div>

    <?php if ($success_msg): ?>
        <div class="premium-alert success" style="margin-bottom: 20px;">
            <div class="alert-icon">✓</div>
            <div class="alert-msgs">
                <p><?php echo $success_msg; ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="premium-alert error" style="margin-bottom: 20px;">
            <div class="alert-icon">⚠️</div>
            <div class="alert-msgs">
                <p><?php echo $error_msg; ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="main-grid">
        <div class="charts-column">
            <!-- Profile Settings -->
            <div class="glass-card mb-8">
                <h3 class="card-title mb-6">Profile Information</h3>
                <form method="POST" class="premium-form">
                    <div class="premium-field">
                        <label class="field-label">Full Name</label>
                        <input type="text" name="full_name" class="premium-input" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>

                    <div class="premium-field">
                        <label class="field-label">Email</label>
                        <input type="email" name="email" class="premium-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="premium-field">
                        <label class="field-label">Mobile</label>
                        <input type="tel" name="mobile" class="premium-input" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>">
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" name="update_profile" class="btn-premium-search">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Password Change -->
            <div class="glass-card">
                <h3 class="card-title mb-6">Change Password</h3>
                <form method="POST" class="premium-form">
                    <div class="premium-field">
                        <label class="field-label">Current Password</label>
                        <input type="password" name="current_password" class="premium-input" required>
                    </div>

                    <div class="premium-field">
                        <label class="field-label">New Password</label>
                        <input type="password" name="new_password" class="premium-input" required>
                    </div>

                    <div class="premium-field">
                        <label class="field-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="premium-input" required>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" name="change_password" class="btn-premium-search">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card">
                <h3 class="field-label mb-6 block">Account Information</h3>
                <div class="stats-mini">
                    <div class="mini-stat">
                        <span class="mini-label">Username</span>
                        <span class="mini-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-label">Role</span>
                        <span class="mini-value"><?php echo ucfirst($user['role']); ?></span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-label">Status</span>
                        <span class="mini-value" style="color: var(--neon-green);">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>