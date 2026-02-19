<?php

/**
 * College Sports Management System
 * Premium Team Management - Dynamic Grid & Optimized Controls
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Team Management';
$current_page = 'teams';

// Search and filter params
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sport_filter = isset($_GET['sport']) ? intval($_GET['sport']) : 0;
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'card';

// Build query
$where_conditions = ["t.status = 'active'"];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "t.team_name LIKE ?";
    $search_param = "%$search%";
    $params[] = $search_param;
    $types .= 's';
}

if ($sport_filter > 0) {
    $where_conditions[] = "t.sport_id = ?";
    $params[] = $sport_filter;
    $types .= 'i';
}

$where_clause = implode(' AND ', $where_conditions);

// Fetch teams with stats
$query = "SELECT t.*, s.sport_name, s.icon as sport_icon,
          COUNT(DISTINCT tp.player_id) as player_count
          FROM teams t
          JOIN sports_categories s ON t.sport_id = s.id
          LEFT JOIN team_players tp ON t.id = tp.team_id
          WHERE $where_clause
          GROUP BY t.id
          ORDER BY t.created_at DESC";

if (!empty($params)) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

$teams = $result->fetch_all(MYSQLI_ASSOC);

// Get sports for filtering
$sports_list = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status = 'active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="background: #f8fafc; padding: 40px;">

    <!-- PREMIUM TEAMS HEADER -->
    <div class="ultra-header">
        <div style="display:flex; justify-content: space-between; align-items: center; gap: 30px;">
            <div style="flex: 1;">
                <h1 class="ultra-title">Team Management</h1>
                <p style="color: rgba(255,255,255,0.6); font-weight: 600; margin-top: 10px; font-size: 16px;">
                    Active Squads: <span style="color: var(--neon-green);"><?php echo count($teams); ?></span> registered teams.
                </p>
            </div>

            <div style="display:flex; align-items: center; gap: 20px;">
                <div class="view-toggle-box">
                    <a href="?view=table<?php echo $search ? '&search=' . $search : ''; ?><?php echo $sport_filter ? '&sport=' . $sport_filter : ''; ?>" class="toggle-btn <?php echo $view_mode === 'table' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['dashboard']; ?>" class="icon-sm">
                        <span>List</span>
                    </a>
                    <a href="?view=card<?php echo $search ? '&search=' . $search : ''; ?><?php echo $sport_filter ? '&sport=' . $sport_filter : ''; ?>" class="toggle-btn <?php echo $view_mode === 'card' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['teams']; ?>" class="icon-sm">
                        <span>Grid</span>
                    </a>
                </div>
                <a href="add_team.php" class="elite-action-btn">
                    <img src="<?php echo $icons['add']; ?>" style="width: 18px; filter: brightness(0) invert(1);">
                    Create New Team
                </a>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <form action="" method="GET" class="bento-filter-bar">
            <input type="hidden" name="view" value="<?php echo $view_mode; ?>">
            <div style="flex: 2; position: relative; display: flex; align-items: center;">
                <img src="<?php echo $icons['search']; ?>" style="position: absolute; left: 20px; width: 18px; opacity: 0.4;">
                <input type="text" name="search" class="bento-search-input" placeholder="Search teams by name..." value="<?php echo htmlspecialchars($search); ?>" style="padding-left: 55px; width: 100%;">
            </div>

            <select name="sport" class="bento-select">
                <option value="0">All Sports</option>
                <?php foreach ($sports_list as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo $sport_filter == $s['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['sport_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="elite-action-btn filter-submit-btn">Apply Filters</button>
        </form>
    </div>

    <?php if ($view_mode === 'table'): ?>
        <!-- LIST VIEW -->
        <div class="ultra-table-container">
            <?php if (count($teams) > 0): ?>
                <?php foreach ($teams as $team): ?>
                    <div class="ultra-table-row">
                        <div class="identity-cluster">
                            <div class="sport-emoji-box" style="background: #f1f5f9; width: 50px; height: 50px; display:flex; align-items:center; justify-content:center; border-radius: 15px; overflow: hidden;">
                                <?php
                                $logo = getTeamLogo($team);
                                if ($logo): ?>
                                    <img src="<?php echo $logo; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else:
                                    $brand = getSportIcon(['image' => null, 'icon' => $team['sport_icon']]); // Simplified for list
                                ?>
                                    <?php echo htmlspecialchars($brand['value']); ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="meta-handle"><?php echo htmlspecialchars($team['team_name']); ?></h3>
                                <p class="meta-subtext"><?php echo htmlspecialchars($team['sport_name']); ?></p>
                            </div>
                        </div>

                        <div style="flex: 1.5; display: flex; gap: 40px;">
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext">Players</span>
                                <span style="font-weight: 700; color: var(--slate-deep);"><?php echo $team['player_count']; ?> Assigned</span>
                            </div>
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext">Performance</span>
                                <span style="font-weight: 700; color: var(--slate-deep);"><?php echo $team['matches_won']; ?> / <?php echo $team['matches_played']; ?> <span class="meta-subtext">Wins</span></span>
                            </div>
                        </div>

                        <div style="display:flex; gap: 12px; margin-left: 20px;">
                            <a href="assign_players.php?id=<?php echo $team['id']; ?>" class="action-btn" title="Assign Players" style="background: #eef2ff; border-radius: 15px; padding: 14px; transition: all 0.3s; text-decoration: none;">
                                <img src="<?php echo $icons['users']; ?>" style="width: 18px;">
                            </a>
                            <a href="edit_team.php?id=<?php echo $team['id']; ?>" class="action-btn edit" style="background: #f1f5f9; border-radius: 15px; padding: 14px; transition: all 0.3s; text-decoration: none;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 18px;">
                            </a>
                            <button class="action-btn delete delete-team-trigger"
                                data-id="<?php echo $team['id']; ?>"
                                data-name="<?php echo htmlspecialchars($team['team_name']); ?>"
                                style="background: #fff1f2; border-radius: 15px; padding: 14px; transition: all 0.3s; border:none; cursor:pointer;">
                                <img src="<?php echo $icons['delete']; ?>" style="width: 18px;">
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 100px; background: white; border-radius: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.02);">
                    <h2 style="color: var(--slate-deep); font-weight: 900;">No Teams Found</h2>
                    <p style="color: #94a3b8; font-weight: 600; margin-top: 10px;">Create a new team to get started.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- GRID VIEW (REDESIGNED CARDS) -->
        <div class="elite-grid">
            <?php if (count($teams) > 0): ?>
                <?php foreach ($teams as $team): ?>
                    <div class="elite-dossier">
                        <div class="dossier-header">
                            <div style="display:flex; gap: 18px; align-items: center;">
                                <div class="sport-emoji-box" style="background: #f8fafc; width: 64px; height: 64px; display:flex; align-items:center; justify-content:center; border-radius: 20px; border: 3px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.06); overflow: hidden;">
                                    <?php
                                    $logo = getTeamLogo($team);
                                    if ($logo): ?>
                                        <img src="<?php echo $logo; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else:
                                        $brand = getSportIcon(['image' => null, 'icon' => $team['sport_icon']]);
                                    ?>
                                        <?php if ($brand['type'] === 'image'): ?>
                                            <img src="<?php echo $brand['value']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span style="font-size: 32px;"><?php echo htmlspecialchars($brand['value']); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3 class="meta-handle" style="font-size: 20px; margin-bottom: 4px;"><?php echo htmlspecialchars($team['team_name']); ?></h3>
                                    <p class="meta-subtext" style="color: var(--primary-color); font-size: 11px;"><?php echo strtoupper($team['sport_name']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="dossier-details-grid" style="grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 15px;">
                            <div class="detail-box" style="margin-bottom: 0; text-align: center; padding: 12px 5px;">
                                <p class="meta-subtext" style="font-size: 9px; margin-bottom: 4px;">Roster</p>
                                <p style="font-weight: 900; color: var(--slate-deep); font-size: 15px;"><?php echo $team['player_count']; ?></p>
                            </div>
                            <div class="detail-box" style="margin-bottom: 0; text-align: center; padding: 12px 5px;">
                                <p class="meta-subtext" style="font-size: 9px; margin-bottom: 4px;">Played</p>
                                <p style="font-weight: 900; color: var(--slate-deep); font-size: 15px;"><?php echo $team['matches_played']; ?></p>
                            </div>
                            <div class="detail-box" style="margin-bottom: 0; text-align: center; padding: 12px 5px;">
                                <p class="meta-subtext" style="font-size: 9px; margin-bottom: 4px;">Victories</p>
                                <p style="font-weight: 900; color: var(--success-color); font-size: 15px;"><?php echo $team['matches_won']; ?></p>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 50px 50px 50px; gap: 12px; margin-top: 20px;">
                            <div style="flex: 1;"></div>
                            <a href="assign_players.php?id=<?php echo $team['id']; ?>" class="elite-action-btn" title="Assign Roster" style="padding: 0; display: flex; align-items: center; justify-content: center; height: 50px; border-radius: 16px; text-decoration: none;">
                                <img src="<?php echo $icons['users']; ?>" style="width: 20px; filter: brightness(0) invert(1);">
                            </a>

                            <a href="edit_team.php?id=<?php echo $team['id']; ?>" title="Edit Team" style="background: #f1f5f9; border-radius: 16px; display: flex; align-items: center; justify-content: center; height: 50px; transition: all 0.3s; text-decoration: none;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 18px;">
                            </a>

                            <button class="delete-team-trigger"
                                data-id="<?php echo $team['id']; ?>"
                                data-name="<?php echo htmlspecialchars($team['team_name']); ?>"
                                title="Delete Team"
                                style="background: #fff1f2; border-radius: 16px; border: none; color: #ef4444; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; height: 50px;">
                                <img src="<?php echo $icons['delete']; ?>" style="width: 18px; filter: invert(41%) sepia(85%) saturate(3474%) hue-rotate(338deg) brightness(98%) contrast(92%);">
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete team handler
        document.querySelectorAll('.delete-team-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                if (confirm(`Are you sure you want to permanently delete team: "${name}"?`)) {
                    window.location.href = `delete_team.php?id=${id}`;
                }
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>