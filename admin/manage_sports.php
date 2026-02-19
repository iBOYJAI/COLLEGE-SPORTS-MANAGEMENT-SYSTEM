<?php

/**
 * College Sports Management System  
 * Premium Sports Management - Dynamic Icons & Responsive Grid
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Sports Management';
$current_page = 'sports';

// Search and filter params
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'card';

// Build query
$where_conditions = ["1=1"];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "sport_name LIKE ?";
    $search_param = "%$search%";
    $params[] = $search_param;
    $types .= 's';
}

if ($status_filter !== 'all') {
    $where_conditions[] = "status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

$where_clause = implode(' AND ', $where_conditions);

// Fetch sports with player and team counts
$query = "SELECT s.*, 
          COUNT(DISTINCT ps.player_id) as player_count,
          COUNT(DISTINCT t.id) as team_count
          FROM sports_categories s
          LEFT JOIN player_sports ps ON s.id = ps.sport_id
          LEFT JOIN teams t ON s.id = t.sport_id
          WHERE $where_clause
          GROUP BY s.id
          ORDER BY s.created_at DESC";

if (!empty($params)) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

$sports = $result->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="background: #f8fafc; padding: 40px;">

    <!-- PREMIUM SPORTS HEADER -->
    <div class="ultra-header">
        <div style="display:flex; justify-content: space-between; align-items: center; gap: 30px;">
            <div style="flex: 1;">
                <h1 class="ultra-title">Sports Categories</h1>
                <p style="color: rgba(255,255,255,0.6); font-weight: 600; margin-top: 10px; font-size: 16px;">
                    Active Sports: <span style="color: var(--neon-green);"><?php echo count($sports); ?></span> registered sports.
                </p>
            </div>

            <div style="display:flex; align-items: center; gap: 20px;">
                <div class="view-toggle-box">
                    <a href="?view=table<?php echo $search ? '&search=' . $search : ''; ?>" class="toggle-btn <?php echo $view_mode === 'table' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['dashboard']; ?>" class="icon-sm">
                        <span>List View</span>
                    </a>
                    <a href="?view=card<?php echo $search ? '&search=' . $search : ''; ?>" class="toggle-btn <?php echo $view_mode === 'card' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['teams']; ?>" class="icon-sm">
                        <span>Grid View</span>
                    </a>
                </div>
                <a href="add_sport.php" class="elite-action-btn">
                    <img src="<?php echo $icons['add']; ?>" style="width: 18px; filter: brightness(0) invert(1);">
                    Add New Sport
                </a>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <form action="" method="GET" class="bento-filter-bar">
            <input type="hidden" name="view" value="<?php echo $view_mode; ?>">
            <div style="flex: 2; position: relative; display: flex; align-items: center;">
                <img src="<?php echo $icons['search']; ?>" style="position: absolute; left: 20px; width: 18px; opacity: 0.4;">
                <input type="text" name="search" class="bento-search-input" placeholder="Search sports by name or type..." value="<?php echo htmlspecialchars($search); ?>" style="padding-left: 55px; width: 100%;">
            </div>

            <select name="status" class="bento-select">
                <option value="all">All States</option>
                <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active Only</option>
                <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive Only</option>
            </select>

            <button type="submit" class="elite-action-btn filter-submit-btn">Apply Filters</button>
        </form>
    </div>

    <?php if ($view_mode === 'table'): ?>
        <!-- LIST VIEW -->
        <div class="ultra-table-container">
            <?php if (count($sports) > 0): ?>
                <?php foreach ($sports as $sport): ?>
                    <div class="ultra-table-row">
                        <div class="identity-cluster">
                            <div class="sport-emoji-box" style="background: #f1f5f9; width: 64px; height: 64px; display:flex; align-items:center; justify-content:center; border-radius: 20px; border: 2px solid #eef2ff; overflow: hidden;">
                                <?php
                                $brand = getSportIcon($sport);
                                if ($brand['type'] === 'image'): ?>
                                    <img src="<?php echo $brand['value']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <?php echo htmlspecialchars($brand['value']); ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="meta-handle"><?php echo htmlspecialchars($sport['sport_name']); ?></h3>
                                <p class="meta-subtext"><?php echo ucfirst($sport['category_type']); ?> Sport</p>
                            </div>
                        </div>

                        <div style="flex: 1.5; display: flex; gap: 30px;">
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext">Active Players</span>
                                <span style="font-weight: 700; color: var(--slate-deep);"><?php echo $sport['player_count']; ?> Registered</span>
                            </div>
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext">Teams</span>
                                <span style="font-weight: 700; color: var(--slate-deep);"><?php echo $sport['team_count']; ?> Active</span>
                            </div>
                        </div>

                        <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
                            <div class="status-neon <?php echo $sport['status']; ?>"></div>
                            <span class="meta-subtext" style="font-weight: 900;"><?php echo strtoupper($sport['status']); ?></span>
                        </div>

                        <div style="display:flex; gap: 12px; margin-left: 20px;">
                            <a href="edit_sport.php?id=<?php echo $sport['id']; ?>" class="action-btn edit" style="background: #f1f5f9; border-radius: 15px; padding: 14px; transition: all 0.3s; text-decoration: none;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 18px;">
                            </a>
                            <button class="action-btn delete delete-sport-trigger"
                                data-id="<?php echo $sport['id']; ?>"
                                data-name="<?php echo htmlspecialchars($sport['sport_name']); ?>"
                                data-players="<?php echo $sport['player_count']; ?>"
                                data-teams="<?php echo $sport['team_count']; ?>"
                                style="background: #fff1f2; border-radius: 15px; padding: 14px; transition: all 0.3s; border:none; cursor:pointer;">
                                <img src="<?php echo $icons['delete']; ?>" style="width: 18px;">
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 100px; background: white; border-radius: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.02);">
                    <h2 style="color: var(--slate-deep); font-weight: 900;">No Sports Found</h2>
                    <p style="color: #94a3b8; font-weight: 600; margin-top: 10px;">No sports categories match the search criteria.</p>
                    <a href="manage_sports.php" class="elite-action-btn" style="width: auto; display: inline-block; margin-top: 30px;">Reset Filters</a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- GRID VIEW (REDESIGNED CARDS) -->
        <div class="elite-grid">
            <?php if (count($sports) > 0): ?>
                <?php foreach ($sports as $sport): ?>
                    <div class="elite-dossier">
                        <div class="dossier-header">
                            <div style="display:flex; gap: 18px; align-items: center;">
                                <div class="sport-emoji-box" style="background: #f8fafc; width: 75px; height: 75px; display:flex; align-items:center; justify-content:center; border-radius: 25px; border: 3px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.06); overflow: hidden;">
                                    <?php
                                    $brand = getSportIcon($sport);
                                    if ($brand['type'] === 'image'): ?>
                                        <img src="<?php echo $brand['value']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <span style="font-size: 38px;"><?php echo htmlspecialchars($brand['value']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3 class="meta-handle" style="font-size: 20px; margin-bottom: 4px;"><?php echo htmlspecialchars($sport['sport_name']); ?></h3>
                                    <p class="meta-subtext" style="color: var(--primary-color); font-size: 11px;"><?php echo strtoupper($sport['category_type']); ?> CATEGORY</p>
                                </div>
                            </div>
                            <div class="status-neon <?php echo $sport['status']; ?>"></div>
                        </div>

                        <div class="detail-box">
                            <p class="meta-subtext" style="font-size: 10px; margin-bottom: 6px;">Description</p>
                            <p style="font-weight: 600; font-size: 13px; color: var(--text-secondary); line-height: 1.5; min-height: 40px;">
                                <?php
                                $desc = $sport['description'] ?? 'No description provided for this discipline.';
                                echo htmlspecialchars(substr($desc, 0, 85)) . (strlen($desc) > 85 ? '...' : '');
                                ?>
                            </p>
                        </div>

                        <div class="dossier-details-grid">
                            <div class="detail-box" style="margin-bottom: 0; text-align: center; padding: 12px 5px;">
                                <p class="meta-subtext" style="font-size: 9px; margin-bottom: 4px;">Players</p>
                                <p style="font-weight: 900; color: var(--slate-deep); font-size: 15px;"><?php echo $sport['player_count']; ?></p>
                            </div>
                            <div class="detail-box" style="margin-bottom: 0; text-align: center; padding: 12px 5px;">
                                <p class="meta-subtext" style="font-size: 9px; margin-bottom: 4px;">Teams</p>
                                <p style="font-weight: 900; color: var(--slate-deep); font-size: 15px;"><?php echo $sport['team_count']; ?></p>
                            </div>
                            <div class="detail-box" style="margin-bottom: 0; text-align: center; padding: 12px 5px;">
                                <p class="meta-subtext" style="font-size: 9px; margin-bottom: 4px;">Squad</p>
                                <p style="font-weight: 900; color: var(--slate-deep); font-size: 15px;"><?php echo $sport['min_players']; ?>-<?php echo $sport['max_players']; ?></p>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 50px 50px; gap: 12px; margin-top: 20px;">
                            <a href="edit_sport.php?id=<?php echo $sport['id']; ?>" class="elite-action-btn" title="Edit Sport" style="padding: 0; display: flex; align-items: center; justify-content: center; height: 50px; border-radius: 16px;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 20px; filter: brightness(0) invert(1);">
                            </a>
                            <button class="delete-sport-trigger"
                                data-id="<?php echo $sport['id']; ?>"
                                data-name="<?php echo htmlspecialchars($sport['sport_name']); ?>"
                                data-players="<?php echo $sport['player_count']; ?>"
                                data-teams="<?php echo $sport['team_count']; ?>"
                                title="Delete Sport"
                                style="background: #fff1f2; border-radius: 16px; border: none; color: #ef4444; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; height: 50px;">
                                <img src="<?php echo $icons['delete']; ?>" style="width: 18px; filter: invert(41%) sepia(85%) saturate(3474%) hue-rotate(338deg) brightness(98%) contrast(92%);">
                            </button>
                            <div style="width: 50px;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete sport handler
        document.querySelectorAll('.delete-sport-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const players = parseInt(this.getAttribute('data-players'));
                const teams = parseInt(this.getAttribute('data-teams'));

                if (players > 0 || teams > 0) {
                    alert(`Cannot delete "${name}". It has ${players} active players and ${teams} teams assigned.`);
                    return;
                }

                if (confirm(`Are you sure you want to permanently delete: "${name}"?`)) {
                    window.location.href = `delete_sport.php?id=${id}`;
                }
            });
        });

        // Elite button hover effects
        document.querySelectorAll('.elite-action-btn').forEach(btn => {
            btn.onmouseenter = () => btn.style.transform = 'translateY(-4px)';
            btn.onmouseleave = () => btn.style.transform = 'translateY(0)';
        });
    });
</script>

<?php include '../includes/footer.php'; ?>