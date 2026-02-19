<?php

/**
 * College Sports Management System
 * Premium Reports & Analytics - Optimized Visibility
 */

require_once '../config.php';
requireAdmin();

// --- EXPORT ENGINE ---
if (isset($_GET['export'])) {
    $type = $_GET['export'];
    $filename = "Sports_Report_" . date('Ymd_His');

    // Fetch data for export
    $export_data = $conn->query("SELECT s.sport_name, 
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

    if ($type === 'csv' || $type === 'excel') {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . ($type === 'excel' ? '.xls' : '.csv') . '"');

        $output = fopen('php://output', 'w');

        // Add headers
        fputcsv($output, ['Sport Name', 'Total Players', 'Total Teams', 'Total Matches']);

        // Add data
        foreach ($export_data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }
}

$page_title = 'Reports & Analytics';
$current_page = 'reports';

// Core metrics extraction
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

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Reports & Analytics</h1>
            <p class="subtitle-text">View comprehensive sports management reports and statistics</p>
        </div>
        <div class="header-actions">
            <button onclick="window.print()" class="elite-action-btn" style="padding: 12px 25px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <img src="<?php echo $icons['reports']; ?>" style="width: 16px; filter: brightness(0) invert(1);">
                Print Report
            </button>
        </div>
    </div>

    <!-- HIGH-LEVEL METRICS -->
    <div class="stats-grid mb-8">
        <div class="glass-card stat-item primary">
            <div class="stat-content">
                <span class="label">Total Players</span>
                <h2 class="value"><?php echo number_format($total_players); ?></h2>
                <span class="trend">Active Players</span>
            </div>
            <div class="stat-icon-box">
                <img src="<?php echo $icons['players']; ?>">
            </div>
        </div>

        <div class="glass-card stat-item secondary">
            <div class="stat-content">
                <span class="label">Total Teams</span>
                <h2 class="value"><?php echo number_format($total_teams); ?></h2>
                <span class="trend">Active Teams</span>
            </div>
            <div class="stat-icon-box">
                <img src="<?php echo $icons['teams']; ?>">
            </div>
        </div>

        <div class="glass-card stat-item accent">
            <div class="stat-content">
                <span class="label">Total Matches</span>
                <h2 class="value"><?php echo number_format($total_matches); ?></h2>
                <span class="trend">Scheduled</span>
            </div>
            <div class="stat-icon-box">
                <img src="<?php echo $icons['matches']; ?>">
            </div>
        </div>

        <div class="glass-card stat-item info">
            <div class="stat-content">
                <span class="label">Completed</span>
                <h2 class="value"><?php echo number_format($completed_matches); ?></h2>
                <span class="trend">Finished</span>
            </div>
            <div class="stat-icon-box">
                <img src="<?php echo $icons['results']; ?>">
            </div>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <!-- SPORT DISTRIBUTION TABLE -->
            <div class="glass-card">
                <div class="card-header pb-6">
                    <h3 class="card-title">Sports Overview</h3>
                </div>
                <div class="ultra-table-container">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Sport</th>
                                <th class="text-center">Players</th>
                                <th class="text-center">Teams</th>
                                <th class="text-center">Matches</th>
                                <th class="text-right">Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sport_stats as $stat): ?>
                                <tr>
                                    <td>
                                        <div class="identity-cluster">
                                            <div class="mini-sport-icon">
                                                <?php
                                                $brand = getSportIcon($stat);
                                                if ($brand['type'] === 'image'): ?>
                                                    <img src="<?php echo $brand['value']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                                <?php else: ?>
                                                    <span style="font-size: 18px;"><?php echo $brand['value']; ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="meta-handle"><?php echo htmlspecialchars($stat['sport_name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center"><strong><?php echo $stat['player_count']; ?></strong></td>
                                    <td class="text-center"><strong><?php echo $stat['team_count']; ?></strong></td>
                                    <td class="text-center"><strong><?php echo $stat['match_count']; ?></strong></td>
                                    <td class="text-right">
                                        <?php
                                        $activity = ($stat['player_count'] * 2) + ($stat['team_count'] * 5) + ($stat['match_count'] * 10);
                                        $max_activity = 500; // Arbitrary for visualization
                                        $percentage = min(100, ($activity / $max_activity) * 100);
                                        ?>
                                        <div class="activity-bar">
                                            <div class="bar-fill" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="side-column">
            <!-- EXPORT MODULE -->
            <div class="glass-card">
                <h3 class="field-label mb-6 block">Export Options</h3>
                <div class="reporting-options">
                    <button class="report-btn" onclick="window.print()">
                        <div class="r-icon pdf">📄</div>
                        <div class="r-text">
                            <span class="r-title">PDF Report</span>
                            <span class="r-desc">Print-ready format</span>
                        </div>
                    </button>

                    <a href="reports.php?export=excel" class="report-btn" style="text-decoration: none;">
                        <div class="r-icon excel">📊</div>
                        <div class="r-text">
                            <span class="r-title">Excel Export</span>
                            <span class="r-desc">Spreadsheet format</span>
                        </div>
                    </a>

                    <a href="reports.php?export=csv" class="report-btn" style="text-decoration: none;">
                        <div class="r-icon raw">⚙️</div>
                        <div class="r-text">
                            <span class="r-title">CSV Data</span>
                            <span class="r-desc">Raw data export</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Information</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        Reports show current active data. Statistics are updated in real-time based on system records.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .mini-sport-icon {
        width: 40px;
        height: 40px;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .activity-bar {
        width: 100px;
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        display: inline-block;
        overflow: hidden;
    }

    .bar-fill {
        height: 100%;
        background: var(--primary-gradient);
        border-radius: 10px;
    }

    .reporting-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .report-btn {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: left;
        width: 100%;
    }

    .report-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border-color: var(--primary-light);
    }

    .r-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: #f8fafc;
        border-radius: 12px;
    }

    .r-icon.pdf {
        color: #ef4444;
        background: #fff1f2;
    }

    .r-icon.excel {
        color: #10b981;
        background: #ecfdf5;
    }

    .r-icon.raw {
        color: #6366f1;
        background: #eef2ff;
    }

    .r-title {
        display: block;
        font-weight: 800;
        font-size: 14px;
        color: var(--slate-deep);
    }

    .r-desc {
        font-size: 10px;
        color: #94a3b8;
    }

    @media print {

        .dashboard-header,
        .header-actions,
        .side-column,
        .sidebar,
        .header-main {
            display: none !important;
        }

        .main-grid {
            grid-template-columns: 1fr !important;
        }

        .dashboard-container {
            padding: 0 !important;
        }

        .glass-card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>

<?php include '../includes/footer.php'; ?>