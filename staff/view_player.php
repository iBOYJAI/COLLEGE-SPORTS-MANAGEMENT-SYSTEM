<?php

/**
 * Staff View Player - Premium Interface
 */
require_once '../config.php';
requireLogin();

$player_id = intval($_GET['id'] ?? 0);
if (!$player_id) {
    header('Location: view_players.php');
    exit();
}

$player = $conn->query("SELECT p.*, GROUP_CONCAT(DISTINCT s.sport_name SEPARATOR ', ') as sports_list
                        FROM players p
                        LEFT JOIN player_sports ps ON p.id = ps.player_id
                        LEFT JOIN sports_categories s ON ps.sport_id = s.id
                        WHERE p.id = $player_id GROUP BY p.id")->fetch_assoc();

if (!$player) {
    header('Location: view_players.php');
    exit();
}

$teams = $conn->query("SELECT t.team_name, s.sport_name FROM team_players tp JOIN teams t ON tp.team_id = t.id JOIN sports_categories s ON t.sport_id = s.id WHERE tp.player_id = $player_id")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="padding: 40px; background: #f8fafc;">
    <div class="ultra-header">
        <h1 class="ultra-title">Personnel Dossier</h1>
        <p class="meta-subtext" style="color: rgba(255,255,255,0.7); margin-top: 10px;">Comprehensive biometric and performance data for <?php echo htmlspecialchars($player['name']); ?>.</p>
    </div>

    <div class="main-grid" style="margin-top: -30px;">
        <div class="side-column">
            <div class="bento-card" style="text-align: center; padding: 40px 20px;">
                <div class="avatar-frame" style="width: 120px; height: 120px; margin: 0 auto 20px; ">
                    <img src="../assets/images/Avatar/<?php echo $player['photo'] ?: 'default.png'; ?>" class="avatar-img-pro">
                </div>
                <h3 style="font-weight: 900; color: var(--slate-deep); font-size: 20px;"><?php echo htmlspecialchars($player['name']); ?></h3>
                <span class="tier-pill"><?php echo htmlspecialchars($player['register_number']); ?></span>

                <div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; text-align: left;">
                    <div style="background: #f8fafc; padding: 15px; border-radius: 15px;">
                        <span class="meta-subtext">Department</span>
                        <p style="font-weight: 700; color: var(--slate-deep); font-size: 13px;"><?php echo $player['department']; ?></p>
                    </div>
                    <div style="background: #f8fafc; padding: 15px; border-radius: 15px;">
                        <span class="meta-subtext">Year</span>
                        <p style="font-weight: 700; color: var(--slate-deep); font-size: 13px;"><?php echo $player['year']; ?> Year</p>
                    </div>
                </div>

                <div style="margin-top: 30px; text-align: left; padding: 10px;">
                    <span class="meta-subtext">Communications</span>
                    <p style="font-weight: 700; color: #64748b; font-size: 13px; margin-top: 5px;">📞 <?php echo $player['mobile']; ?></p>
                </div>
            </div>
        </div>

        <div class="charts-column">
            <div class="bento-card">
                <h3 style="font-weight: 900; color: var(--slate-deep); margin-bottom: 25px;">Operational Assignments</h3>
                <div class="elite-grid">
                    <?php foreach ($teams as $t): ?>
                        <div style="background: #f8fafc; padding: 20px; border-radius: 20px; border: 1px solid #eef2ff;">
                            <span class="meta-subtext"><?php echo strtoupper($t['sport_name']); ?></span>
                            <h4 style="font-weight: 900; color: var(--slate-deep); margin-top: 5px;"><?php echo htmlspecialchars($t['team_name']); ?></h4>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bento-card" style="margin-top: 30px;">
                <h3 style="font-weight: 900; color: var(--slate-deep); margin-bottom: 25px;">Mission History</h3>
                <p class="meta-subtext">Historical performance metrics are currently archived.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>