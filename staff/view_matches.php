<?php

/**
 * Staff View Matches - Premium Interface
 */
require_once '../config.php';
requireLogin();

$page_title = 'View Matches';
$current_page = 'matches';

// Filters
$sport_filter = $_GET['sport'] ?? '';
$status_filter = $_GET['status'] ?? '';
$search = sanitize($_GET['search'] ?? '');

$query = "SELECT m.*, s.sport_name, s.icon as sport_icon, t1.team_name as team1, t2.team_name as team2,
          r.team1_score, r.team2_score, w.team_name as winner_name
          FROM matches m
          JOIN sports_categories s ON m.sport_id = s.id
          JOIN teams t1 ON m.team1_id = t1.id
          JOIN teams t2 ON m.team2_id = t2.id
          LEFT JOIN match_results r ON m.id = r.match_id
          LEFT JOIN teams w ON r.winner_team_id = w.id
          WHERE 1=1";

if ($sport_filter) $query .= " AND m.sport_id = " . intval($sport_filter);
if ($status_filter) $query .= " AND m.status = '" . $conn->real_escape_string($status_filter) . "'";
if ($search) {
    $query .= " AND (t1.team_name LIKE '%$search%' OR t2.team_name LIKE '%$search%' OR m.venue LIKE '%$search%')";
}

$query .= " ORDER BY m.match_date DESC, m.match_time DESC";
$matches = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

$sports = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status='active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="padding: 40px; background: #f8fafc;">
    <div class="ultra-header">
        <h1 class="ultra-title">Field Operations Schedule</h1>
        <p class="meta-subtext" style="color: rgba(255,255,255,0.7); margin-top: 10px;">Monitor all scheduled and historical athletic engagements.</p>
    </div>

    <!-- BENTO FILTER BAR -->
    <form action="" method="GET" class="bento-filter-bar" style="margin-top: -35px; margin-bottom: 40px; position: relative; z-index: 10;">
        <div style="flex: 2; position: relative; display: flex; align-items: center;">
            <img src="<?php echo $icons['search']; ?>" style="position: absolute; left: 20px; width: 18px; opacity: 0.4;">
            <input type="text" name="search" class="bento-search-input" placeholder="Search by team or venue..." value="<?php echo htmlspecialchars($search); ?>" style="padding-left: 55px; width: 100%;">
        </div>

        <select name="sport" class="bento-select">
            <option value="">All Disciplines</option>
            <?php foreach ($sports as $s): ?>
                <option value="<?php echo $s['id']; ?>" <?php echo $sport_filter == $s['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($s['sport_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="status" class="bento-select">
            <option value="">All States</option>
            <option value="scheduled" <?php echo $status_filter == 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
            <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
        </select>

        <button type="submit" class="elite-action-btn" style="min-width: 180px;">APPLY FILTERS</button>
        <a href="view_matches.php" class="btn-reset-light" style="padding: 18px 25px; border-radius: 18px;">RESET</a>
    </form>

    <!-- Matches Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 25px;">
        <?php foreach ($matches as $match): ?>
            <?php
            $is_completed = ($match['status'] === 'completed');
            $card_class = $is_completed ? 'success' : 'primary';
            $status_label = strtoupper($match['status']);
            ?>
            <div class="bento-card match-card <?php echo $card_class; ?>" style="padding: 0; overflow: hidden; border: 1px solid rgba(0,0,0,0.05);">
                <div style="padding: 20px; background: <?php echo $is_completed ? 'rgba(0, 200, 150, 0.03)' : 'rgba(140, 0, 255, 0.03)'; ?>; border-bottom: 1px dashed rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="sport-emoji-box" style="width: 45px; height: 45px; font-size: 22px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: white; border: 1px solid #f1f5f9; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                            <?php
                            $sport_icon = $match['sport_icon'] ?? '🏆';
                            if (strpos($sport_icon, '.') !== false || strpos($sport_icon, '/') !== false): ?>
                                <img src="../assets/images/<?php echo $sport_icon; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span style="line-height: 1;"><?php echo $sport_icon; ?></span>
                            <?php endif; ?>
                        </div>
                        <span style="font-weight: 800; color: var(--slate-deep); font-size: 12px; letter-spacing: 1px;"><?php echo strtoupper($match['sport_name']); ?></span>
                    </div>
                    <span class="tier-pill" style="background: <?php echo $is_completed ? 'rgba(0, 200, 150, 0.1)' : 'rgba(140, 0, 255, 0.1)'; ?>; color: <?php echo $is_completed ? 'var(--elite-green)' : 'var(--elite-purple)'; ?>; font-size: 10px;">
                        <?php echo $status_label; ?>
                    </span>
                </div>

                <div style="padding: 30px 20px; text-align: center;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                        <div style="flex: 1;">
                            <h4 style="font-weight: 900; color: var(--slate-deep); font-size: 15px;"><?php echo htmlspecialchars($match['team1']); ?></h4>
                        </div>
                        <div style="padding: 10px 15px; background: #f8fafc; border-radius: 50%; font-weight: 900; color: var(--elite-purple); font-size: 11px;">VS</div>
                        <div style="flex: 1;">
                            <h4 style="font-weight: 900; color: var(--slate-deep); font-size: 15px;"><?php echo htmlspecialchars($match['team2']); ?></h4>
                        </div>
                    </div>

                    <?php if ($is_completed): ?>
                        <div style="margin-top: 25px; padding: 15px; background: #f8fafc; border-radius: 15px; display: inline-flex; align-items: center; gap: 20px;">
                            <div style="text-align: center;">
                                <span class="meta-subtext" style="font-size: 9px;">FINAL SCORE</span>
                                <div style="font-weight: 900; color: var(--slate-deep); font-size: 24px;">
                                    <?php echo $match['team1_score']; ?> - <?php echo $match['team2_score']; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="padding: 20px; background: #fff; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span class="meta-subtext" style="display: block; font-size: 10px;">WINDOW // LOCATION</span>
                        <span style="font-weight: 700; color: #64748b; font-size: 12px;">
                            <?php echo formatDate($match['match_date'], 'M d'); ?> @ <?php echo formatTime($match['match_time']); ?> // <?php echo htmlspecialchars($match['venue']); ?>
                        </span>
                    </div>
                    <?php if (!$is_completed): ?>
                        <a href="enter_scores.php?id=<?php echo $match['id']; ?>" class="elite-action-btn" style="padding: 8px 15px; font-size: 10px;">ENTER SCORE</a>
                    <?php else: ?>
                        <div class="sport-emoji-box" style="width: 30px; height: 30px; background: rgba(0, 200, 150, 0.1); color: var(--elite-green);">
                            <img src="<?php echo $icons['results']; ?>" style="width: 14px; filter: invert(50%) sepia(80%) saturate(1000%) hue-rotate(120deg);">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>