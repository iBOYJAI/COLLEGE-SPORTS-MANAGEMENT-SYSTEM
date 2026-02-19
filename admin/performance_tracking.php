<?php

/**
 * College Sports Management System
 * Premium Performance Tracking - Bento Redesign
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Performance Tracking';
$current_page = 'performance';

// Top performers by sport
$top_performers = $conn->query("SELECT p.id as player_id, p.name, p.register_number, p.photo, p.gender, s.sport_name,
                                COUNT(DISTINCT m.id) as matches_played,
                                SUM(CASE WHEN mr.winner_team_id IN (
                                    SELECT team_id FROM team_players WHERE player_id = p.id
                                ) THEN 1 ELSE 0 END) as matches_won
                                FROM players p
                                JOIN player_sports ps ON p.id = ps.player_id
                                JOIN sports_categories s ON ps.sport_id = s.id
                                LEFT JOIN team_players tp ON p.id = tp.player_id
                                LEFT JOIN matches m ON (m.team1_id = tp.team_id OR m.team2_id = tp.team_id) AND m.status = 'completed'
                                LEFT JOIN match_results mr ON m.id = mr.match_id
                                WHERE p.status = 'active'
                                GROUP BY p.id, s.id
                                HAVING matches_played > 0
                                ORDER BY matches_won DESC, matches_played DESC
                                LIMIT 12")->fetch_all(MYSQLI_ASSOC);

// Team standings
$team_standings = $conn->query("SELECT t.id as team_id, t.team_name, s.sport_name, t.matches_played, t.matches_won,
                                ROUND((t.matches_won / NULLIF(t.matches_played, 0)) * 100, 1) as win_rate
                                FROM teams t
                                JOIN sports_categories s ON t.sport_id = s.id
                                WHERE t.status = 'active' AND t.matches_played > 0
                                ORDER BY win_rate DESC, t.matches_won DESC
                                LIMIT 10")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Performance Tracking</h1>
            <p class="subtitle-text">View top athletes and team performance statistics</p>
        </div>
        <div class="header-actions">
            <a href="reports.php" class="btn-premium-search" style="padding: 12px 25px; font-size: 14px; text-decoration: none;">
                View Reports
            </a>
        </div>
    </div>

    <!-- METRICS GRID -->
    <div class="main-grid">
        <div class="charts-column">
            <!-- LEADERBOARD CARD -->
            <div class="glass-card">
                <div class="card-header pb-6" style="display:flex; justify-content: space-between; align-items: center;">
                    <div style="display:flex; align-items: center; gap: 12px;">
                        <span style="font-size: 24px;">🏆</span>
                        <h3 class="card-title">Top Players</h3>
                    </div>
                    <a href="manage_players.php" class="view-all">View All</a>
                </div>

                <div class="ultra-table-container">
                    <?php if (count($top_performers) > 0): ?>
                        <div class="performance-list">
                            <?php foreach ($top_performers as $i => $player): ?>
                                <div class="performer-row">
                                    <div class="rank-badge <?php echo ($i < 3) ? 'top-' . ($i + 1) : ''; ?>">
                                        <?php echo $i + 1; ?>
                                    </div>
                                    <div class="identity-cluster">
                                        <img src="<?php echo getPlayerPhoto($player['player_id'], $player['photo'], $player['gender']); ?>" class="ultra-avatar">
                                        <div>
                                            <h4 class="meta-handle"><?php echo htmlspecialchars($player['name']); ?></h4>
                                            <p class="meta-subtext"><?php echo $player['register_number']; ?></p>
                                        </div>
                                    </div>
                                    <div class="sport-tag">
                                        <span class="tier-pill" style="font-size: 9px;"><?php echo htmlspecialchars($player['sport_name']); ?></span>
                                    </div>
                                    <div class="stats-mini-cluster">
                                        <div class="stat-bubble">
                                            <span class="bubble-val"><?php echo $player['matches_played']; ?></span>
                                            <span class="bubble-label">MTCH</span>
                                        </div>
                                        <div class="stat-bubble success">
                                            <span class="bubble-val"><?php echo $player['matches_won']; ?></span>
                                            <span class="bubble-label">WINS</span>
                                        </div>
                                    </div>
                                    <div class="action-link">
                                        <a href="view_player.php?id=<?php echo $player['player_id']; ?>" title="Profile">
                                            <img src="<?php echo $icons['view']; ?>" style="width: 16px; opacity: 0.5;">
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="padding: 100px; text-align: center;">
                            <p class="text-secondary">No performance data available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="side-column">
            <!-- TEAM STANDINGS -->
            <div class="glass-card mb-8">
                <div class="card-header pb-6">
                    <div style="display:flex; align-items: center; gap: 12px;">
                        <span style="font-size: 24px;">🎖️</span>
                        <h3 class="card-title">Team Standings</h3>
                    </div>
                </div>

                <div class="standings-mini-list">
                    <?php foreach ($team_standings as $i => $team): ?>
                        <div class="standing-item">
                            <div class="standing-rank"><?php echo $i + 1; ?></div>
                            <div class="standing-icon">🏆</div>
                            <div class="standing-info">
                                <span class="standing-name"><?php echo htmlspecialchars($team['team_name']); ?></span>
                                <span class="standing-meta"><?php echo $team['sport_name']; ?></span>
                            </div>
                            <div class="standing-rate">
                                <span class="rate-val"><?php echo $team['win_rate']; ?>%</span>
                                <div class="rate-bar-bg">
                                    <div class="rate-bar-fill" style="width: <?php echo $team['win_rate']; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ANALYTICS LINKS -->
            <div class="glass-card">
                <h3 class="field-label mb-6 block">Quick Links</h3>
                <div class="quick-nav-grid">
                    <a href="player_statistics.php" class="nav-card">
                        <div class="nav-icon">📊</div>
                        <span>Player Stats</span>
                    </a>
                    <a href="reports.php" class="nav-card">
                        <div class="nav-icon">📝</div>
                        <span>Reports</span>
                    </a>
                    <a href="generate_certificate.php" class="nav-card">
                        <div class="nav-icon">📜</div>
                        <span>Certificates</span>
                    </a>
                    <a href="manage_matches.php" class="nav-card">
                        <div class="nav-icon">⚡</div>
                        <span>Matches</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .performance-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .performer-row {
        display: grid;
        grid-template-columns: 40px 1fr 120px 140px 40px;
        align-items: center;
        padding: 15px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        border: 1px solid #eef2ff;
        transition: all 0.3s;
    }

    .performer-row:hover {
        background: white;
        transform: scale(1.01);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 13px;
        color: #94a3b8;
        border-radius: 8px;
        background: #f1f5f9;
    }

    .rank-badge.top-1 {
        background: #fef3c7;
        color: #d97706;
    }

    .rank-badge.top-2 {
        background: #e2e8f0;
        color: #64748b;
    }

    .rank-badge.top-3 {
        background: #ffedd5;
        color: #9a3412;
    }

    .identity-cluster {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stats-mini-cluster {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .stat-bubble {
        padding: 6px 12px;
        background: #f8fafc;
        border-radius: 12px;
        text-align: center;
        min-width: 60px;
    }

    .stat-bubble.success {
        background: var(--primary-lighter);
        color: var(--primary-color);
    }

    .bubble-val {
        display: block;
        font-weight: 900;
        font-size: 14px;
        line-height: 1;
    }

    .bubble-label {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 0.5px;
        opacity: 0.6;
    }

    .standing-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
    }

    .standing-rank {
        font-weight: 900;
        color: #cbd5e1;
        font-size: 14px;
        width: 20px;
    }

    .standing-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .standing-info {
        flex: 1;
    }

    .standing-name {
        display: block;
        font-weight: 700;
        font-size: 14px;
        color: var(--slate-deep);
    }

    .standing-meta {
        font-size: 10px;
        color: #94a3b8;
    }

    .standing-rate {
        text-align: right;
        width: 80px;
    }

    .rate-val {
        font-weight: 900;
        font-size: 13px;
        color: var(--primary-color);
    }

    .rate-bar-bg {
        height: 4px;
        background: #f1f5f9;
        border-radius: 10px;
        margin-top: 4px;
        overflow: hidden;
    }

    .rate-bar-fill {
        height: 100%;
        background: var(--primary-gradient);
    }

    .quick-nav-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .nav-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
        border: 1px solid transparent;
    }

    .nav-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-light);
    }

    .nav-icon {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
    }

    .nav-card span {
        font-weight: 700;
        font-size: 12px;
        color: var(--slate-deep);
    }
</style>

<?php include '../includes/footer.php'; ?>