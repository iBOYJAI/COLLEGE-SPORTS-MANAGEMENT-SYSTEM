<?php

/**
 * Staff Dashboard - Redesigned Premium Interface
 */

require_once '../config.php';
requireLogin();

$page_title = 'Staff Dashboard';
$current_page = 'dashboard';

// Get upcoming matches
$upcoming_matches = $conn->query("SELECT m.*, s.sport_name, s.icon as sport_icon, t1.team_name as team1, t2.team_name as team2
                                  FROM matches m
                                  JOIN sports_categories s ON m.sport_id = s.id
                                  JOIN teams t1 ON m.team1_id = t1.id
                                  JOIN teams t2 ON m.team2_id = t2.id
                                  WHERE m.match_date >= CURDATE() AND m.status = 'scheduled'
                                  ORDER BY m.match_date, m.match_time
                                  LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Get statistics
$total_players = $conn->query("SELECT COUNT(*) as c FROM players WHERE status = 'active'")->fetch_assoc()['c'];
$total_teams = $conn->query("SELECT COUNT(*) as c FROM teams WHERE status = 'active'")->fetch_assoc()['c'];
$completed_matches = $conn->query("SELECT COUNT(*) as c FROM matches WHERE status = 'completed'")->fetch_assoc()['c'];

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="background: #f8fafc; padding: 40px;">

    <!-- STAFF PORTAL HEADER -->
    <div class="ultra-header">
        <div style="display:flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="ultra-title">Staff Command Centre</h1>
                <p style="color: rgba(255,255,255,0.7); font-weight: 600; margin-top: 10px; font-size: 16px;">
                    Welcome back, <span style="color: var(--neon-green);"><?php echo htmlspecialchars($current_user['full_name']); ?></span>. System is operational.
                </p>
            </div>
            <div class="bento-card" style="padding: 15px 25px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                <span style="color: white; font-size: 12px; font-weight: 800; letter-spacing: 1px; display: block; margin-bottom: 5px;">SERVER TIME</span>
                <span style="color: var(--neon-green); font-family: 'JetBrains Mono', monospace; font-weight: 900; font-size: 18px;"><?php echo date('H:i'); ?> <small style="font-size: 10px; opacity: 0.6;">UTC+5:30</small></span>
            </div>
        </div>
    </div>

    <!-- CORE METRICS GRID -->
    <div class="elite-grid" style="margin-top: -30px;">
        <!-- Players Metric -->
        <div class="bento-card primary">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span class="tier-pill">ATHLETES</span>
                    <h2 style="font-size: 42px; font-weight: 900; color: var(--slate-deep); margin: 15px 0 5px;"><?php echo number_format($total_players); ?></h2>
                    <p style="color: #94a3b8; font-weight: 600; font-size: 13px;">Registered Cadre</p>
                </div>
                <div class="sport-emoji-box" style="background: rgba(140, 0, 255, 0.05);">
                    <img src="<?php echo $icons['players']; ?>" style="width: 24px; filter: grayscale(1) brightness(0.5);">
                </div>
            </div>
        </div>

        <!-- Teams Metric -->
        <div class="bento-card success">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span class="tier-pill" style="background: rgba(0, 200, 150, 0.1); color: var(--elite-green);">SQUADS</span>
                    <h2 style="font-size: 42px; font-weight: 900; color: var(--slate-deep); margin: 15px 0 5px;"><?php echo number_format($total_teams); ?></h2>
                    <p style="color: #94a3b8; font-weight: 600; font-size: 13px;">Active Divisions</p>
                </div>
                <div class="sport-emoji-box" style="background: rgba(0, 200, 150, 0.05);">
                    <img src="<?php echo $icons['teams']; ?>" style="width: 24px; filter: grayscale(1) brightness(0.5);">
                </div>
            </div>
        </div>

        <!-- Matches Metric -->
        <div class="bento-card warning">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span class="tier-pill" style="background: rgba(255, 170, 0, 0.1); color: #f59e0b;">OPERATIONS</span>
                    <h2 style="font-size: 42px; font-weight: 900; color: var(--slate-deep); margin: 15px 0 5px;"><?php echo number_format($completed_matches); ?></h2>
                    <p style="color: #94a3b8; font-weight: 600; font-size: 13px;">Completed Results</p>
                </div>
                <div class="sport-emoji-box" style="background: rgba(245, 158, 11, 0.05);">
                    <img src="<?php echo $icons['results']; ?>" style="width: 24px; filter: grayscale(1) brightness(0.5);">
                </div>
            </div>
        </div>
    </div>

    <!-- PENDING ACTIONS (UPCOMING MATCHES) -->
    <div class="bento-card" style="margin-top: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding: 10px;">
            <div>
                <h3 style="font-weight: 900; color: var(--slate-deep); font-size: 20px;">Pending Field Operations</h3>
                <p style="color: #94a3b8; font-size: 13px; font-weight: 600;">Upcoming matches requiring score entry and validation.</p>
            </div>
            <a href="view_matches.php" class="btn-reset-light" style="padding: 10px 20px; font-size: 12px;">Full Schedule</a>
        </div>

        <div class="ultra-table-container shadow-none" style="background: transparent; border: none;">
            <?php if (count($upcoming_matches) > 0): ?>
                <?php foreach ($upcoming_matches as $match): ?>
                    <div class="ultra-table-row" style="background: white; margin-bottom: 12px; border-radius: 20px; border: 1px solid #f1f5f9;">
                        <div class="identity-cluster" style="flex: 2;">
                            <div class="sport-emoji-box" style="font-size: 28px; background: white; border: 1px solid #f1f5f9; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: center; width: 55px; height: 55px; border-radius: 18px; overflow: hidden;">
                                <?php
                                $sport_icon = $match['sport_icon'] ?? '🏆';
                                if (strpos($sport_icon, '.') !== false || strpos($sport_icon, '/') !== false): ?>
                                    <img src="../assets/images/<?php echo $sport_icon; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span style="line-height: 1;"><?php echo $sport_icon; ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="meta-handle" style="font-size: 14px;">
                                    <?php echo htmlspecialchars($match['team1']); ?>
                                    <span style="color: var(--elite-purple); font-size: 10px; margin: 0 8px;">VS</span>
                                    <?php echo htmlspecialchars($match['team2']); ?>
                                </h4>
                                <span class="meta-subtext"><?php echo $match['sport_name']; ?> Division</span>
                            </div>
                        </div>

                        <div style="flex: 1.5;">
                            <span class="meta-subtext" style="display: block; margin-bottom: 4px;">Tactical Window</span>
                            <span style="font-weight: 800; color: var(--slate-deep); font-size: 13px;">
                                <?php echo formatDate($match['match_date'], 'M d, Y'); ?> @ <?php echo formatTime($match['match_time']); ?>
                            </span>
                        </div>

                        <div style="flex: 1.5;">
                            <span class="meta-subtext" style="display: block; margin-bottom: 4px;">Location</span>
                            <span style="font-weight: 700; color: #64748b; font-size: 13px;">📍 <?php echo htmlspecialchars($match['venue']); ?></span>
                        </div>

                        <div>
                            <a href="enter_scores.php?id=<?php echo $match['id']; ?>" class="elite-action-btn" style="padding: 12px 25px; font-size: 11px;">
                                ENTER INTEL
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 60px;">
                    <span style="font-size: 40px; opacity: 0.3;">📡</span>
                    <p style="margin-top: 15px; color: #94a3b8; font-weight: 600;">No immediate operations scheduled.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>