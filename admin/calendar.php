<?php

/**
 * Match Calendar View
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Match Calendar';
$current_page = 'calendar';

// Get current month or from query
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Get matches for this month
$start_date = date('Y-m-01', mktime(0, 0, 0, $month, 1, $year));
$end_date = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

$matches = $conn->query("SELECT m.*, s.sport_name, t1.team_name as team1, t2.team_name as team2
                         FROM matches m
                         JOIN sports_categories s ON m.sport_id = s.id
                         JOIN teams t1 ON m.team1_id = t1.id
                         JOIN teams t2 ON m.team2_id = t2.id
                         WHERE m.match_date BETWEEN '$start_date' AND '$end_date'
                         ORDER BY m.match_date, m.match_time")->fetch_all(MYSQLI_ASSOC);

// Group by date
$matches_by_date = [];
foreach ($matches as $match) {
    $date = $match['match_date'];
    if (!isset($matches_by_date[$date])) {
        $matches_by_date[$date] = [];
    }
    $matches_by_date[$date][] = $match;
}

// Calendar calculations
$first_day = date('w', mktime(0, 0, 0, $month, 1, $year));
$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title"><img src="<?php echo $icons['matches']; ?>" class="stat-icon-img" style="width: 32px; height: 32px;"> Match Calendar</h1>
        <p class="page-subtitle">View scheduled matches in calendar format</p>
    </div>

    <!-- Month Navigation -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
            <a href="?month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>" class="btn btn-secondary">← Previous</a>
            <h2 style="margin: 0;"><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>
            <a href="?month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>" class="btn btn-secondary">Next →</a>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0;">
                <!-- Day headers -->
                <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day): ?>
                    <div style="padding: 12px; text-align: center; font-weight: 600; background: var(--bg-secondary); border: 1px solid var(--border-light);">
                        <?php echo $day; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Empty cells before first day -->
                <?php for ($i = 0; $i < $first_day; $i++): ?>
                    <div style="min-height: 120px; border: 1px solid var(--border-light); background: var(--bg-secondary);"></div>
                <?php endfor; ?>

                <!-- Calendar days -->
                <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                    <?php
                    $current_date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
                    $is_today = $current_date === date('Y-m-d');
                    $day_matches = $matches_by_date[$current_date] ?? [];
                    ?>
                    <div style="min-height: 120px; border: 1px solid var(--border-light); padding: 8px; vertical-align: top; <?php echo $is_today ? 'background: #fff3cd;' : ''; ?>">
                        <div style="font-weight: 600; margin-bottom: 4px; <?php echo $is_today ? 'color: var(--warning-color);' : ''; ?>">
                            <?php echo $day; ?>
                            <?php if ($is_today): ?> <span style="font-size: 12px;">●</span><?php endif; ?>
                        </div>

                        <?php if (!empty($day_matches)): ?>
                            <?php foreach ($day_matches as $match): ?>
                                <div style="font-size: 11px; padding: 4px; margin-bottom: 4px; background: var(--primary-color); color: white; border-radius: 4px; cursor: pointer;" onclick="window.location.href='view_results.php?id=<?php echo $match['id']; ?>'">
                                    <div style="font-weight: 600;"><?php echo formatTime($match['match_time']); ?></div>
                                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?php echo htmlspecialchars($match['team1']); ?> vs <?php echo htmlspecialchars($match['team2']); ?>
                                    </div>
                                    <div style="font-size: 10px; opacity: 0.9;"><?php echo $match['sport_name']; ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="margin-top: 24px; text-align: center;">
        <a href="schedule_match.php" class="btn btn-primary"><img src="<?php echo $icons['add']; ?>" class="action-icon-sm" style="filter: brightness(0) invert(1);"> Schedule New Match</a>
        <a href="manage_matches.php" class="btn btn-secondary">View All Matches</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>