<?php

/**
 * Staff Settings - High Fidelity Layout (Admin Parity)
 */

require_once '../config.php';
requireLogin();

$page_title = 'Settings';
$current_page = 'settings';

$success_msg = '';
$error_msg = '';

// Get current user data
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $full_name = sanitize($_POST['full_name']);
        $email = sanitize($_POST['email']);
        $mobile = sanitize($_POST['mobile']);

        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, mobile = ? WHERE id = ?");
        $stmt->bind_param("sssi", $full_name, $email, $mobile, $user_id);

        if ($stmt->execute()) {
            setSuccess('Profile updated successfully');
            header("Location: settings.php");
            exit();
        } else {
            setError('Failed to update profile');
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
                    setSuccess('Password changed successfully');
                    header("Location: settings.php");
                    exit();
                } else {
                    setError('Failed to change password');
                }
                $stmt->close();
            } else {
                setError('New passwords do not match');
            }
        } else {
            setError('Current password is incorrect');
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
            <p class="subtitle-text">Manage your operational preferences and security</p>
        </div>
    </div>

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
                        <label class="field-label">Email Address</label>
                        <input type="email" name="email" class="premium-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="premium-field">
                        <label class="field-label">Mobile Number</label>
                        <input type="tel" name="mobile" class="premium-input" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>">
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" name="update_profile" class="btn-premium-search">Save Profile</button>
                    </div>
                </form>
            </div>

            <!-- Password Change -->
            <div class="glass-card">
                <h3 class="card-title mb-6">Security & Password</h3>
                <form method="POST" class="premium-form">
                    <div class="premium-field">
                        <label class="field-label">Current Password</label>
                        <input type="password" name="current_password" class="premium-input" required>
                    </div>

                    <div class="premium-field">
                        <label class="field-label">New Secure Password</label>
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
                <h3 class="field-label mb-6 block">Account Blueprint</h3>
                <div class="stats-mini">
                    <div class="mini-stat">
                        <span class="mini-label">Unique ID</span>
                        <span class="mini-value">STAFF-<?php echo str_pad($user['id'], 4, '0', STR_PAD_LEFT); ?></span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-label">Role Level</span>
                        <span class="mini-value"><?php echo strtoupper($user['role']); ?></span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-label">Account Bio</span>
                        <span class="mini-value" style="color: var(--neon-green);">VERIFIED PERSONNEL</span>
                    </div>
                </div>
            </div>

            <div class="bento-card mt-8" style="background: rgba(140, 0, 255, 0.05); border: 2px dashed rgba(140, 0, 255, 0.2);">
                <h3 class="meta-subtext" style="color: var(--elite-purple); font-weight: 800; margin-bottom: 15px;">SECURITY PROTOCOL</h3>
                <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                    Updating your credentials will be logged for audit purposes. Ensure your password is at least 8 characters long with mixing complex symbols.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>