<?php

/**
 * College Sports Management System
 * Premium Player Statistics - Optimized View
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Player Statistics';
$current_page = 'statistics';

// Search and filtering
$search = sanitize($_GET['search'] ?? '');
$dept_filter = sanitize($_GET['department'] ?? 'all');

$where = ["p.status = 'active'"];
if ($search) {
    $where[] = "(p.name LIKE '%$search%' OR p.register_number LIKE '%$search%')";
}
if ($dept_filter !== 'all') {
    $where[] = "p.department = '$dept_filter'";
}
$where_clause = implode(' AND ', $where);

// Get all players with statistics
$players = $conn->query("SELECT p.*, 
                         GROUP_CONCAT(DISTINCT s.sport_name SEPARATOR ', ') as sports_list,
                         COUNT(DISTINCT tp.team_id) as teams_count,
                         COUNT(DISTINCT m.id) as matches_played
                         FROM players p
                         LEFT JOIN player_sports ps ON p.id = ps.player_id
                         LEFT JOIN sports_categories s ON ps.sport_id = s.id
                         LEFT JOIN team_players tp ON p.id = tp.player_id
                         LEFT JOIN matches m ON (m.team1_id = tp.team_id OR m.team2_id = tp.team_id) AND m.status = 'completed'
                         WHERE $where_clause
                         GROUP BY p.id
                         ORDER BY matches_played DESC, p.name")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Player Statistics</h1>
            <p class="subtitle-text">View detailed player performance and participation data</p>
        </div>
        <div class="header-actions">
            <!-- Search Bar Mini -->
            <form action="" method="GET" style="display:flex; gap: 10px;">
                <input type="text" name="search" class="premium-input-sm" placeholder="Search players..." value="<?php echo htmlspecialchars($search); ?>" style="width: 200px;">
                <button type="submit" class="elite-action-btn" style="padding: 0 20px;">
                    <img src="<?php echo $icons['search']; ?>" style="width: 16px; filter: brightness(0) invert(1);">
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN DATA VIEW -->
    <div class="glass-card">
        <div class="ultra-table-container">
            <?php if (count($players) > 0): ?>
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Department</th>
                            <th>Sports</th>
                            <th class="text-center">Teams</th>
                            <th class="text-center">Matches</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($players as $player): ?>
                            <tr>
                                <td>
                                    <div class="identity-cluster">
                                        <img src="<?php echo getPlayerPhoto($player['id'], $player['photo'], $player['gender']); ?>" class="ultra-avatar">
                                        <div>
                                            <h3 class="meta-handle"><?php echo htmlspecialchars($player['name']); ?></h3>
                                            <p class="meta-subtext"><?php echo $player['register_number']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="line-height: 1.4;">
                                        <span style="font-weight: 700; color: var(--slate-deep); font-size: 13px;"><?php echo $player['department']; ?></span>
                                        <p class="meta-subtext"><?php echo $player['year']; ?> Year</p>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 5px; max-width: 250px;">
                                        <?php if ($player['sports_list']): ?>
                                            <?php foreach (explode(', ', $player['sports_list']) as $sport): ?>
                                                <span class="tier-pill" style="background: rgba(140, 0, 255, 0.05); color: var(--primary-color); border: 1px solid rgba(140, 0, 255, 0.1); font-size: 9px;"><?php echo htmlspecialchars($sport); ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="meta-subtext">No sports</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="stat-badge">
                                        <span class="badge-num"><?php echo $player['teams_count']; ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="stat-badge primary">
                                        <span class="badge-num"><?php echo $player['matches_played']; ?></span>
                                        <span class="badge-label">Matches</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <a href="view_player.php?id=<?php echo $player['id']; ?>" class="action-btn-circle" title="View Details">
                                        <img src="<?php echo $icons['view']; ?>" style="width: 18px;">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 100px; text-align: center;">
                    <img src="<?php echo $icons['ill_players']; ?>" style="width: 120px; opacity: 0.3; margin-bottom: 20px;">
                    <h3 style="color: var(--slate-deep); font-weight: 900;">No Players Found</h3>
                    <p class="subtitle-text">Try adjusting your search or filters</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .stat-badge {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        padding: 8px 15px;
        border-radius: 12px;
        min-width: 60px;
    }

    .stat-badge.primary {
        background: var(--primary-lighter);
        color: var(--primary-color);
    }

    .badge-num {
        font-weight: 900;
        font-size: 16px;
        line-height: 1;
    }

    .badge-label {
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.7;
        margin-top: 2px;
    }

    .action-btn-circle {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border-radius: 50%;
        transition: all 0.3s;
        border: none;
        text-decoration: none;
    }

    .action-btn-circle:hover {
        background: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(140, 0, 255, 0.2);
    }

    .action-btn-circle:hover img {
        filter: brightness(0) invert(1);
    }

    .premium-table th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        padding: 20px;
        border-bottom: 2px solid #f1f5f9;
    }

    .premium-table td {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .premium-input-sm {
        height: 44px;
        padding: 0 15px;
        border-radius: 12px;
        border: 1px solid #eef2ff;
        background: white;
        font-size: 13px;
        outline: none;
        transition: all 0.3s;
    }

    .premium-input-sm:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px var(--primary-lighter);
    }
</style>

<?php include '../includes/footer.php'; ?>