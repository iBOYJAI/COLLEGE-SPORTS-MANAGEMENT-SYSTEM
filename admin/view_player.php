<?php

/**
 * College Sports Management System
 * Premium Player Profile - High Fidelity Dashboard
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Athlete Profile';
$current_page = 'players';

$player_id = intval($_GET['id'] ?? 0);

if (!$player_id) {
    setError('Invalid player selection identifier.');
    header('Location: manage_players.php');
    exit();
}

// Get player details with consolidated sports list
$query = "SELECT p.*, GROUP_CONCAT(DISTINCT s.sport_name SEPARATOR ', ') as sports_list
          FROM players p
          LEFT JOIN player_sports ps ON p.id = ps.player_id
          LEFT JOIN sports_categories s ON ps.sport_id = s.id
          WHERE p.id = ?
          GROUP BY p.id";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $player_id);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$player) {
    setError('Athlete node not found in system.');
    header('Location: manage_players.php');
    exit();
}

// Fetch Squad Assignments
$teams = $conn->query("SELECT t.team_name, s.sport_name 
                       FROM team_players tp
                       JOIN teams t ON tp.team_id = t.id
                       JOIN sports_categories s ON t.sport_id = s.id
                       WHERE tp.player_id = $player_id")->fetch_all(MYSQLI_ASSOC);

// Fetch Competitive History (Recent Matches)
$matches = $conn->query("SELECT m.match_date, m.match_time, s.sport_name, 
                         t1.team_name as team1, t2.team_name as team2,
                         mr.team1_score, mr.team2_score, mr.winner_team_id
                         FROM matches m
                         JOIN teams t1 ON m.team1_id = t1.id
                         JOIN teams t2 ON m.team2_id = t2.id
                         JOIN sports_categories s ON m.sport_id = s.id
                         LEFT JOIN match_results mr ON m.id = mr.match_id
                         WHERE (m.team1_id IN (SELECT team_id FROM team_players WHERE player_id = $player_id) 
                             OR m.team2_id IN (SELECT team_id FROM team_players WHERE player_id = $player_id))
                         AND m.status = 'completed'
                         ORDER BY m.match_date DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text"><?php echo htmlspecialchars($player['name']); ?></h1>
            <p class="subtitle-text">Year <?php echo $player['year']; ?> • <?php echo $player['department']; ?></p>
        </div>
        <div class="header-actions">
            <a href="manage_players.php" class="btn-reset-light">
                Back to List
            </a>
            <a href="edit_player.php?id=<?php echo $player_id; ?>" class="elite-action-btn" style="text-decoration: none; padding: 12px 25px;">
                Edit Profile
            </a>
        </div>
    </div>

    <div class="main-grid">
        <!-- LEFT: PROFILE CARD & STATS -->
        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <div class="profile-avatar-box">
                    <img src="<?php echo getPlayerPhoto($player['id'], $player['photo'], $player['gender']); ?>" class="profile-avatar-main">
                    <div class="status-indicator <?php echo $player['status']; ?>"></div>
                </div>

                <h2 class="meta-handle" style="font-size: 24px; margin-top: 20px;"><?php echo htmlspecialchars($player['name']); ?></h2>
                <p class="meta-subtext" style="color: var(--primary-color); font-weight: 700;"><?php echo $player['register_number']; ?></p>

                <div class="profile-metrics-grid mt-8">
                    <div class="p-metric">
                        <span class="p-label">Age</span>
                        <span class="p-val"><?php echo $player['age']; ?></span>
                    </div>
                    <div class="p-metric">
                        <span class="p-label">Blood</span>
                        <span class="p-val"><?php echo $player['blood_group'] ?: '-'; ?></span>
                    </div>
                    <div class="p-metric">
                        <span class="p-label">Year</span>
                        <span class="p-val"><?php echo $player['year']; ?></span>
                    </div>
                </div>

                <div class="premium-divider"></div>

                <div class="contact-node-list">
                    <div class="contact-node">
                        <span class="c-label">Mobile</span>
                        <span class="c-val"><?php echo $player['mobile']; ?></span>
                    </div>
                    <div class="contact-node">
                        <span class="c-label">Email</span>
                        <span class="c-val"><?php echo $player['email'] ?: 'No email linked'; ?></span>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT: ACTIVITY & SQUADS -->
        <div class="charts-column">
            <!-- SPORTS & SQUADS DASH -->
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div class="glass-card">
                    <h3 class="card-title mb-6">Sports</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <?php if ($player['sports_list']): ?>
                            <?php foreach (explode(', ', $player['sports_list']) as $sport): ?>
                                <span class="tier-pill"><?php echo htmlspecialchars($sport); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="meta-subtext">No sports assigned</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="glass-card">
                    <h3 class="card-title mb-6">Teams</h3>
                    <div class="assigned-teams-list">
                        <?php if (count($teams) > 0): ?>
                            <?php foreach ($teams as $team): ?>
                                <div class="team-mini-node">
                                    <div class="team-mini-icon">🏆</div>
                                    <div class="team-mini-info">
                                        <span class="team-mini-name"><?php echo htmlspecialchars($team['team_name']); ?></span>
                                        <span class="team-mini-sport"><?php echo $team['sport_name']; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="meta-subtext">No teams assigned</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- COMPETITIVE LOG -->
            <div class="glass-card">
                <div class="card-header pb-6">
                    <h3 class="card-title">Match History</h3>
                </div>

                <div class="competative-list">
                    <?php if (count($matches) > 0): ?>
                        <?php foreach ($matches as $match): ?>
                            <div class="match-log-node">
                                <div class="match-meta">
                                    <span class="match-date"><?php echo date('M d, Y', strtotime($match['match_date'])); ?></span>
                                    <span class="match-sport"><?php echo $match['sport_name']; ?></span>
                                </div>
                                <div class="match-pairing">
                                    <span class="pairing-team"><?php echo htmlspecialchars($match['team1']); ?></span>
                                    <div class="match-score-board">
                                        <span class="score-val"><?php echo $match['team1_score']; ?></span>
                                        <span class="score-divider">-</span>
                                        <span class="score-val"><?php echo $match['team2_score']; ?></span>
                                    </div>
                                    <span class="pairing-team"><?php echo htmlspecialchars($match['team2']); ?></span>
                                </div>
                                <div class="match-result-badge">
                                    <!-- Log logic for win/loss if needed -->
                                    <span class="tier-pill" style="font-size: 8px; background: #f1f5f9;">Completed</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 60px; text-align: center;">
                            <p class="text-secondary">No matches played yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-avatar-box {
        position: relative;
        display: inline-block;
        margin-bottom: 10px;
    }

    .profile-avatar-main {
        width: 160px;
        height: 160px;
        border-radius: 50px;
        object-fit: cover;
        border: 6px solid white;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        background: #f8fafc;
    }

    .status-indicator {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 4px solid white;
    }

    .status-indicator.active {
        background: var(--neon-green);
        box-shadow: 0 0 15px var(--neon-green);
    }

    .status-indicator.inactive {
        background: #94a3b8;
    }

    .profile-metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .p-metric {
        background: white;
        padding: 12px;
        border-radius: 15px;
        border: 1px solid #f1f5f9;
    }

    .p-label {
        display: block;
        font-size: 9px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .p-val {
        font-weight: 900;
        font-size: 16px;
        color: var(--slate-deep);
    }

    .contact-node-list {
        text-align: left;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .contact-node .c-label {
        display: block;
        font-size: 10px;
        color: #94a3b8;
        font-weight: 700;
    }

    .contact-node .c-val {
        font-weight: 700;
        font-size: 14px;
        color: var(--slate-deep);
    }

    .assigned-teams-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .team-mini-node {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        background: white;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        transition: transform 0.2s;
    }

    .team-mini-node:hover {
        transform: translateX(5px);
    }

    .team-mini-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .team-mini-name {
        display: block;
        font-weight: 700;
        font-size: 13px;
        color: var(--slate-deep);
    }

    .team-mini-sport {
        font-size: 9px;
        color: #94a3b8;
    }

    .match-log-node {
        display: grid;
        grid-template-columns: 100px 1fr 80px;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
    }

    .match-log-node:last-child {
        border-bottom: none;
    }

    .match-meta span {
        display: block;
    }

    .match-date {
        font-weight: 700;
        font-size: 12px;
        color: var(--slate-deep);
    }

    .match-sport {
        font-size: 10px;
        color: #94a3b8;
    }

    .match-pairing {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }

    .pairing-team {
        font-weight: 800;
        font-size: 13px;
        color: var(--slate-deep);
        width: 40%;
        text-align: center;
    }

    .match-score-board {
        background: #f8fafc;
        padding: 6px 15px;
        border-radius: 10px;
        font-weight: 900;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .score-divider {
        opacity: 0.3;
    }
</style>

<?php include '../includes/footer.php'; ?>