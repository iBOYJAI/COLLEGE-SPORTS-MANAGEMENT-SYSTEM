<?php

/**
 * System Intelligence Notifications
 */
require_once '../config.php';
requireAdmin();

$page_title = 'System Intelligence';
$current_page = 'notifications';

// Fetch recent notifications/activity
$activities = $conn->query("SELECT a.*, u.full_name as user_name 
                           FROM activity_log a 
                           LEFT JOIN users u ON a.user_id = u.id 
                           ORDER BY a.created_at DESC 
                           LIMIT 30")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header" style="margin-bottom: 40px;">
        <div class="header-info">
            <h1 class="ultra-title" style="font-size: 42px;">System Intelligence</h1>
            <p class="subtitle-text" style="color: #64748b; font-weight: 600; font-size: 16px;">
                Real-time tracking of all cadre movements and operational updates.
            </p>
        </div>
    </div>

    <div class="dashboard-bento-grid" style="grid-template-columns: repeat(3, 1fr);">
        <?php if (count($activities) > 0): ?>
            <?php foreach ($activities as $log):
                $icon = '⚡';
                $color = 'var(--elite-purple)';
                if (stripos($log['description'], 'registered') !== false) {
                    $icon = '👤';
                    $color = 'var(--elite-green)';
                }
                if (stripos($log['description'], 'match') !== false) {
                    $icon = '🏆';
                    $color = '#0ea5e9';
                }
                if (stripos($log['description'], 'squad') !== false) {
                    $icon = '🛡️';
                    $color = '#f59e0b';
                }
            ?>
                <div class="bento-card" style="min-height: 200px;">
                    <div style="display: flex; gap: 20px;">
                        <div style="width: 50px; height: 50px; background: #f8fafc; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 10px 20px rgba(0,0,0,0.02);">
                            <?php echo $icon; ?>
                        </div>
                        <div style="flex: 1;">
                            <span style="font-size: 10px; font-weight: 800; color: <?php echo $color; ?>; text-transform: uppercase; letter-spacing: 2px;">
                                <?php echo time_elapsed_string($log['created_at']); ?>
                            </span>
                            <h3 style="font-size: 16px; font-weight: 800; color: var(--slate-deep); margin: 10px 0; line-height: 1.4;">
                                <?php echo htmlspecialchars($log['description']); ?>
                            </h3>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                                <span style="font-size: 11px; font-weight: 700; color: #94a3b8;">Auth: <?php echo htmlspecialchars($log['user_name'] ?: 'System'); ?></span>
                                <span style="font-size: 11px; font-weight: 600; color: #cbd5e1;"><?php echo date('H:i', strtotime($log['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bento-card" style="grid-column: span 3; text-align: center; padding: 100px;">
                <span style="font-size: 60px;">📡</span>
                <h2 style="margin-top: 20px; font-weight: 900; color: var(--slate-deep);">Quiet on the Wire</h2>
                <p style="color: #94a3b8; font-weight: 600;">No system notifications detected in the current cycle.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>