<?php

/**
 * College Sports Management System
 * Premium Edit Player - Local File Upload Integration
 */

require_once '../config.php';
require_once '../includes/sport_list.php';
requireAdmin();

$page_title = 'Edit Player';
$current_page = 'players';

$player_id = intval($_GET['id'] ?? 0);

if (!$player_id) {
    setError('Invalid player selection');
    header('Location: manage_players.php');
    exit();
}

$player = $conn->query("SELECT * FROM players WHERE id = $player_id")->fetch_assoc();
if (!$player) {
    setError('Athlete record not found');
    header('Location: manage_players.php');
    exit();
}

// Get active sports for selection
$sports = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status = 'active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);
$player_sports = array_column($conn->query("SELECT sport_id FROM player_sports WHERE player_id = $player_id")->fetch_all(MYSQLI_ASSOC), 'sport_id');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $mobile = sanitize($_POST['mobile']);
    $email = sanitize($_POST['email']);
    $address = sanitize($_POST['address']);
    $year = $_POST['year'];
    $selected_sports = $_POST['sports'] ?? [];

    if (empty($name)) $errors[] = 'Name is required';
    if (empty($selected_sports)) $errors[] = 'At least one sport must be assigned';

    $photo_path = $player['photo']; // Preserve current

    // Handle New File Upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['profile_photo'], UPLOAD_PATH . '/players');
        if ($upload['success']) {
            $photo_path = $upload['filename'];

            // Delete old file if it wasn't a system avatar
            if ($player['photo'] && strpos($player['photo'], 'boy-') !== 0 && strpos($player['photo'], 'girl-') !== 0) {
                @unlink(UPLOAD_PATH . '/players/' . $player['photo']);
            }
        } else {
            $errors[] = 'Upload failed: ' . $upload['error'];
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE players SET name = ?, mobile = ?, email = ?, address = ?, year = ?, photo = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $mobile, $email, $address, $year, $photo_path, $player_id);

        if ($stmt->execute()) {
            // Update sports associations
            $conn->query("DELETE FROM player_sports WHERE player_id = $player_id");
            $sport_stmt = $conn->prepare("INSERT INTO player_sports (player_id, sport_id) VALUES (?, ?)");
            foreach ($selected_sports as $sport_id) {
                $sport_stmt->bind_param("ii", $player_id, $sport_id);
                $sport_stmt->execute();
            }
            $sport_stmt->close();

            logActivity($conn, 'update', 'players', $player_id, "Updated athlete profile: $name");
            setSuccess('Player profile and photo synchronized successfully.');
            header('Location: manage_players.php');
            exit();
        } else {
            $errors[] = 'Synchronization error: ' . $conn->error;
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
            <h1 class="welcome-text">Edit Player</h1>
            <p class="subtitle-text">Update player information for <strong><?php echo htmlspecialchars($player['name']); ?></strong></p>
        </div>
        <div class="header-actions">
            <a href="manage_players.php" class="btn-reset-light">
                Back to List
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form" enctype="multipart/form-data" id="edit-player-form">
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
                                <input type="text" name="name" class="premium-input" id="player_name_input" value="<?php echo htmlspecialchars($player['name']); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Mobile Number</label>
                                <input type="tel" name="mobile" class="premium-input" value="<?php echo htmlspecialchars($player['mobile']); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Email Address</label>
                                <input type="email" name="email" class="premium-input" value="<?php echo htmlspecialchars($player['email']); ?>">
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Academic Information</h3>

                            <div class="premium-field">
                                <label class="field-label">Year</label>
                                <div class="premium-radio-group">
                                    <?php $years = ['I', 'II', 'III', 'IV'];
                                    foreach ($years as $y): ?>
                                        <label class="radio-item">
                                            <input type="radio" name="year" value="<?php echo $y; ?>" <?php echo ($player['year'] === $y) ? 'checked' : ''; ?>>
                                            <span class="radio-label"><?php echo $y; ?> Year</span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Department</label>
                                <input type="text" class="premium-input" value="<?php echo htmlspecialchars($player['department']); ?>" disabled style="opacity: 0.6; background: #f1f5f9;">
                                <p style="font-size: 10px; color: var(--text-light); margin-top: 5px;">* Department cannot be changed</p>
                            </div>
                        </div>
                    </div>

                    <div class="premium-divider"></div>

                    <div class="form-grid">
                        <div class="form-column">
                            <h3 class="form-section-title">Sports</h3>

                            <div class="premium-field">
                                <label class="field-label">Select Sports</label>

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
                                            <input type="checkbox" name="sports[]" value="<?php echo $sport['id']; ?>" <?php echo in_array($sport['id'], $player_sports) ? 'checked' : ''; ?>>
                                            <div class="sport-check-box">
                                                <span class="sport-icon-mini" style="font-size: 18px; display: block; margin-bottom: 4px;"><?php echo $icon; ?></span>
                                                <span class="sport-name"><?php echo htmlspecialchars($sport['sport_name']); ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Address</h3>
                            <div class="premium-field">
                                <label class="field-label">Home Address</label>
                                <textarea name="address" class="premium-input" rows="4" style="resize: none;"><?php echo htmlspecialchars($player['address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 200px;">Save Changes</button>
                        <a href="manage_players.php" class="btn-reset-light" style="margin-left: 10px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Profile Photo</h3>
                <div class="profile-preview-box">
                    <div id="image-drop-area" class="upload-area">
                        <img src="<?php echo getPlayerPhoto($player['id'], $player['photo'], $player['gender']); ?>" id="image-preview" class="preview-img-premium">
                        <div class="upload-overlay">
                            <div class="upload-icon">📸</div>
                            <p>Change Photo</p>
                        </div>
                        <input type="file" name="profile_photo" id="file-input" class="hidden-file-input" accept="image/*" form="edit-player-form">
                    </div>
                </div>
                <p class="subtitle-text mt-8" style="font-size:12px; line-height: 1.6; opacity: 0.8" id="player-name-preview">
                    Profile for <br><strong><?php echo htmlspecialchars($player['name']); ?></strong>
                </p>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Player Information</h3>
                <div class="stats-mini">
                    <div class="mini-stat">
                        <span class="mini-label">Gender</span>
                        <span class="mini-value" style="color: var(--primary-color)"><?php echo strtoupper($player['gender']); ?></span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-label">ID Number</span>
                        <span class="mini-value"><?php echo $player['register_number']; ?></span>
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

    .sports-selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 12px;
        max-height: 250px;
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
        padding: 12px;
        border-radius: 14px;
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
        const nameInput = document.getElementById('player_name_input');
        const namePreview = document.getElementById('player-name-preview');

        dropArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => previewImg.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });

        nameInput.addEventListener('input', function() {
            namePreview.innerHTML = this.value ? `Active Profile for <br><strong>${this.value}</strong>` : "Profile identification data update in progress.";
        });

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