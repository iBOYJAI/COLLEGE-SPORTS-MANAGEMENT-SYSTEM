<?php

/**
 * College Sports Management System
 * Premium Team Registration - Local File Upload Integration
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Create Team';
$current_page = 'teams';

// Get active sports for team category assignment
$query = "SELECT s.*, 
          (SELECT COUNT(*) FROM teams WHERE sport_id = s.id) as current_teams
          FROM sports_categories s 
          WHERE s.status = 'active' 
          ORDER BY s.sport_name";
$sports = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_name = sanitize($_POST['team_name']);
    $sport_id = intval($_POST['sport_id']);
    $coach_name = sanitize($_POST['coach_name'] ?? '');

    $logo_path = '';

    if (empty($team_name)) {
        $errors[] = 'Team name is required';
    }

    if ($sport_id <= 0) {
        $errors[] = 'Sport selection is required';
    }

    // Handle logo upload
    if (isset($_FILES['team_logo']) && $_FILES['team_logo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['team_logo'], UPLOAD_PATH . '/teams');
        if ($upload['success']) {
            $logo_path = $upload['filename'];
        } else {
            $errors[] = 'Logo upload failed: ' . $upload['error'];
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO teams (team_name, sport_id, coach_name, logo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $team_name, $sport_id, $coach_name, $logo_path);

        if ($stmt->execute()) {
            $team_id = $stmt->insert_id;
            logActivity($conn, 'create', 'teams', $team_id, "Registered team: $team_name");
            setSuccess('Team registered successfully');
            header('Location: manage_teams.php');
            exit();
        } else {
            $errors[] = 'Failed to register team: ' . $conn->error;
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
            <h1 class="welcome-text">Add Team</h1>
            <p class="subtitle-text">Create a new sports squad and set its branding.</p>
        </div>
        <div class="header-actions">
            <a href="manage_teams.php" class="btn-reset-light">
                Back to Team List
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form" enctype="multipart/form-data" id="add-team-form">
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
                            <h3 class="form-section-title">Team Information</h3>

                            <div class="premium-field">
                                <label class="field-label">Team Identity (Name)</label>
                                <input type="text" name="team_name" class="premium-input" id="team_name_input" value="<?php echo htmlspecialchars($_POST['team_name'] ?? ''); ?>" required placeholder="e.g. Phoenix Strikers">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Select Sport</label>
                                <select name="sport_id" class="premium-select" id="sport-selector" required>
                                    <option value="0">Select Category</option>
                                    <?php foreach ($sports as $sport): ?>
                                        <?php
                                        $brand = getSportIcon($sport);
                                        $icon_val = ($brand['type'] === 'emoji') ? $brand['value'] : '📁';
                                        ?>
                                        <option value="<?php echo $sport['id']; ?>"
                                            data-icon="<?php echo htmlspecialchars($icon_val); ?>"
                                            data-img="<?php echo ($brand['type'] === 'image') ? $brand['value'] : ''; ?>"
                                            <?php echo ($_POST['sport_id'] ?? '') == $sport['id'] ? 'selected' : ''; ?>>
                                            <?php echo $icon_val; ?> <?php echo htmlspecialchars($sport['sport_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Coach Information</h3>

                            <div class="premium-field">
                                <label class="field-label">Coach Name (Optional)</label>
                                <input type="text" name="coach_name" class="premium-input" value="<?php echo htmlspecialchars($_POST['coach_name'] ?? ''); ?>" placeholder="e.g. Coach Johnson">
                            </div>

                            <div class="premium-info-grid" style="grid-template-columns: 1fr; margin-top: 10px;">
                                <div class="info-note" style="background: rgba(140, 0, 255, 0.05); padding: 15px; border-radius: 12px; border-left: 4px solid var(--primary-color);">
                                    <p style="font-size: 11px; line-height: 1.6; color: var(--slate-deep);">
                                        <strong>Note:</strong> Player assignment occurs on the Team Management page after this team is created.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 200px;">Save Team</button>
                        <a href="manage_teams.php" class="btn-reset-light" style="margin-left: 10px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Team Logo</h3>
                <div class="sport-preview-box">
                    <div id="image-drop-area" class="upload-area">
                        <div id="team-icon-preview" style="font-size: 80px; width: 100%; height: 100%; display:flex; align-items:center; justify-content:center;">🏆</div>
                        <img src="" id="image-preview" class="preview-img-premium" style="display: none;">
                        <div class="upload-overlay">
                            <div class="upload-icon">📁</div>
                            <p>Upload Logo</p>
                        </div>
                        <input type="file" name="team_logo" id="file-input" class="hidden-file-input" accept="image/*" form="add-team-form">
                    </div>
                </div>
                <h3 class="meta-handle" style="font-size: 20px; margin: 20px 0 5px;" id="team-name-label">New Team</h3>
                <p class="subtitle-text" style="font-size:11px; line-height: 1.6; opacity: 0.8">JPG, PNG or WEBP (Max 2MB)</p>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Upload Info</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        Upload a custom logo for your team. If no logo is uploaded, the sport icon will be displayed. Logos are saved to <strong>assets/uploads/teams/</strong>
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('image-drop-area');
        const fileInput = document.getElementById('file-input');
        const previewImg = document.getElementById('image-preview');
        const iconDiv = document.getElementById('team-icon-preview');
        const teamNameInput = document.getElementById('team_name_input');
        const teamNameLabel = document.getElementById('team-name-label');
        const sportSelector = document.getElementById('sport-selector');

        // Toggle file input
        dropArea.addEventListener('click', () => fileInput.click());

        // Preview image
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

        // Name preview
        teamNameInput.addEventListener('input', function() {
            teamNameLabel.innerHTML = this.value || "New Team";
        });

        // Sport Icon Sync (only if no custom image)
        sportSelector.addEventListener('change', function() {
            if (!fileInput.value) {
                const selected = this.options[this.selectedIndex];
                const icon = selected.getAttribute('data-icon');
                iconDiv.innerText = icon || '🏆';
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>