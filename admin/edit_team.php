<?php

/**
 * College Sports Management System
 * Premium Team Update - Local File Upload Integration
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Edit Team';
$current_page = 'teams';

$team_id = intval($_GET['id'] ?? 0);
if (!$team_id) {
    setError('Invalid squad selection');
    header('Location: manage_teams.php');
    exit();
}

// Fetch team details
$team = $conn->query("SELECT t.*, s.sport_name, s.icon 
                      FROM teams t 
                      JOIN sports_categories s ON t.sport_id = s.id 
                      WHERE t.id = $team_id")->fetch_assoc();

if (!$team) {
    setError('Sports squad node not found');
    header('Location: manage_teams.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_name = sanitize($_POST['team_name']);
    $coach_name = sanitize($_POST['coach_name']);
    $status = $_POST['status'];

    $logo_path = $team['logo']; // Preserve current

    if (empty($team_name)) $errors[] = 'Team identity name is required';

    // Handle New File Upload
    if (isset($_FILES['team_logo']) && $_FILES['team_logo']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['team_logo'], UPLOAD_PATH . '/teams');
        if ($upload['success']) {
            $logo_path = $upload['filename'];

            // Delete old file if exists
            if ($team['logo']) {
                @unlink(UPLOAD_PATH . '/teams/' . $team['logo']);
            }
        } else {
            $errors[] = 'Upload failed: ' . $upload['error'];
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE teams SET team_name = ?, coach_name = ?, status = ?, logo = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $team_name, $coach_name, $status, $logo_path, $team_id);

        if ($stmt->execute()) {
            logActivity($conn, 'update', 'teams', $team_id, "Refined team branding: $team_name");
            setSuccess('Team parameters and branding synchronized successfully.');
            header('Location: manage_teams.php');
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
            <h1 class="welcome-text">Update Team</h1>
            <p class="subtitle-text">Change details and branding for <strong><?php echo htmlspecialchars($team['team_name']); ?></strong>.</p>
        </div>
        <div class="header-actions">
            <a href="manage_teams.php" class="btn-reset-light">
                Return to Teams Feed
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form" enctype="multipart/form-data" id="edit-team-form">
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
                            <h3 class="form-section-title">Team Identification</h3>

                            <div class="premium-field">
                                <label class="field-label">Team Identity (Name)</label>
                                <input type="text" name="team_name" class="premium-input" id="team_name_input" value="<?php echo htmlspecialchars($team['team_name']); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Team Status</label>
                                <div class="premium-radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="active" <?php echo ($team['status'] === 'active') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Active / Live</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="inactive" <?php echo ($team['status'] === 'inactive') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Inactive / Standby</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Coach Information</h3>
                            <div class="premium-field">
                                <label class="field-label">Coach Name</label>
                                <input type="text" name="coach_name" class="premium-input" value="<?php echo htmlspecialchars($team['coach_name']); ?>">
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Linked Sport Category</label>
                                <input type="text" class="premium-input" value="<?php echo htmlspecialchars($team['sport_name']); ?>" disabled style="opacity: 0.6; background: #f1f5f9;">
                            </div>
                        </div>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 200px;">Save Changes</button>
                        <a href="manage_teams.php" class="btn-reset-light" style="margin-left: 10px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Team Representative</h3>
                <div class="sport-preview-box">
                    <div id="image-drop-area" class="upload-area">
                        <?php
                        $logo = getTeamLogo($team);
                        if ($logo): ?>
                            <div id="team-icon-preview" style="font-size: 80px; width: 100%; height: 100%; display:none; align-items:center; justify-content:center;">🏆</div>
                            <img src="<?php echo $logo; ?>" id="image-preview" class="preview-img-premium">
                        <?php else:
                            $brand = getSportIcon(['icon' => $team['sport_icon']]);
                        ?>
                            <?php if ($brand['type'] === 'image'): ?>
                                <div id="team-icon-preview" style="font-size: 80px; width: 100%; height: 100%; display:none; align-items:center; justify-content:center;">🏆</div>
                                <img src="<?php echo $brand['value']; ?>" id="image-preview" class="preview-img-premium">
                            <?php else: ?>
                                <div id="team-icon-preview" style="font-size: 80px; width: 100%; height: 100%; display:flex; align-items:center; justify-content:center;"><?php echo $brand['value']; ?></div>
                                <img src="" id="image-preview" class="preview-img-premium" style="display: none;">
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="upload-overlay">
                            <div class="upload-icon">📁</div>
                            <p>Replace Team Logo</p>
                        </div>
                        <input type="file" name="team_logo" id="file-input" class="hidden-file-input" accept="image/*" form="edit-team-form">
                    </div>
                </div>
                <h3 class="meta-handle" style="font-size: 20px; margin-bottom: 5px;" id="team-name-label"><?php echo htmlspecialchars($team['team_name']); ?></h3>
                <p class="subtitle-text" style="font-size:11px; line-height: 1.6; opacity: 0.8">JPG, PNG or WEBP (Max 2MB)</p>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Team Performance</h3>
                <div class="stats-mini">
                    <div class="mini-stat">
                        <span class="mini-label">Matches Played</span>
                        <span class="mini-value"><?php echo $team['matches_played']; ?> Games</span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-label">Victory Count</span>
                        <span class="mini-value" style="color: var(--success-color)"><?php echo $team['matches_won']; ?> Wins</span>
                    </div>
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

        teamNameInput.addEventListener('input', function() {
            teamNameLabel.innerHTML = this.value || "New Squad";
        });
    });
</script>

<?php include '../includes/footer.php'; ?>