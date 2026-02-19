<?php

/**
 * College Sports Management System
 * Premium Edit User - Local File Upload Integration
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Edit User Settings';
$current_page = 'users';

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($user_id === 0) {
    setError('Access token required for modification');
    header('Location: manage_users.php');
    exit();
}

// Fetch user
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) {
    setError('Record not found');
    header('Location: manage_users.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $gender = $_POST['gender'];
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'];
    $status = $_POST['status'];
    $phone = sanitize($_POST['phone'] ?? '');

    $photo_path = $user['photo']; // Keep existing by default

    // Validation
    if (empty($full_name)) $errors[] = 'Full legal name is required';
    if (empty($username)) $errors[] = 'Username is required';
    if (!empty($username) && usernameExists($conn, $username, $user_id)) {
        $errors[] = 'Username already taken';
    }

    if (!empty($password)) {
        if (strlen($password) < 8) $errors[] = 'Password must be 8+ characters';
        if ($password !== $confirm_password) $errors[] = 'Passwords mismatch';
    }

    // Role safety
    if ($user_id == $_SESSION['user_id']) {
        if ($role !== $user['role']) $errors[] = 'Self-role modification denied';
        if ($status === 'inactive') $errors[] = 'Self-deactivation denied';
    }

    // Handle New Upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['profile_photo'], UPLOAD_PATH . '/users');
        if ($upload['success']) {
            $photo_path = $upload['filename'];

            // Optional: Delete old file if it wasn't a system avatar
            if ($user['photo'] && strpos($user['photo'], 'boy-') !== 0 && strpos($user['photo'], 'girl-') !== 0) {
                @unlink(UPLOAD_PATH . '/users/' . $user['photo']);
            }
        } else {
            $errors[] = 'Upload failed: ' . $upload['error'];
        }
    }

    if (empty($errors)) {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, gender = ?, password = ?, role = ?, status = ?, phone = ?, photo = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("sssssssssi", $full_name, $username, $email, $gender, $hashed, $role, $status, $phone, $photo_path, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, gender = ?, role = ?, status = ?, phone = ?, photo = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("ssssssssi", $full_name, $username, $email, $gender, $role, $status, $phone, $photo_path, $user_id);
        }

        if ($stmt->execute()) {
            logActivity($conn, 'update', 'users', $user_id, "Updated profile for: $username");
            setSuccess('Profile updated and synchronized.');
            header('Location: manage_users.php');
            exit();
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Update User Profile</h1>
            <p class="subtitle-text">Modify account details for <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>.</p>
        </div>
        <div class="header-actions">
            <a href="manage_users.php" class="btn-reset-light">
                Back to List
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form" enctype="multipart/form-data" id="edit-user-form">
                    <?php if (!empty($errors)): ?>
                        <div class="premium-alert error">
                            <div class="alert-icon">⚠️</div>
                            <div class="alert-msgs">
                                <?php foreach ($errors as $err): ?>
                                    <p><?php echo $err; ?></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-column">
                            <h3 class="form-section-title">User Information</h3>

                            <div class="premium-field">
                                <label class="field-label">Full Name</label>
                                <input type="text" name="full_name" class="premium-input" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Email Address</label>
                                <input type="email" name="email" class="premium-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Gender</label>
                                <div class="premium-radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="gender" value="male" <?php echo ($user['gender'] === 'male') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Male</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="gender" value="female" <?php echo ($user['gender'] === 'female') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Female</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="gender" value="other" <?php echo ($user['gender'] === 'other') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Other</span>
                                    </label>
                                </div>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Contact Number</label>
                                <input type="tel" name="phone" class="premium-input" value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Account Access</h3>

                            <div class="premium-field">
                                <label class="field-label">Username</label>
                                <input type="text" name="username" class="premium-input" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Role</label>
                                <select name="role" class="premium-select" <?php echo ($user_id == $_SESSION['user_id']) ? 'disabled' : ''; ?>>
                                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Administrator</option>
                                    <option value="staff" <?php echo ($user['role'] === 'staff') ? 'selected' : ''; ?>>Staff Member</option>
                                </select>
                                <?php if ($user_id == $_SESSION['user_id']): ?>
                                    <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                                <?php endif; ?>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Status</label>
                                <div class="premium-radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="active" <?php echo ($user['status'] === 'active') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Active</span>
                                    </label>
                                    <label class="radio-item <?php echo ($user_id == $_SESSION['user_id']) ? 'disabled' : ''; ?>">
                                        <input type="radio" name="status" value="inactive" <?php echo ($user['status'] === 'inactive') ? 'checked' : ''; ?> <?php echo ($user_id == $_SESSION['user_id']) ? 'disabled' : ''; ?>>
                                        <span class="radio-label">Inactive</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="premium-divider"></div>
                    <h3 class="form-section-title">Update Password <span class="title-meta">(Optional)</span></h3>
                    <div class="form-grid">
                        <div class="premium-field">
                            <label class="field-label">New Password</label>
                            <input type="password" name="password" class="premium-input" placeholder="••••••••">
                        </div>
                        <div class="premium-field">
                            <label class="field-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="premium-input" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 200px;">Save Changes</button>
                        <a href="manage_users.php" class="btn-reset-light" style="margin-left: 10px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Profile Photo</h3>
                <div class="profile-preview-box">
                    <div id="image-drop-area" class="upload-area">
                        <img src="<?php echo getUserPhoto($user['photo']); ?>" id="image-preview" class="preview-img-premium">
                        <div class="upload-overlay">
                            <div class="upload-icon">📸</div>
                            <p>Replace Profile Photo</p>
                        </div>
                        <input type="file" name="profile_photo" id="file-input" class="hidden-file-input" accept="image/*" form="edit-user-form">
                    </div>
                </div>
                <p class="subtitle-text mt-8" style="font-size:11px; opacity: 0.8">Currently hosted locally</p>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Account Metadata</h3>
                <div class="stats-mini">
                    <div class="mini-stat">
                        <span class="mini-label">Internal UID</span>
                        <span class="mini-value">#USR-0<?php echo $user['id']; ?></span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-label">Created On</span>
                        <span class="mini-value"><?php echo formatDate($user['created_at'], 'M d, Y'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .upload-area {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: 40px;
        overflow: hidden;
        cursor: pointer;
        border: 4px solid white;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .upload-area:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .preview-img-premium {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .upload-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .upload-area:hover .upload-overlay {
        opacity: 1;
    }

    .upload-icon {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .upload-overlay p {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .hidden-file-input {
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('image-drop-area');
        const fileInput = document.getElementById('file-input');
        const previewImg = document.getElementById('image-preview');

        dropArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => previewImg.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>