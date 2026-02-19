<?php

/**
 * College Sports Management System
 * Premium Player Management - Optimized Filtering & Multi-View
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Player Management';
$current_page = 'players';

// Search and filters
$search = sanitize($_GET['search'] ?? '');
$sport_filter = intval($_GET['sport'] ?? 0);
$dept_filter = sanitize($_GET['department'] ?? 'all');
$year_filter = sanitize($_GET['year'] ?? 'all');
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'card';

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
    $where[] = "ps.sport_id = ?";
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
$count_query = "SELECT COUNT(DISTINCT p.id) as total FROM players p LEFT JOIN player_sports ps ON p.id = ps.player_id WHERE $where_clause";
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

// Fetch players
$query = "SELECT p.*, GROUP_CONCAT(DISTINCT s.sport_name SEPARATOR ', ') as sports 
          FROM players p 
          LEFT JOIN player_sports ps ON p.id = ps.player_id 
          LEFT JOIN sports_categories s ON ps.sport_id = s.id 
          WHERE $where_clause 
          GROUP BY p.id 
          ORDER BY p.created_at DESC 
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


include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="background: #f8fafc; padding: 40px;">

    <!-- PREMIUM PLAYERS HEADER -->
    <div class="ultra-header">
        <div style="display:flex; justify-content: space-between; align-items: center; gap: 30px;">
            <div style="flex: 1;">
                <h1 class="ultra-title">Player Management</h1>
                <p style="color: rgba(255,255,255,0.6); font-weight: 600; margin-top: 10px; font-size: 16px;">
                    Total Athletes: <span style="color: var(--neon-green);"><?php echo $total; ?></span> registered players.
                </p>
            </div>

            <div style="display:flex; align-items: center; gap: 20px;">
                <div class="view-toggle-box">
                    <a href="?view=table<?php echo $search ? '&search=' . $search : ''; ?>" class="toggle-btn <?php echo $view_mode === 'table' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['dashboard']; ?>" class="icon-sm">
                        <span>List</span>
                    </a>
                    <a href="?view=card<?php echo $search ? '&search=' . $search : ''; ?>" class="toggle-btn <?php echo $view_mode === 'card' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['teams']; ?>" class="icon-sm">
                        <span>Grid</span>
                    </a>
                </div>
                <a href="add_player.php" class="elite-action-btn">
                    <img src="<?php echo $icons['add']; ?>" style="width: 18px; filter: brightness(0) invert(1);">
                    Add New Player
                </a>
            </div>
        </div>

        <!-- BENTO FILTER BAR -->
        <form action="" method="GET" class="bento-filter-bar">
            <input type="hidden" name="view" value="<?php echo $view_mode; ?>">

            <div style="flex: 2; position: relative; display: flex; align-items: center;">
                <img src="<?php echo $icons['search']; ?>" style="position: absolute; left: 20px; width: 18px; opacity: 0.4;">
                <input type="text" name="search" class="bento-search-input" placeholder="Search by name or registration number..." value="<?php echo htmlspecialchars($search); ?>" style="padding-left: 55px; width: 100%;">
            </div>

            <select name="sport" class="bento-select">
                <option value="0">All Sports</option>
                <?php foreach ($sports_list as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo $sport_filter == $s['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['sport_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="department" class="bento-select">
                <option value="all">All Depts</option>
                <?php
                $depts = ['Computer Science', 'Electronics', 'Mechanical', 'Civil', 'Information Technology', 'Electrical'];
                foreach ($depts as $d): ?>
                    <option value="<?php echo $d; ?>" <?php echo $dept_filter === $d ? 'selected' : ''; ?>><?php echo $d; ?></option>
                <?php endforeach; ?>
            </select>

            <select name="year" class="bento-select">
                <option value="all">All Years</option>
                <?php $years = ['I', 'II', 'III', 'IV'];
                foreach ($years as $y): ?>
                    <option value="<?php echo $y; ?>" <?php echo $year_filter === $y ? 'selected' : ''; ?>><?php echo $y; ?> Year</option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="elite-action-btn">Filter</button>
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
                                <span class="meta-subtext">Department</span>
                                <span style="font-weight: 700; color: var(--slate-deep);"><?php echo $player['department']; ?></span>
                            </div>
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext">Academic Year</span>
                                <span style="font-weight: 700; color: var(--slate-deep);"><?php echo $player['year']; ?> Year</span>
                            </div>
                        </div>

                        <div style="flex: 2;">
                            <span class="meta-subtext">Registered Sports</span>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px;">
                                <?php if ($player['sports']): ?>
                                    <?php foreach (explode(', ', $player['sports']) as $sport): ?>
                                        <span class="tier-pill" style="background: var(--primary-lighter); color: var(--primary-color); padding: 4px 10px; font-size: 10px; border-radius: 10px;"><?php echo htmlspecialchars($sport); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="meta-subtext">None</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="display:flex; gap: 12px; margin-left: 20px;">
                            <a href="view_player.php?id=<?php echo $player['id']; ?>" class="action-btn view" style="background: #f1f5f9; border-radius: 15px; padding: 14px; transition: all 0.3s; text-decoration: none;">
                                <img src="<?php echo $icons['view']; ?>" style="width: 18px;">
                            </a>
                            <a href="edit_player.php?id=<?php echo $player['id']; ?>" class="action-btn edit" style="background: #f1f5f9; border-radius: 15px; padding: 14px; transition: all 0.3s; text-decoration: none;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 18px;">
                            </a>
                            <button class="action-btn delete delete-player"
                                data-id="<?php echo $player['id']; ?>"
                                data-name="<?php echo htmlspecialchars($player['name']); ?>"
                                style="background: #fff1f2; border-radius: 15px; padding: 14px; transition: all 0.3s; border:none; cursor:pointer;">
                                <img src="<?php echo $icons['delete']; ?>" style="width: 18px;">
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 100px; background: white; border-radius: 40px;">
                    <h2 style="color: var(--slate-deep); font-weight: 900;">No Players Found</h2>
                    <p style="color: #94a3b8; font-weight: 600;">Try adjusting your filters.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- GRID VIEW -->
        <div class="elite-grid">
            <?php if (count($players) > 0): ?>
                <?php foreach ($players as $player): ?>
                    <div class="elite-dossier" style="gap: 15px;">
                        <div class="dossier-header">
                            <div style="display:flex; gap: 18px; align-items: center;">
                                <img src="<?php echo getPlayerPhoto($player['id'], $player['photo'], $player['gender']); ?>" class="ultra-avatar dossier-avatar">
                                <div>
                                    <h3 class="meta-handle" style="font-size: 18px; margin-bottom: 2px;"><?php echo htmlspecialchars($player['name']); ?></h3>
                                    <p class="meta-subtext" style="color: var(--primary-color); font-size: 11px;"><?php echo $player['register_number']; ?></p>
                                </div>
                            </div>
                            <span class="tier-pill" style="font-size: 9px;"><?php echo strtoupper($player['year']); ?> YEAR</span>
                        </div>

                        <div class="detail-box">
                            <p class="meta-subtext" style="font-size: 10px; margin-bottom: 4px;">Sports</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                <?php if ($player['sports']): ?>
                                    <?php foreach (explode(', ', $player['sports']) as $sport): ?>
                                        <span style="background: #f1f5f9; color: #475569; padding: 3px 8px; font-size: 9px; border-radius: 6px; font-weight: 700;">#<?php echo htmlspecialchars($sport); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="meta-subtext">None</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="dossier-details-grid">
                            <div class="detail-box" style="margin-bottom: 0;">
                                <p class="meta-subtext" style="font-size: 10px; margin-bottom: 4px;">Department</p>
                                <p style="font-weight: 700; font-size: 12px; color: var(--slate-deep);"><?php echo $player['department']; ?></p>
                            </div>
                            <div class="detail-box" style="margin-bottom: 0;">
                                <p class="meta-subtext" style="font-size: 10px; margin-bottom: 4px;">Age</p>
                                <p style="font-weight: 700; font-size: 12px; color: var(--slate-deep);"><?php echo $player['age']; ?> Years</p>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 50px 50px 50px; gap: 10px; margin-top: 10px;">
                            <div style="flex: 1;"></div>
                            <a href="view_player.php?id=<?php echo $player['id']; ?>" class="elite-action-btn" title="View Profile" style="padding: 0; display: flex; align-items: center; justify-content: center; height: 50px; border-radius: 12px; background: #f8fafc; color: var(--slate-deep); text-decoration: none;">
                                <img src="<?php echo $icons['view']; ?>" style="width: 18px;">
                            </a>
                            <a href="edit_player.php?id=<?php echo $player['id']; ?>" class="elite-action-btn" title="Edit Player" style="padding: 0; display: flex; align-items: center; justify-content: center; height: 50px; border-radius: 12px; text-decoration: none;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 18px; filter: brightness(0) invert(1);">
                            </a>
                            <button class="delete-player" data-id="<?php echo $player['id']; ?>" data-name="<?php echo htmlspecialchars($player['name']); ?>" title="Delete Player" style="background: #fff1f2; border-radius: 12px; border: none; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; height: 50px;">
                                <img src="<?php echo $icons['delete']; ?>" style="width: 16px; filter: invert(41%) sepia(85%) saturate(3474%) hue-rotate(338deg) brightness(98%) contrast(92%);">
                            </button>
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
            echo generatePagination($page_num, $total_pages, 'manage_players.php?' . $query_string);
            ?>
        </div>
    <?php endif; ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-player').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                if (confirm(`Are you sure you want to delete player: ${name}?`)) {
                    window.location.href = `delete_player.php?id=${id}`;
                }
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>