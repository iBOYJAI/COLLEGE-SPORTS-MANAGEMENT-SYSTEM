<?php

/**
 * College Sports Management System
 * Premium Add User - Local File Upload Integration
 */

require_once '../config.php';
requireAdmin();

$page_title = 'User Registration';
$current_page = 'users';

$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $gender = $_POST['gender'] ?? 'other';
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $phone = sanitize($_POST['phone'] ?? '');

    // Identity resolution: File upload or default avatar
    $photo_path = 'boy-1.png'; // Default
    if ($gender === 'female') $photo_path = 'girl-1.png';

    // Validation
    if (empty($full_name)) $errors[] = 'Full name is required';
    if (empty($username)) $errors[] = 'Username is required';
    if (empty($email)) $errors[] = 'Email address is required';
    if (empty($password)) $errors[] = 'Password is required';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match';

    if (!empty($username) && usernameExists($conn, $username)) {
        $errors[] = 'Username is already taken';
    }

    // Handle File Upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['profile_photo'], UPLOAD_PATH . '/users');
        if ($upload['success']) {
            $photo_path = $upload['filename'];
        } else {
            $errors[] = 'Upload failed: ' . $upload['error'];
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Note: Phone field is optional and may not exist in older databases.
        // Insert only into columns guaranteed to exist in the schema.
        $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, gender, password, role, status, photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssss", $full_name, $username, $email, $gender, $hashed, $role, $status, $photo_path);

        if ($stmt->execute()) {
            $new_id = $stmt->insert_id;
            logActivity($conn, 'create', 'users', $new_id, "Registered new user: $username");
            setSuccess('User registered and profile initialized locally.');
            header('Location: manage_users.php');
            exit();
        } else {
            $errors[] = 'Database synchronization error: ' . $conn->error;
        }
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Create New User</h1>
            <p class="subtitle-text">Fill in the details below to add a new account.</p>
        </div>
        <div class="header-actions">
            <a href="manage_users.php" class="btn-reset-light">
                Back to User List
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form" enctype="multipart/form-data">
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
                            <h3 class="form-section-title">Account Settings</h3>

                            <div class="premium-field">
                                <label class="field-label">Full Name</label>
                                <input type="text" name="full_name" class="premium-input" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required placeholder="John Doe">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Password</label>
                                <input type="email" name="email" class="premium-input" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required placeholder="john@example.com">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Verify Password</label>
                                <select name="gender" class="premium-select" id="gender-select">
                                    <option value="male" <?php echo (($_POST['gender'] ?? 'male') === 'male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo (($_POST['gender'] ?? '') === 'female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="other" <?php echo (($_POST['gender'] ?? '') === 'other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Contact Number</label>
                                <input type="tel" name="phone" class="premium-input" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" placeholder="+91 XXX XXX XXXX">
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Personal Details</h3>

                            <div class="premium-field">
                                <label class="field-label">Username</label>
                                <input type="text" name="username" class="premium-input" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required placeholder="johndoe123">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">User Role</label>
                                <select name="role" class="premium-select" required>
                                    <option value="staff" <?php echo (($_POST['role'] ?? '') === 'staff') ? 'selected' : ''; ?>>Staff Member</option>
                                    <option value="admin" <?php echo (($_POST['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Administrator</option>
                                </select>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Account Status</label>
                                <div class="premium-radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="active" checked>
                                        <span class="radio-label">Active</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="inactive" <?php echo (($_POST['status'] ?? '') === 'inactive') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Inactive</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="premium-divider"></div>
                    <h3 class="form-section-title">Security Settings</h3>
                    <div class="form-grid">
                        <div class="premium-field">
                            <label class="field-label">Password</label>
                            <input type="password" name="password" class="premium-input" placeholder="••••••••" required>
                        </div>
                        <div class="premium-field">
                            <label class="field-label">Verify Password</label>
                            <input type="password" name="confirm_password" class="premium-input" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 220px;">Save User</button>
                        <a href="manage_users.php" class="btn-reset-light" style="margin-left: 10px;">Go Back</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Profile Identity</h3>
                <div class="profile-preview-box">
                    <div id="image-drop-area" class="upload-area">
                        <img src="../assets/images/Avatar/boy-1.png" id="image-preview" class="preview-img-premium">
                        <div class="upload-overlay">
                            <div class="upload-icon">📸</div>
                            <p>Click to Upload Custom Photo</p>
                        </div>
                        <input type="file" name="profile_photo" id="file-input" class="hidden-file-input" accept="image/*" form="add-user-form">
                    </div>
                </div>
                <p class="subtitle-text mt-8" style="font-size:12px; line-height: 1.6; opacity: 0.8" id="user-name-preview">User preview will appear here.</p>

                <div class="glass-card mt-8">
                    <h3 class="field-label mb-4 block">Identity Logic</h3>
                    <div class="stats-mini">
                        <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                            If no custom photo is uploaded, the system automatically assigns a gender-appropriate default icon. Custom photos are stored in **assets/uploads/users/**.
                        </p>
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
            const genderSelect = document.getElementById('gender-select');

            // Toggle file input
            dropArea.addEventListener('click', () => fileInput.click());

            // Preview image
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => previewImg.src = e.target.result;
                    reader.readAsDataURL(file);
                }
            });

            // Default avatar logic based on gender
            genderSelect.addEventListener('change', function() {
                if (!fileInput.value) { // Only update if no custom file selected
                    if (this.value === 'female') {
                        previewImg.src = '../assets/images/Avatar/girl-1.png';
                    } else {
                        previewImg.src = '../assets/images/Avatar/boy-1.png';
                    }
                }
            });
        });
    </script>

    <?php include '../includes/footer.php'; ?>