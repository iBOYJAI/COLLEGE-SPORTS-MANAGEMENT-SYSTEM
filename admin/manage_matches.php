<?php

/**
 * College Sports Management System
 * Premium Match Management - Dynamic Scheduling & Analysis
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Match Schedule';
$current_page = 'matches';

// Search and filter params
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sport_filter = isset($_GET['sport']) ? intval($_GET['sport']) : 0;
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : 'all';
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'table';

// Build query
$where_conditions = ["m.status != 'cancelled'"];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(t1.team_name LIKE ? OR t2.team_name LIKE ? OR m.venue LIKE ?)";
    $s = "%$search%";
    $params[] = $s;
    $params[] = $s;
    $params[] = $s;
    $types .= 'sss';
}

if ($sport_filter > 0) {
    $where_conditions[] = "m.sport_id = ?";
    $params[] = $sport_filter;
    $types .= 'i';
}

if ($status_filter !== 'all') {
    $where_conditions[] = "m.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

$where_clause = implode(' AND ', $where_conditions);

// Fetch matches with matchup details
$query = "SELECT m.*, s.sport_name, s.icon as sport_icon,
          t1.team_name as team1, t2.team_name as team2,
          mr.team1_score, mr.team2_score
          FROM matches m
          JOIN sports_categories s ON m.sport_id = s.id
          JOIN teams t1 ON m.team1_id = t1.id
          JOIN teams t2 ON m.team2_id = t2.id
          LEFT JOIN match_results mr ON m.id = mr.match_id
          WHERE $where_clause
          ORDER BY m.match_date DESC, m.match_time DESC";

if (!empty($params)) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}

$matches = $result->fetch_all(MYSQLI_ASSOC);

// Get sports for filtering
$sports_list = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status = 'active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="background: #f8fafc; padding: 40px;">

    <!-- PREMIUM MATCHES HEADER -->
    <div class="ultra-header">
        <div style="display:flex; justify-content: space-between; align-items: center; gap: 30px;">
            <div style="flex: 1;">
                <h1 class="ultra-title">Match Schedule</h1>
                <p style="color: rgba(255,255,255,0.6); font-weight: 600; margin-top: 10px; font-size: 16px;">
                    Event Registry: <span style="color: var(--neon-green);"><?php echo count($matches); ?></span> scheduled fixtures.
                </p>
            </div>

            <div style="display:flex; align-items: center; gap: 20px;">
                <div class="view-toggle-box">
                    <a href="?view=table<?php echo $search ? '&search=' . $search : ''; ?>" class="toggle-btn <?php echo $view_mode === 'table' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['dashboard']; ?>" class="icon-sm">
                        <span>List</span>
                    </a>
                    <a href="?view=card<?php echo $search ? '&search=' . $search : ''; ?>" class="toggle-btn <?php echo $view_mode === 'card' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['matches']; ?>" class="icon-sm">
                        <span>Grid</span>
                    </a>
                </div>
                <a href="schedule_match.php" class="elite-action-btn">
                    <img src="<?php echo $icons['add']; ?>" style="width: 18px; filter: brightness(0) invert(1);">
                    Schedule Match
                </a>
            </div>
        </div>

        <!-- BENTO FILTER BAR -->
        <form action="" method="GET" class="bento-filter-bar">
            <input type="hidden" name="view" value="<?php echo $view_mode; ?>">
            <div style="flex: 2; position: relative; display: flex; align-items: center;">
                <img src="<?php echo $icons['search']; ?>" style="position: absolute; left: 20px; width: 18px; opacity: 0.4;">
                <input type="text" name="search" class="bento-search-input" placeholder="Search by team or venue..." value="<?php echo htmlspecialchars($search); ?>" style="padding-left: 55px; width: 100%;">
            </div>

            <select name="sport" class="bento-select">
                <option value="0">All Sports</option>
                <?php foreach ($sports_list as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo $sport_filter == $s['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['sport_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="status" class="bento-select">
                <option value="all">All Status</option>
                <option value="scheduled" <?php echo $status_filter === 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>

            <button type="submit" class="elite-action-btn">Filter</button>
        </form>
    </div>

    <?php if ($view_mode === 'table'): ?>
        <!-- LIST VIEW -->
        <div class="ultra-table-container">
            <?php if (count($matches) > 0): ?>
                <?php foreach ($matches as $match): ?>
                    <div class="ultra-table-row">
                        <div class="identity-cluster" style="flex: 2;">
                            <div class="sport-emoji-box" style="font-size: 24px; background: #f1f5f9; width: 50px; height: 50px; display:flex; align-items:center; justify-content:center; border-radius: 15px;">
                                <?php echo htmlspecialchars($match['sport_icon'] ?? '🏆'); ?>
                            </div>
                            <div>
                                <h3 class="meta-handle" style="font-size: 16px;">
                                    <?php echo htmlspecialchars($match['team1']); ?>
                                    <span style="color: var(--primary-color); font-size: 12px; margin: 0 5px;">VS</span>
                                    <?php echo htmlspecialchars($match['team2']); ?>
                                </h3>
                                <p class="meta-subtext"><?php echo htmlspecialchars($match['sport_name']); ?> Fixture</p>
                            </div>
                        </div>

                        <div style="flex: 1.5; display: flex; gap: 30px;">
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext">Schedule</span>
                                <span style="font-weight: 700; color: var(--slate-deep); font-size: 13px;">
                                    <?php echo formatDate($match['match_date'], 'M d, Y'); ?> @ <?php echo formatTime($match['match_time']); ?>
                                </span>
                            </div>
                            <div style="display:flex; flex-direction:column;">
                                <span class="meta-subtext">Venue</span>
                                <span style="font-weight: 700; color: var(--slate-deep); font-size: 13px;"><?php echo htmlspecialchars($match['venue']); ?></span>
                            </div>
                        </div>

                        <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
                            <?php if ($match['status'] === 'completed'): ?>
                                <div style="text-align: center;">
                                    <span class="meta-subtext" style="display: block; margin-bottom: 2px;">Score</span>
                                    <span style="font-weight: 900; background: var(--success-lighter); color: var(--success-color); padding: 5px 12px; border-radius: 10px; font-size: 14px;">
                                        <?php echo $match['team1_score']; ?> - <?php echo $match['team2_score']; ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <span class="tier-pill" style="background: var(--warning-lighter); color: var(--warning-color);">UPCOMING</span>
                            <?php endif; ?>
                        </div>

                        <div style="display:flex; gap: 10px; margin-left: 20px;">
                            <?php if ($match['status'] === 'scheduled'): ?>
                                <a href="enter_results.php?id=<?php echo $match['id']; ?>" class="action-btn" title="Enter Results" style="background: var(--success-lighter); border-radius: 15px; padding: 14px; text-decoration: none;">
                                    <img src="<?php echo $icons['results']; ?>" style="width: 18px; filter: invert(41%) sepia(85%) saturate(3474%) hue-rotate(120deg) brightness(98%) contrast(92%);">
                                </a>
                            <?php else: ?>
                                <a href="view_results.php?id=<?php echo $match['id']; ?>" class="action-btn" title="View Results" style="background: #f1f5f9; border-radius: 15px; padding: 14px; text-decoration: none;">
                                    <img src="<?php echo $icons['view']; ?>" style="width: 18px;">
                                </a>
                            <?php endif; ?>

                            <a href="edit_match.php?id=<?php echo $match['id']; ?>" class="action-btn" title="Edit Match" style="background: #f1f5f9; border-radius: 15px; padding: 14px; text-decoration: none;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 18px;">
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 100px; background: white; border-radius: 40px;">
                    <h2 style="color: var(--slate-deep); font-weight: 900;">No Matches Found</h2>
                    <p style="color: #94a3b8; font-weight: 600;">Check filters or schedule a new match.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- GRID VIEW (MATCHUP CARDS) - GLASS ELITE REDESIGN -->
        <div class="elite-grid">
            <?php if (count($matches) > 0): ?>
                <?php foreach ($matches as $match): ?>
                    <div class="bento-card <?php echo $match['status'] === 'completed' ? 'success' : 'primary'; ?> match-card-pro" style="padding: 0; position: relative; overflow: hidden; height: 100%;">
                        <?php if ($match['status'] === 'completed'): ?>
                            <div class="tier-pill" style="position: absolute; top: 15px; right: 15px; background: var(--elite-green); color: black; z-index: 10;">COMPLETED</div>
                        <?php endif; ?>

                        <!-- Card Header Accent -->
                        <div style="background: <?php echo $match['status'] === 'completed' ? 'var(--elite-green)' : 'var(--elite-purple)'; ?>; padding: 12px; text-align: center;">
                            <span style="color: <?php echo $match['status'] === 'completed' ? 'black' : 'white'; ?>; font-weight: 800; font-size: 11px; letter-spacing: 2px;"><?php echo strtoupper($match['sport_name']); ?></span>
                        </div>

                        <!-- Matchup Content -->
                        <div style="padding: 30px;">
                            <div style="display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 20px; margin-bottom: 25px;">
                                <div style="text-align: center;">
                                    <div style="width: 65px; height: 65px; background: #f8fafc; border-radius: 22px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.03); font-weight: 900; color: var(--elite-purple); border: 2px solid #eef2ff;">
                                        <?php echo substr($match['team1'], 0, 1); ?>
                                    </div>
                                    <h4 style="font-size: 15px; color: var(--slate-deep); font-weight: 900; line-height: 1.2; height: 36px; display: flex; align-items: center; justify-content: center; overflow: hidden;"><?php echo htmlspecialchars($match['team1']); ?></h4>
                                </div>

                                <div class="vs" style="margin-top: -30px;">VS</div>

                                <div style="text-align: center;">
                                    <div style="width: 65px; height: 65px; background: #f8fafc; border-radius: 22px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.03); font-weight: 900; color: var(--elite-purple); border: 2px solid #eef2ff;">
                                        <?php echo substr($match['team2'], 0, 1); ?>
                                    </div>
                                    <h4 style="font-size: 15px; color: var(--slate-deep); font-weight: 900; line-height: 1.2; height: 36px; display: flex; align-items: center; justify-content: center; overflow: hidden;"><?php echo htmlspecialchars($match['team2']); ?></h4>
                                </div>
                            </div>

                            <div style="background: rgba(148, 163, 184, 0.05); padding: 18px; border-radius: 20px; margin-bottom: 25px; text-align: center; border: 1px solid rgba(255,255,255,0.5);">
                                <p style="font-size: 13px; color: var(--slate-deep); font-weight: 700; margin-bottom: 6px;">📅 <?php echo formatDate($match['match_date'], 'M d, Y'); ?></p>
                                <p style="font-size: 12px; color: #94a3b8; font-weight: 600;">📍 <?php echo htmlspecialchars($match['venue']); ?> @ <?php echo formatTime($match['match_time']); ?></p>
                            </div>

                            <?php if ($match['status'] === 'completed'): ?>
                                <div style="margin-bottom: 25px; text-align: center; padding: 10px; background: rgba(140, 0, 255, 0.02); border-radius: 15px;">
                                    <span style="display:block; font-size: 10px; color: #94a3b8; margin-bottom: 5px; font-weight: 800; letter-spacing: 1.5px;">FINAL INTEL</span>
                                    <div style="font-size: 30px; font-weight: 900; color: var(--elite-purple); letter-spacing: 2px;">
                                        <?php echo $match['team1_score']; ?> : <?php echo $match['team2_score']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Action Commands -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <?php if ($match['status'] === 'scheduled'): ?>
                                    <a href="enter_results.php?id=<?php echo $match['id']; ?>" class="elite-action-btn" style="padding: 12px; text-decoration: none; text-align: center; font-size: 11px;">Update Score</a>
                                <?php else: ?>
                                    <a href="enter_results.php?id=<?php echo $match['id']; ?>" class="btn-reset-light" style="padding: 12px; text-decoration: none; text-align: center; font-size: 11px; background: rgba(140, 0, 255, 0.05);">Edit Intel</a>
                                <?php endif; ?>
                                <a href="edit_match.php?id=<?php echo $match['id']; ?>" class="btn-reset-light" style="padding: 12px; text-decoration: none; text-align: center; font-size: 11px;">Modify</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bento-card" style="grid-column: span 3; text-align: center; padding: 80px;">
                    <span style="font-size: 50px;">📡</span>
                    <h3 style="margin-top: 20px; font-weight: 900; color: var(--slate-deep);">No Transmission Detected</h3>
                    <p style="color: #94a3b8; font-weight: 600;">Check filters or schedule a new match.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>