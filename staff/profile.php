<?php

/**
 * Staff Profile - Premium Interface
 */
require_once '../config.php';
requireLogin();

$page_title = 'Personnel Profile';
$current_page = 'profile';

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="padding: 40px; background: #f8fafc;">
    <div class="ultra-header">
        <h1 class="ultra-title">Personnel Identity</h1>
        <p class="meta-subtext" style="color: rgba(255,255,255,0.7); margin-top: 10px;">Dossier summary for <?php echo htmlspecialchars($current_user['full_name']); ?>.</p>
    </div>

    <div class="bento-card" style="margin-top: -30px; max-width: 600px; margin-left: auto; margin-right: auto; padding: 50px; text-align: center;">
        <div class="avatar-frame" style="width: 120px; height: 120px; margin: 0 auto 30px; ">
            <img src="<?php echo $avatar_path; ?>" class="avatar-img-pro">
        </div>

        <h2 style="font-weight: 900; color: var(--slate-deep); font-size: 28px; margin-bottom: 10px;"><?php echo htmlspecialchars($current_user['full_name']); ?></h2>
        <div class="tier-pill" style="margin-bottom: 30px; display: inline-block;">CORP // STAFF OPERATOR</div>

        <div style="text-align: left; background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #eef2ff;">
            <div style="margin-bottom: 20px;">
                <span class="meta-subtext">Handle</span>
                <p style="font-weight: 800; color: var(--slate-deep);"><?php echo htmlspecialchars($current_user['username']); ?></p>
            </div>
            <div style="margin-bottom: 20px;">
                <span class="meta-subtext">Clearance</span>
                <p style="font-weight: 800; color: var(--elite-purple);">Level 2 (Departmental)</p>
            </div>
            <div>
                <span class="meta-subtext">Assigned Module</span>
                <p style="font-weight: 800; color: var(--slate-deep);"><?php echo strtoupper($current_user['role']); ?> CENTRAL</p>
            </div>
        </div>

        <div style="margin-top: 40px;">
            <a href="dashboard.php" class="elite-action-btn" style="width: 100%; justify-content: center; padding: 15px;">DASHBOARD ACCESS</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>