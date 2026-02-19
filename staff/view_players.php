<?php

/**
 * Staff Athlete Registry - Premium Reworked Interface
 * Includes Multi-View, Advanced Filtering, and Data Depth
 */

require_once '../config.php';
requireLogin();

$page_title = 'Athlete Registry';
$current_page = 'players';

// Search and filters
$search = sanitize($_GET['search'] ?? '');
$sport_filter = intval($_GET['sport'] ?? 0);
$dept_filter = sanitize($_GET['department'] ?? 'all');
$year_filter = sanitize($_GET['year'] ?? 'all');
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'table'; // Default to table for staff utility

// Build query
$where = ["p.status = 'active'"];
$params = [];
$types = '';

if ($search) {
    $where[] = "(p.name LIKE ? OR p.register_number LIKE ?)";
    $s = "%$search%";
    $params[] = $s;
    $params[] = $s;
    $types .= 'ss';
}

if ($sport_filter) {
    // We need to check if the player is in this sport via player_sports table
    $where[] = "p.id IN (SELECT player_id FROM player_sports WHERE sport_id = ?)";
    $params[] = $sport_filter;
    $types .= 'i';
}

if ($dept_filter !== 'all') {
    $where[] = "p.department = ?";
    $params[] = $dept_filter;
    $types .= 's';
}

if ($year_filter !== 'all') {
    $where[] = "p.year = ?";
    $params[] = $year_filter;
    $types .= 's';
}

$where_clause = implode(' AND ', $where);

// Pagination
$per_page = ($view_mode === 'card') ? 12 : 15;
$page_num = intval($_GET['page'] ?? 1);
$offset = ($page_num - 1) * $per_page;

// Count total
$count_query = "SELECT COUNT(*) as total FROM players p WHERE $where_clause";
if ($params) {
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $total = $count_stmt->get_result()->fetch_assoc()['total'];
    $count_stmt->close();
} else {
    $total = $conn->query($count_query)->fetch_assoc()['total'];
}

$total_pages = ceil($total / $per_page);

// Fetch players with sports data depth
$query = "SELECT p.*, GROUP_CONCAT(DISTINCT s.sport_name SEPARATOR ', ') as sports 
          FROM players p 
          LEFT JOIN player_sports ps ON p.id = ps.player_id 
          LEFT JOIN sports_categories s ON ps.sport_id = s.id 
          WHERE $where_clause 
          GROUP BY p.id 
          ORDER BY p.name ASC 
          LIMIT ? OFFSET ?";

$params_fetch = $params;
$params_fetch[] = $per_page;
$params_fetch[] = $offset;
$types_fetch = $types . 'ii';

