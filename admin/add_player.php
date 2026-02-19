<?php

/**
 * College Sports Management System
 * Premium Player Registration - Local File Upload Integration
 */

require_once '../config.php';
require_once '../includes/sport_list.php';
requireAdmin();

$page_title = 'Player Registration';
$current_page = 'players';

// Get active sports for selection
$sports = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status = 'active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $register_number = sanitize($_POST['register_number']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $department = sanitize($_POST['department']);
    $year = $_POST['year'];
    $mobile = sanitize($_POST['mobile']);
    $email = sanitize($_POST['email']);
    $address = sanitize($_POST['address']);
    $blood_group = sanitize($_POST['blood_group']);
    $emergency_contact = sanitize($_POST['emergency_contact']);
    $selected_sports = $_POST['sports'] ?? [];

    if (empty($name)) $errors[] = 'Name is required';
    if (empty($register_number)) $errors[] = 'Registration number is required';
    if (empty($dob)) $errors[] = 'Date of birth is required';
    if (empty($selected_sports)) $errors[] = 'Select at least one sport';

    // Check unique registration number
    $check = $conn->prepare("SELECT id FROM players WHERE register_number = ?");
    $check->bind_param("s", $register_number);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $errors[] = 'Registration number already exists';
    }
    $check->close();

    // Photo selection logic
    $photo_path = ''; // Handled by getPlayerPhoto default logic if empty

    // Handle File Upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['profile_photo'], UPLOAD_PATH . '/players');
        if ($upload['success']) {
            $photo_path = $upload['filename'];
        } else {
            $errors[] = 'Upload failed: ' . $upload['error'];
        }
    }

    if (empty($errors)) {
        $age = date_diff(date_create($dob), date_create('now'))->y;

        $stmt = $conn->prepare("INSERT INTO players (name, register_number, dob, age, gender, department, year, mobile, email, address, blood_group, emergency_contact, photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssississssss", $name, $register_number, $dob, $age, $gender, $department, $year, $mobile, $email, $address, $blood_group, $emergency_contact, $photo_path);

        if ($stmt->execute()) {
            $player_id = $stmt->insert_id;

            // Assign sports
            $sport_stmt = $conn->prepare("INSERT INTO player_sports (player_id, sport_id) VALUES (?, ?)");
            foreach ($selected_sports as $sport_id) {
                $sport_stmt->bind_param("ii", $player_id, $sport_id);
                $sport_stmt->execute();
            }
            $sport_stmt->close();

            logActivity($conn, 'create', 'players', $player_id, "Registered player: $name");
            setSuccess('Player registered successfully with custom profile photo.');
            header('Location: manage_players.php');
            exit();
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
        $stmt->close();
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Add Player</h1>
            <p class="subtitle-text">Fill in the student-athlete details to register them.</p>
        </div>
        <div class="header-actions">
            <a href="manage_players.php" class="btn-reset-light">
                Back to Player List
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form" enctype="multipart/form-data" id="add-player-form">
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
                            <h3 class="form-section-title">Personal Information</h3>

                            <div class="premium-field">
                                <label class="field-label">Full Name</label>
                                <input type="text" name="name" class="premium-input" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required placeholder="e.g. John Doe">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Select Registered Sports</label>
                                <input type="date" name="dob" class="premium-input" id="dob_input" value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Gender</label>
                                <select name="gender" class="premium-select" id="gender-select" required>
                                    <option value="Male" <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Blood Group</label>
                                <select name="blood_group" class="premium-select">
                                    <option value="">Select Group</option>
                                    <?php $groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                    foreach ($groups as $g): ?>
                                        <option value="<?php echo $g; ?>" <?php echo ($_POST['blood_group'] ?? '') === $g ? 'selected' : ''; ?>><?php echo $g; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Academic Details</h3>

                            <div class="premium-field">
                                <label class="field-label">Department</label>
                                <input type="text" name="register_number" class="premium-input" value="<?php echo htmlspecialchars($_POST['register_number'] ?? ''); ?>" required placeholder="e.g. REG-123456">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Department</label>
                                <select name="department" class="premium-select" required>
                                    <?php
                                    $depts = ['Computer Science', 'Electronics', 'Mechanical', 'Civil', 'Information Technology', 'Electrical'];
                                    foreach ($depts as $d): ?>
                                        <option value="<?php echo $d; ?>" <?php echo ($_POST['department'] ?? '') === $d ? 'selected' : ''; ?>><?php echo $d; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Academic Year</label>
                                <div class="premium-radio-group">
                                    <?php $years = ['I', 'II', 'III', 'IV'];
                                    foreach ($years as $y): ?>
                                        <label class="radio-item">
                                            <input type="radio" name="year" value="<?php echo $y; ?>" <?php echo (($_POST['year'] ?? 'I') === $y) ? 'checked' : ''; ?>>
                                            <span class="radio-label"><?php echo $y; ?> Year</span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="premium-divider"></div>

                    <div class="form-grid">
                        <div class="form-column">
                            <h3 class="form-section-title">Contact & Location</h3>

                            <div class="premium-field">
                                <label class="field-label">Mobile Number</label>
                                <input type="tel" name="mobile" class="premium-input" value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>" required placeholder="+91 XXX XXX XXXX">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Email Address</label>
                                <input type="email" name="email" class="premium-input" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="john@example.com">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Permanent Address</label>
                                <textarea name="address" class="premium-input" rows="3" placeholder="Street, City, Pincode" style="resize: none;"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Sports Selection</h3>

                            <div class="premium-field">
                                <label class="field-label">Select Registered Sports</label>

                                <div class="sport-search-wrapper" style="margin-bottom: 12px; position: relative;">
                                    <input type="text" id="sport_search" class="premium-input-sm" placeholder="Search sports..." style="padding-left: 35px; height: 38px; font-size: 13px;">
                                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); opacity: 0.4;">🔍</span>
                                </div>

                                <div class="sports-selection-grid" id="sports_grid">
                                    <?php
                                    foreach ($sports as $sport):
                                        $icon = '🏆';
                                        foreach ($sport_registry as $sr) {
                                            if ($sr['name'] == $sport['sport_name']) {
                                                $icon = $sr['icon'];
                                                break;
                                            }
                                        }
                                    ?>
                                        <label class="sport-check-item" data-name="<?php echo strtolower($sport['sport_name']); ?>">
                                            <input type="checkbox" name="sports[]" value="<?php echo $sport['id']; ?>" <?php echo in_array($sport['id'], $_POST['sports'] ?? []) ? 'checked' : ''; ?>>
                                            <div class="sport-check-box">
                                                <span class="sport-icon-mini" style="font-size: 18px; display: block; margin-bottom: 4px;"><?php echo $icon; ?></span>
                                                <span class="sport-name"><?php echo htmlspecialchars($sport['sport_name']); ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Emergency Contact Info</label>
                                <textarea name="emergency_contact" class="premium-input" rows="2" placeholder="Jane Doe, Mother, +91 XXX XXX XXXX" style="resize: none;"><?php echo htmlspecialchars($_POST['emergency_contact'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 200px;">Save Player</button>
                        <a href="manage_players.php" class="btn-reset-light" style="margin-left: 10px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Player Identification</h3>
                <div class="profile-preview-box">
                    <div id="image-drop-area" class="upload-area">
                        <img src="../assets/images/Avatar/boy-1.png" id="image-preview" class="preview-img-premium">
                        <div class="upload-overlay">
                            <div class="upload-icon">📸</div>
                            <p>Upload Profile Photo</p>
                        </div>
                        <input type="file" name="profile_photo" id="file-input" class="hidden-file-input" accept="image/*" form="add-player-form">
                    </div>
                </div>
                <p class="subtitle-text mt-8" style="font-size:12px; line-height: 1.6; opacity: 0.8" id="player-name-preview">Identity data will reflect above.</p>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Registration Note</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        Custom photos are hosted locally at **assets/uploads/players/**. If no photo is provided, a system default will be used for rosters.
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

    .sports-selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
        max-height: 200px;
        overflow-y: auto;
        padding: 5px;
    }

    .sport-check-item {
        position: relative;
        cursor: pointer;
    }

    .sport-check-item input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .sport-check-box {
        background: white;
        border: 1px solid #eef2ff;
        padding: 10px;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s;
    }

    .sport-check-item input:checked+.sport-check-box {
        background: var(--primary-lighter);
        border-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(140, 0, 255, 0.1);
    }

    .sport-name {
        font-size: 11px;
        font-weight: 700;
        color: var(--slate-deep);
    }

    .sport-check-item input:checked+.sport-check-box .sport-name {
        color: var(--primary-color);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('image-drop-area');
        const fileInput = document.getElementById('file-input');
        const previewImg = document.getElementById('image-preview');
        const genderSelect = document.getElementById('gender-select');
        const nameInput = document.querySelector('input[name="name"]');
        const namePreview = document.getElementById('player-name-preview');

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

        // Name preview
        nameInput.addEventListener('input', function() {
            namePreview.innerHTML = this.value ? `Registration for <br><strong>${this.value}</strong>` : "Identity data will reflect above.";
        });

        // Default avatar logic
        genderSelect.addEventListener('change', function() {
            if (!fileInput.value) {
                if (this.value === 'Female') {
                    previewImg.src = '../assets/images/Avatar/girl-1.png';
                } else {
                    previewImg.src = '../assets/images/Avatar/boy-1.png';
                }
            }
        });

        // Sport Search Logic
        const sportSearch = document.getElementById('sport_search');
        const sportItems = document.querySelectorAll('.sport-check-item');

        sportSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            sportItems.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>