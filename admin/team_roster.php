<?php

/**
 * Team Roster with Captain Selection
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Team Roster';
$current_page = 'teams';

$team_id = intval($_GET['id'] ?? 0);

if (!$team_id) {
    setError('Invalid team ID');
    header('Location: manage_teams.php');
    exit();
}

$team = $conn->query("SELECT t.*, s.sport_name FROM teams t JOIN sports_categories s ON t.sport_id = s.id WHERE t.id = $team_id")->fetch_assoc();

if (!$team) {
    setError('Team not found');
    header('Location: manage_teams.php');
    exit();
}

// Get team players with captain status
$players = $conn->query("SELECT p.*, tp.is_captain FROM players p 
                         JOIN team_players tp ON p.id = tp.player_id 
                         WHERE tp.team_id = $team_id 
                         ORDER BY tp.is_captain DESC, p.name")->fetch_all(MYSQLI_ASSOC);

// Handle captain selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_captain'])) {
    $captain_id = intval($_POST['captain_id']);

    // Remove existing captain
    $conn->query("UPDATE team_players SET is_captain = 0 WHERE team_id = $team_id");

    // Set new captain
    $stmt = $conn->prepare("UPDATE team_players SET is_captain = 1 WHERE team_id = ? AND player_id = ?");
    $stmt->bind_param("ii", $team_id, $captain_id);
    $stmt->execute();

    logActivity($conn, 'update', 'teams', $team_id, "Set team captain");
    setSuccess('Team captain updated');
    header("Location: team_roster.php?id=$team_id");
    exit();
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="page-header">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="manage_teams.php" class="btn btn-secondary">← Back</a>
            <div style="flex: 1;">
                <h1 class="page-title"><?php echo htmlspecialchars($team['team_name']); ?> Roster</h1>
                <p class="page-subtitle"><?php echo $team['sport_name']; ?> • <?php echo count($players); ?> Players</p>
            </div>
            <a href="assign_players.php?id=<?php echo $team_id; ?>" class="btn btn-primary"><img src="<?php echo $icons['users']; ?>" class="action-icon-sm" style="filter: brightness(0) invert(1);"> Manage Players</a>
        </div>
    </div>

    <?php if (empty($players)): ?>
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 60px;">
                <div style="margin-bottom: 16px;"><img src="<?php echo $icons['ill_players']; ?>" class="action-icon-img" style="width: 120px; height: 120px;"></div>
                <h3>No Players Assigned</h3>
                <p class="text-secondary">Add players to this team to get started</p>
                <a href="assign_players.php?id=<?php echo $team_id; ?>" class="btn btn-primary">Assign Players</a>
            </div>
        </div>
    <?php else: ?>

        <!-- Set Captain Form -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                <h3 class="card-title"><img src="<?php echo $icons['certificates']; ?>" class="action-icon-sm"> Team Captain</h3>
            </div>
            <div class="card-body">
                <form method="POST" style="display: flex; gap: 12px; align-items: flex-end;">
                    <div class="form-group" style="flex: 1; margin: 0;">
                        <label class="form-label">Select Team Captain</label>
                        <select name="captain_id" class="form-control" required>
                            <?php foreach ($players as $player): ?>
                                <option value="<?php echo $player['id']; ?>" <?php echo $player['is_captain'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($player['name']); ?> - <?php echo $player['register_number']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="set_captain" class="btn btn-primary">Set as Captain</button>
                </form>
            </div>
        </div>

        <!-- Player Roster -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Team Members</h3>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Reg. Number</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Blood Group</th>
                            <th>Role</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($players as $player): ?>
                            <tr style="<?php echo $player['is_captain'] ? 'background: #fff3cd;' : ''; ?>">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <img src="<?php echo getPlayerPhoto($player['id'], $player['photo'], $player['gender']); ?>" alt="Avatar" class="avatar" style="object-fit: cover;">
                                        <div>
                                            <strong><?php echo htmlspecialchars($player['name']); ?></strong>
                                            <?php if ($player['is_captain']): ?>
                                                <img src="<?php echo $icons['certificates']; ?>" class="action-icon-sm" style="margin-left: 8px;">
                                            <?php endif; ?>
                                            <div style="font-size: 12px; color: var(--text-secondary);">
                                                <?php echo $player['gender']; ?>, Age <?php echo $player['age']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo $player['register_number']; ?></td>
                                <td><?php echo $player['department']; ?></td>
                                <td><?php echo $player['year']; ?> Year</td>
                                <td><?php echo $player['blood_group'] ?: '-'; ?></td>
                                <td>
                                    <?php if ($player['is_captain']): ?>
                                        <span class="badge badge-warning">Captain</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Player</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $player['mobile']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>