$stmt = $conn->prepare($query);
$stmt->bind_param($types_fetch, ...$params_fetch);
$stmt->execute();
$players = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get sports for filter
$sports_list = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status = 'active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);
// Get unique departments for filter
$depts_list = $conn->query("SELECT DISTINCT department FROM players WHERE status = 'active' ORDER BY department")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="background: #f8fafc; padding: 40px;">

    <!-- ULTRA HEADER -->
    <div class="ultra-header">
        <div style="display:flex; justify-content: space-between; align-items: center; gap: 30px;">
            <div style="flex: 1;">
                <h1 class="ultra-title">Athlete Cadre</h1>
                <p style="color: rgba(255,255,255,0.7); font-weight: 600; margin-top: 10px; font-size: 16px;">
                    Operational Strength: <span style="color: var(--neon-green);"><?php echo $total; ?></span> identified personnel.
                </p>
            </div>

            <div style="display:flex; align-items: center; gap: 20px;">
                <div class="view-toggle-box">
                    <a href="?view=table<?php echo $search ? '&search=' . $search : ''; ?><?php echo $sport_filter ? '&sport=' . $sport_filter : ''; ?><?php echo $dept_filter !== 'all' ? '&department=' . $dept_filter : ''; ?><?php echo $year_filter !== 'all' ? '&year=' . $year_filter : ''; ?>" class="toggle-btn <?php echo $view_mode === 'table' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['dashboard']; ?>" class="icon-sm">
                        <span>Table</span>
                    </a>
                    <a href="?view=card<?php echo $search ? '&search=' . $search : ''; ?><?php echo $sport_filter ? '&sport=' . $sport_filter : ''; ?><?php echo $dept_filter !== 'all' ? '&department=' . $dept_filter : ''; ?><?php echo $year_filter !== 'all' ? '&year=' . $year_filter : ''; ?>" class="toggle-btn <?php echo $view_mode === 'card' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['teams']; ?>" class="icon-sm">
                        <span>Grid</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- BENTO FILTER BAR -->
        <form action="" method="GET" class="bento-filter-bar">
            <input type="hidden" name="view" value="<?php echo $view_mode; ?>">

            <div style="flex: 2; position: relative; display: flex; align-items: center;">
                <img src="<?php echo $icons['search']; ?>" style="position: absolute; left: 20px; width: 18px; opacity: 0.4;">
                <input type="text" name="search" class="bento-search-input" placeholder="Search by name or register number..." value="<?php echo htmlspecialchars($search); ?>" style="padding-left: 55px; width: 100%;">
            </div>

            <select name="sport" class="bento-select">
                <option value="0">All Disciplines</option>
                <?php foreach ($sports_list as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo $sport_filter == $s['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['sport_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="department" class="bento-select">
                <option value="all">All Departments</option>
                <?php foreach ($depts_list as $d): ?>
                    <option value="<?php echo $d['department']; ?>" <?php echo $dept_filter === $d['department'] ? 'selected' : ''; ?>><?php echo $d['department']; ?></option>
                <?php endforeach; ?>
            </select>

            <select name="year" class="bento-select">
                <option value="all">All Years</option>
                <?php foreach (['I', 'II', 'III', 'IV'] as $y): ?>
                    <option value="<?php echo $y; ?>" <?php echo $year_filter === $y ? 'selected' : ''; ?>><?php echo $y; ?> Year</option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="elite-action-btn">APPLY FILTERS</button>
        </form>
    </div>

    <?php if ($view_mode === 'table'): ?>
        <!-- LIST VIEW -->
        <div class="ultra-table-container">
            <?php if (count($players) > 0): ?>
                <?php foreach ($players as $player): ?>
                    <div class="ultra-table-row">
                        <div class="identity-cluster">
                            <img src="<?php echo getPlayerPhoto($player['id'], $player['photo'], $player['gender']); ?>" class="ultra-avatar">
                            <div>
                                <h3 class="meta-handle"><?php echo htmlspecialchars($player['name']); ?></h3>
                                <p class="meta-subtext"><?php echo htmlspecialchars($player['register_number']); ?></p>
                            </div>
                        </div>

                        <div style="flex: 1.5; display: flex; gap: 30px;">
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext" style="font-size: 10px;">DEPT // YEAR</span>
                                <span style="font-weight: 700; color: var(--slate-deep); font-size: 13px;"><?php echo $player['department']; ?> (<?php echo $player['year']; ?>)</span>
                            </div>
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext" style="font-size: 10px;">COMMS</span>
                                <span style="font-weight: 600; color: #64748b; font-size: 12px;"><?php echo $player['mobile']; ?></span>
                            </div>
                        </div>

                        <div style="flex: 2;">
                            <span class="meta-subtext" style="font-size: 10px;">DIVISION ASSIGNMENTS</span>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px;">
                                <?php if ($player['sports']): ?>
                                    <?php foreach (explode(', ', $player['sports']) as $sport): ?>
                                        <span class="tier-pill" style="background: var(--primary-lighter); color: var(--primary-color); padding: 4px 10px; font-size: 10px; border-radius: 10px; font-weight: 800;"><?php echo strtoupper($sport); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="meta-subtext">No assignments</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="display:flex; gap: 12px; margin-left: 20px;">
                            <a href="view_player.php?id=<?php echo $player['id']; ?>" class="action-btn view" style="background: #f1f5f9; border-radius: 15px; padding: 14px; transition: all 0.3s; text-decoration: none;" title="View Profile">
                                <img src="<?php echo $icons['view']; ?>" style="width: 18px;">
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 100px; background: white; border-radius: 40px;">
                    <h2 style="color: var(--slate-deep); font-weight: 900;">No Personnel Found</h2>
                    <p style="color: #94a3b8; font-weight: 600;">System could not resolve the specified criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- DOSSIER CARD VIEW -->
        <div class="elite-grid">
            <?php if (count($players) > 0): ?>
                <?php foreach ($players as $player): ?>
                    <div class="elite-dossier" style="gap: 15px;">
                        <div class="dossier-header" style="justify-content: space-between;">
                            <div style="display:flex; gap: 18px; align-items: center;">
                                <img src="<?php echo getPlayerPhoto($player['id'], $player['photo'], $player['gender']); ?>" class="ultra-avatar dossier-avatar">
                                <div>
                                    <h3 class="meta-handle" style="font-size: 18px; margin-bottom: 2px;"><?php echo htmlspecialchars($player['name']); ?></h3>
                                    <p class="meta-subtext" style="color: var(--elite-purple); font-size: 11px; font-family: 'JetBrains Mono'; font-weight: 800;"><?php echo $player['register_number']; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="detail-box">
                            <p class="meta-subtext" style="font-size: 10px; margin-bottom: 8px;">DIVISION ASSIGNMENTS</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                <?php if ($player['sports']): ?>
                                    <?php foreach (explode(', ', $player['sports']) as $sport): ?>
                                        <span style="background: #eef2ff; color: #4338ca; padding: 4px 10px; font-size: 10px; border-radius: 8px; font-weight: 800;">#<?php echo strtoupper($sport); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="meta-subtext">Unassigned</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="dossier-details-grid">
                            <div class="detail-box" style="margin-bottom: 0;">
                                <p class="meta-subtext" style="font-size: 10px; margin-bottom: 4px;">AFFILIATION</p>
                                <p style="font-weight: 800; font-size: 12px; color: var(--slate-deep);"><?php echo $player['department']; ?></p>
                            </div>
                            <div class="detail-box" style="margin-bottom: 0;">
                                <p class="meta-subtext" style="font-size: 10px; margin-bottom: 4px;">RANK</p>
                                <p style="font-weight: 800; font-size: 12px; color: var(--slate-deep);"><?php echo $player['year']; ?> Year</p>
                            </div>
                        </div>

                        <div style="margin-top: 10px;">
                            <a href="view_player.php?id=<?php echo $player['id']; ?>" class="elite-action-btn" style="width: 100%; justify-content: center; height: 50px; border-radius: 15px; text-decoration: none; background: #f8fafc; color: var(--slate-deep); border: 1px solid #e2e8f0;">
                                <img src="<?php echo $icons['view']; ?>" style="width: 18px; margin-right: 10px;">
                                VIEW PROFILE
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($total_pages > 1): ?>
        <div style="margin-top: 40px; display: flex; justify-content: center;">
            <?php
            $query_params = $_GET;
            unset($query_params['page']);
            $query_string = http_build_query($query_params);
            echo generatePagination($page_num, $total_pages, 'view_players.php?' . $query_string);
            ?>
        </div>
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>