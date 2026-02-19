<?php

/**
 * Staff System Intel Feed - Redesigned Analytics Page
 */

require_once '../config.php';
requireLogin();

$page_title = 'System Intel Feed';
$current_page = 'reports';

// Analytical metric extractions
$total_players = $conn->query("SELECT COUNT(*) as c FROM players WHERE status = 'active'")->fetch_assoc()['c'];
$total_teams = $conn->query("SELECT COUNT(*) as c FROM teams WHERE status = 'active'")->fetch_assoc()['c'];
$total_matches = $conn->query("SELECT COUNT(*) as c FROM matches")->fetch_assoc()['c'];
$completed_matches = $conn->query("SELECT COUNT(*) as c FROM matches WHERE status = 'completed'")->fetch_assoc()['c'];

// High-fidelity sport-wise distribution
$sport_stats = $conn->query("SELECT s.id, s.sport_name, s.icon,
                             COUNT(DISTINCT p.id) as player_count,
                             COUNT(DISTINCT t.id) as team_count,
                             COUNT(DISTINCT m.id) as match_count
                             FROM sports_categories s
                             LEFT JOIN player_sports ps ON s.id = ps.sport_id
                             LEFT JOIN players p ON ps.player_id = p.id AND p.status = 'active'
                             LEFT JOIN teams t ON s.id = t.sport_id AND t.status = 'active'
                             LEFT JOIN matches m ON s.id = m.sport_id
                             WHERE s.status = 'active'
                             GROUP BY s.id
                             ORDER BY player_count DESC")->fetch_all(MYSQLI_ASSOC);

// Timeline Feed
$per_page = 15;
$page_num = intval($_GET['page'] ?? 1);
$offset = ($page_num - 1) * $per_page;
$total_logs = $conn->query("SELECT COUNT(*) as total FROM activity_log")->fetch_assoc()['total'];
$total_pages = ceil($total_logs / $per_page);

$logs = $conn->query("SELECT l.*, u.full_name, u.role
                    FROM activity_log l
                    LEFT JOIN users u ON l.user_id = u.id
                    ORDER BY l.created_at DESC
                    LIMIT $per_page OFFSET $offset")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">System Intel Feed</h1>
            <p class="subtitle-text">Synthesized athletic analytics and operational logs</p>
        </div>
        <div class="header-actions">
            <button onclick="window.print()" class="btn-reset-light" style="display: flex; align-items: center; gap: 8px; border-radius: 12px; padding: 12px 20px;">
                <img src="<?php echo $icons['reports']; ?>" style="width: 16px;">
                Print Summary
            </button>
        </div>
    </div>

    <!-- ANALYTICAL OVERLAY -->
    <div class="stats-grid mb-8">
        <div class="glass-card stat-item primary">
            <div class="stat-content">
                <span class="label">CADRE STRENGTH</span>
                <h2 class="value"><?php echo number_format($total_players); ?></h2>
                <span class="trend">Ready Status</span>
            </div>
            <div class="stat-icon-box" style="background: rgba(140, 0, 255, 0.1);">
                <img src="<?php echo $icons['players']; ?>" style="filter: grayscale(1) opacity(0.8);">
            </div>
        </div>

        <div class="glass-card stat-item secondary">
            <div class="stat-content">
                <span class="label">DEPLOYED SQUADS</span>
                <h2 class="value"><?php echo number_format($total_teams); ?></h2>
                <span class="trend">Active Sync</span>
            </div>
            <div class="stat-icon-box" style="background: rgba(0, 200, 150, 0.1);">
                <img src="<?php echo $icons['teams']; ?>" style="filter: grayscale(1) opacity(0.8);">
            </div>
        </div>

        <div class="glass-card stat-item success">
            <div class="stat-content">
                <span class="label">FINAL RESULTS</span>
                <h2 class="value"><?php echo number_format($completed_matches); ?></h2>
                <span class="trend">Archived Ops</span>
            </div>
            <div class="stat-icon-box" style="background: rgba(16, 185, 129, 0.1);">
                <img src="<?php echo $icons['results']; ?>" style="filter: grayscale(1) opacity(0.8);">
            </div>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <!-- DIVISIONAL PENETRATION -->
            <div class="glass-card">
                <div class="card-header pb-6">
                    <h3 class="card-title">Divisional Analysis</h3>
                </div>
                <div class="ultra-table-container">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Discipline</th>
                                <th class="text-center">Cadre</th>
                                <th class="text-center">Squads</th>
                                <th class="text-right">Operational Pulse</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sport_stats as $stat): ?>
                                <tr>
                                    <td>
                                        <div class="identity-cluster">
                                            <div class="mini-sport-icon" style="width: 45px; height: 45px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 10px rgba(0,0,0,0.03); font-size: 22px;">
                                                <?php
                                                $sport_icon = $stat['icon'] ?? '🏆';
                                                if (strpos($sport_icon, '.') !== false || strpos($sport_icon, '/') !== false): ?>
                                                    <img src="../assets/images/<?php echo $sport_icon; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                <?php else: ?>
                                                    <span style="line-height: 1;"><?php echo $sport_icon; ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="meta-handle" style="font-size: 14px; font-weight: 800;"><?php echo htmlspecialchars($stat['sport_name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center"><span style="font-weight: 800; color: var(--slate-deep);"><?php echo $stat['player_count']; ?></span></td>
                                    <td class="text-center"><span style="font-weight: 800; color: var(--slate-deep);"><?php echo $stat['team_count']; ?></span></td>
                                    <td class="text-right">
                                        <?php
                                        $pulse = min(100, (($stat['player_count'] * 3) + ($stat['match_count'] * 15)));
                                        ?>
                                        <div style="width: 80px; height: 6px; background: #f1f5f9; border-radius: 10px; display: inline-block; overflow: hidden;">
                                            <div style="height: 100%; width: <?php echo $pulse; ?>%; background: var(--primary-color); border-radius: 10px;"></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TIMELINE FEED -->
            <div class="glass-card mt-8">
                <div class="card-header pb-6">
                    <h3 class="card-title">Operational Log Timeline</h3>
                </div>
                <div class="activity-feed">
                    <?php foreach ($logs as $log): ?>
                        <div class="ultra-table-row" style="margin-bottom: 5px; border-radius: 15px; background: white; border: 1px solid #f1f5f9;">
                            <div style="flex: 0 0 50px; display: flex; align-items: center; justify-content: center;">
                                <div style="width: 32px; height: 32px; background: white; border-radius: 10px; border: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?php echo $icons['info']; ?>" style="width: 14px; opacity: 0.5;">
                                </div>
                            </div>
                            <div style="flex: 2;">
                                <h4 style="font-weight: 800; color: var(--slate-deep); font-size: 13px;"><?php echo htmlspecialchars($log['description']); ?></h4>
                                <span class="meta-subtext"><?php echo date('H:i', strtotime($log['created_at'])); ?> // <?php echo htmlspecialchars($log['full_name']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div style="margin-top: 25px; display: flex; justify-content: center;">
                        <?php echo generatePagination($page_num, $total_pages, 'view_reports.php'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card">
                <h3 class="field-label mb-6 block">Command Directives</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        Analytical data represents live system states. Use the Intel Feed for daily reporting requirements.
                    </p>
                </div>
            </div>

            <div class="bento-card mt-8" style="background: rgba(16, 185, 129, 0.05); border: 2px solid rgba(16, 185, 129, 0.1);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 40px; height: 40px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">✓</div>
                    <div>
                        <span class="meta-subtext" style="color: #059669; font-weight: 800;">SYNC STATUS</span>
                        <p style="font-weight: 700; color: var(--slate-deep); font-size: 13px;">GRID UPLINK ACTIVE</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>