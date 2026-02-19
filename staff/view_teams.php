<?php

/**
 * Staff View Teams - Premium Interface
 */
require_once '../config.php';
requireLogin();

$page_title = 'View Teams';
$current_page = 'teams';

$search = sanitize($_GET['search'] ?? '');

$query = "SELECT t.*, s.sport_name 
          FROM teams t 
          JOIN sports_categories s ON t.sport_id = s.id 
          WHERE t.status = 'active'";

if ($search) {
    $query .= " AND (t.team_name LIKE '%$search%' OR s.sport_name LIKE '%$search%')";
}

$query .= " ORDER BY s.sport_name, t.team_name";
$teams = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="padding: 40px; background: #f8fafc;">
    <div class="ultra-header">
        <h1 class="ultra-title">Squadron & Teams Registry</h1>
        <p class="meta-subtext" style="color: rgba(255,255,255,0.7); margin-top: 10px;">Review active sports divisions and registered team rosters.</p>
    </div>

    <!-- Filters -->
    <div class="bento-card" style="margin-top: -30px; margin-bottom: 30px;">
        <form method="GET" style="display: flex; gap: 20px;">
            <div class="form-group" style="flex: 1;">
                <input type="text" name="search" class="form-control" placeholder="Search by team name or sport..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button type="submit" class="elite-action-btn" style="padding: 0 40px;">FILTER RESULTS</button>
        </form>
    </div>

    <!-- Teams Grid -->
    <div class="elite-grid">
        <?php foreach ($teams as $team): ?>
            <div class="bento-card success" style="padding: 0; overflow: hidden;">
                <div style="background: rgba(0, 200, 150, 0.05); padding: 25px; text-align: center; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 70px; height: 70px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); font-size: 30px;">
                        🛡️
                    </div>
                    <h3 style="font-weight: 900; color: var(--slate-deep); font-size: 18px;"><?php echo htmlspecialchars($team['team_name']); ?></h3>
                    <span class="tier-pill" style="margin-top: 10px;"><?php echo $team['sport_name']; ?></span>
                </div>
                <div style="padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: white;">
                    <div style="text-align: center; background: #f8fafc; padding: 10px; border-radius: 12px;">
                        <span class="meta-subtext" style="font-size: 9px;">PLAYED</span>
                        <p style="font-weight: 900; color: var(--slate-deep);"><?php echo $team['matches_played']; ?></p>
                    </div>
                    <div style="text-align: center; background: #f8fafc; padding: 10px; border-radius: 12px;">
                        <span class="meta-subtext" style="font-size: 9px;">VICTORIES</span>
                        <p style="font-weight: 900; color: var(--elite-green);"><?php echo $team['matches_won']; ?></p>
                    </div>
                </div>
                <div style="padding: 15px; background: #fff; text-align: center;">
                    <a href="team_roster.php?id=<?php echo $team['id']; ?>" class="btn-reset-light" style="width: 100%; display: block; font-size: 11px; font-weight: 800;">VIEW ROSTER</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>