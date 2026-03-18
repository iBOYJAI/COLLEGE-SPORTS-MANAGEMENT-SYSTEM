<?php

/**
 * College Sports Management System
 * Premium Edit Sport - Local File Upload Integration
 */

require_once '../config.php';
require_once '../includes/sport_list.php';
requireAdmin();

$page_title = 'Update Sport Discipline';
$current_page = 'sports';

$sport_id = intval($_GET['id'] ?? 0);
if (!$sport_id) {
    setError('Invalid sport identity parameter');
    header('Location: manage_sports.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM sports_categories WHERE id = ?");
$stmt->bind_param("i", $sport_id);
$stmt->execute();
$sport = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$sport) {
    setError('Sport node not found');
    header('Location: manage_sports.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sport_name = sanitize($_POST['sport_name']);
    $icon = $_POST['selected_icon'] ?? '🏆';
    $description = sanitize($_POST['description']);
    $category_type = $_POST['category_type'];
    $min_players = intval($_POST['min_players']);
    $max_players = intval($_POST['max_players']);
    $status = $_POST['status'];

    $image_path = $sport['image']; // Preserve current

    if (empty($sport_name)) $errors[] = 'Sport name is required';

    // Handle New File Upload
    if (isset($_FILES['sport_image']) && $_FILES['sport_image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['sport_image'], UPLOAD_PATH . '/sports');
        if ($upload['success']) {
            $image_path = $upload['filename'];

            // Delete old file if exists
            if ($sport['image']) {
                @unlink(UPLOAD_PATH . '/sports/' . $sport['image']);
            }
        } else {
            $errors[] = 'Upload failed: ' . $upload['error'];
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE sports_categories SET sport_name = ?, icon = ?, description = ?, category_type = ?, min_players = ?, max_players = ?, status = ?, image = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssssiissi", $sport_name, $icon, $description, $category_type, $min_players, $max_players, $status, $image_path, $sport_id);

        if ($stmt->execute()) {
            logActivity($conn, 'update', 'sports_categories', $sport_id, "Refined sport branding: $sport_name");
            setSuccess('Sport discipline and branding refined successfully.');
            header('Location: manage_sports.php');
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
            <h1 class="welcome-text">Update Sport</h1>
            <p class="subtitle-text">Update branding and settings for <strong><?php echo htmlspecialchars($sport['sport_name']); ?></strong>.</p>
        </div>
        <div class="header-actions">
            <a href="manage_sports.php" class="btn-reset-light">
                Back to Sport List
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form" enctype="multipart/form-data" id="edit-sport-form">
                    <input type="hidden" name="selected_icon" id="final-icon-input" value="<?php echo htmlspecialchars($sport['icon'] ?? '🏆'); ?>">

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
                            <h3 class="form-section-title">Sport Information</h3>

                            <div class="premium-field">
                                <label class="field-label">Sport Identity (Name)</label>
                                <input type="text" name="sport_name" class="premium-input" id="sport_name_input" value="<?php echo htmlspecialchars($sport['sport_name']); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Sport Type</label>
                                <select name="category_type" class="premium-select">
                                    <option value="team" <?php echo ($sport['category_type'] === 'team') ? 'selected' : ''; ?>>Team Sport</option>
                                    <option value="individual" <?php echo ($sport['category_type'] === 'individual') ? 'selected' : ''; ?>>Individual Sport</option>
                                    <option value="both" <?php echo ($sport['category_type'] === 'both') ? 'selected' : ''; ?>>Both (Hybrid)</option>
                                </select>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Status</label>
                                <div class="premium-radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="active" <?php echo ($sport['status'] === 'active') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Active</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="inactive" <?php echo ($sport['status'] === 'inactive') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Inactive</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Participation Limits</h3>

                            <div class="form-grid">
                                <div class="premium-field">
                                    <label class="field-label">Min Squad</label>
                                    <input type="number" name="min_players" class="premium-input" min="1" value="<?php echo $sport['min_players']; ?>" required>
                                </div>

                                <div class="premium-field">
                                    <label class="field-label">Max Squad</label>
                                    <input type="number" name="max_players" class="premium-input" min="1" value="<?php echo $sport['max_players']; ?>" required>
                                </div>
                            </div>

                            <div class="premium-field" style="margin-top: 10px;">
                                <label class="field-label">Brief Narrative</label>
                                <textarea name="description" class="premium-input" rows="3" style="resize: none;"><?php echo htmlspecialchars($sport['description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="premium-divider"></div>

                    <h3 class="form-section-title">Icon Settings</h3>
                    <div class="icon-selector-grid">
                        <?php foreach ($sport_registry as $item): ?>
                            <?php $isActive = ($item['icon'] === ($sport['icon'] ?? '🏆')) ? 'active' : ''; ?>
                            <div class="icon-option <?php echo $isActive; ?>" onclick="selectSportIcon('<?php echo $item['icon']; ?>', this)">
                                <?php echo $item['icon']; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 200px;">Save Changes</button>
                        <a href="manage_sports.php" class="btn-reset-light" style="margin-left: 10px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Visual Identity</h3>
                <div class="sport-preview-box">
                    <div id="image-drop-area" class="upload-area">
                        <?php
                        $brand = getSportIcon($sport);
                        if ($brand['type'] === 'image'): ?>
                            <div id="sport-icon-preview" style="font-size: 80px; width: 100%; height: 100%; display:none; align-items:center; justify-content:center;">🏆</div>
                            <img src="<?php echo $brand['value']; ?>" id="image-preview" class="preview-img-premium">
                        <?php else: ?>
                            <div id="sport-icon-preview" style="font-size: 80px; width: 100%; height: 100%; display:flex; align-items:center; justify-content:center;"><?php echo $brand['value']; ?></div>
                            <img src="" id="image-preview" class="preview-img-premium" style="display: none;">
                        <?php endif; ?>

                        <div class="upload-overlay">
                            <div class="upload-icon">📁</div>
                            <p>Replace Branding Asset</p>
                        </div>
                        <input type="file" name="sport_image" id="file-input" class="hidden-file-input" accept="image/*" form="edit-sport-form">
                    </div>
                </div>
                <p class="subtitle-text mt-8" style="font-size:12px; line-height: 1.6; opacity: 0.8" id="sport-name-label">
                    Active representative for <br><strong><?php echo htmlspecialchars($sport['sport_name']); ?></strong>
                </p>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Asset Policy</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        Branding assets are hosted locally. When a new image is uploaded, it becomes the primary identity for this category across the platform.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .upload-area {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto;
        border-radius: 40px;
        overflow: hidden;
        cursor: pointer;
        border: 4px solid white;
        background: #f8fafc;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .upload-area:hover {
        transform: translateY(-5px);
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

    .hidden-file-input {
        display: none;
    }

    .icon-selector-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
        gap: 10px;
        max-height: 200px;
        overflow-y: auto;
        padding: 15px;
        background: #f8fafc;
        border-radius: 20px;
    }

    .icon-option {
        font-size: 24px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .icon-option.active {
        border-color: var(--primary-color);
        background: var(--primary-lighter);
        transform: scale(1.1);
    }
</style>

<script>
    function selectSportIcon(emoji, element) {
        document.getElementById('final-icon-input').value = emoji;
        const iconDiv = document.getElementById('sport-icon-preview');
        const imgPreview = document.getElementById('image-preview');

        // Hide image if showing emoji
        imgPreview.style.display = 'none';
        iconDiv.style.display = 'flex';
        iconDiv.innerText = emoji;

        document.querySelectorAll('.icon-option').forEach(opt => opt.classList.remove('active'));
        element.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('image-drop-area');
        const fileInput = document.getElementById('file-input');
        const previewImg = document.getElementById('image-preview');
        const iconDiv = document.getElementById('sport-icon-preview');

        dropArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    iconDiv.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('sport_name_input').addEventListener('input', function() {
            document.getElementById('sport-name-label').innerHTML = this.value ? `Active representative for <br><strong>${this.value}</strong>` : "The system will show a preview of your custom branding.";
        });
    });
</script>

<?php include '../includes/footer.php'; ?>