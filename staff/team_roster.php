<?php

/**
 * Staff Team Roster - Premium Interface
 */
require_once '../config.php';
requireLogin();

$team_id = intval($_GET['id'] ?? 0);
if (!$team_id) {
    header('Location: view_teams.php');
    exit();
}

$team = $conn->query("SELECT t.*, s.sport_name 
                      FROM teams t 
                      JOIN sports_categories s ON t.sport_id = s.id 
                      WHERE t.id = $team_id")->fetch_assoc();

if (!$team) {
    header('Location: view_teams.php');
    exit();
}

$players = $conn->query("SELECT p.*, tp.is_captain 
                         FROM players p 
                         JOIN team_players tp ON p.id = tp.player_id 
                         WHERE tp.team_id = $team_id 
                         ORDER BY tp.is_captain DESC, p.name ASC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="padding: 40px; background: #f8fafc;">
    <div class="ultra-header">
        <div style="display: flex; gap: 20px; align-items: center;">
            <div class="sport-emoji-box" style="width: 80px; height: 80px; background: white; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); font-size: 35px;">
                🛡️
            </div>
            <div>
                <h1 class="ultra-title"><?php echo htmlspecialchars($team['team_name']); ?></h1>
                <p class="meta-subtext" style="color: rgba(255,255,255,0.7); margin-top: 10px;">Division: <?php echo $team['sport_name']; ?> // Roster Integrity: <?php echo count($players); ?> Personnel</p>
            </div>
        </div>
    </div>

    <div style="margin-top: -30px;">
        <div class="bento-card">
            <h3 style="font-weight: 900; color: var(--slate-deep); margin-bottom: 30px;">Squad Lineup</h3>

            <div class="ultra-table-container shadow-none" style="background: transparent; border: none;">
                <?php if (count($players) > 0): ?>
                    <?php foreach ($players as $player): ?>
                        <div class="ultra-table-row" style="background: white; border-radius: 20px; margin-bottom: 12px; border: 1px solid #f1f5f9;">
                            <div class="identity-cluster" style="flex: 2;">
                                <img src="../assets/images/Avatar/<?php echo $player['photo'] ?: 'default.png'; ?>" class="ultra-avatar">
                                <div>
                                    <h4 class="meta-handle"><?php echo htmlspecialchars($player['name']); ?></h4>
                                    <span class="meta-subtext"><?php echo $player['register_number']; ?></span>
                                </div>
                            </div>

                            <div style="flex: 1.5;">
                                <span class="meta-subtext">Department</span>
                                <span style="font-weight: 700; color: var(--slate-deep);"><?php echo $player['department']; ?></span>
                            </div>

                            <div style="flex: 1;">
                                <?php if ($player['is_captain']): ?>
                                    <span class="tier-pill" style="background: var(--warning-lighter); color: var(--warning-color); font-size: 10px;">CAPTAIN</span>
                                <?php else: ?>
                                    <span class="tier-pill" style="font-size: 10px;">MEMBER</span>
                                <?php endif; ?>
                            </div>

                            <div>
                                <a href="view_player.php?id=<?php echo $player['id']; ?>" class="btn-reset-light" style="padding: 10px 20px; font-size: 12px;">DOSSIER</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 60px;">
                        <p class="meta-subtext">No personnel assigned to this squadron.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>