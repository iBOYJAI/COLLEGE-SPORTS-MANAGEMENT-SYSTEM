<?php

/**
 * Staff Results Command Centre - Redesigned Interface
 */

require_once '../config.php';
requireLogin();

$page_title = 'Enter Scores';
$current_page = 'scores';

$match_id = intval($_GET['id'] ?? 0);

// If no match ID, show match selection interface (Command Centre)
if (!$match_id) {
    // Fetch all scheduled matches requiring entry
    $matches = $conn->query("SELECT m.*, s.sport_name, s.icon as sport_icon,
                             t1.team_name as team1, t2.team_name as team2,
                             t1.logo as team1_logo, t2.logo as team2_logo
                             FROM matches m
                             JOIN sports_categories s ON m.sport_id = s.id
                             JOIN teams t1 ON m.team1_id = t1.id
                             JOIN teams t2 ON m.team2_id = t2.id
                             WHERE m.status = 'scheduled'
                             ORDER BY m.match_date ASC, m.match_time ASC")->fetch_all(MYSQLI_ASSOC);

    // Fetch past operations (already completed)
    $past_ops = $conn->query("SELECT m.*, s.sport_name, s.icon as sport_icon,
                              t1.team_name as team1, t2.team_name as team2,
                              r.team1_score, r.team2_score
                              FROM matches m
                              JOIN sports_categories s ON m.sport_id = s.id
                              JOIN teams t1 ON m.team1_id = t1.id
                              JOIN teams t2 ON m.team2_id = t2.id
                              JOIN match_results r ON m.id = r.match_id
                              ORDER BY m.match_date DESC
                              LIMIT 10")->fetch_all(MYSQLI_ASSOC);

    include '../includes/header.php';
    include '../includes/sidebar.php';
?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-info">
                <h1 class="welcome-text">Results Command Centre</h1>
                <p class="subtitle-text">Finalize match intel and validate operational results</p>
            </div>
        </div>

        <?php if (count($matches) > 0): ?>
            <div class="section-heading-pro" style="margin: 40px 0 20px;">
                <h2 style="font-size: 20px; font-weight: 900; color: var(--slate-deep); display: flex; align-items: center; gap: 10px;">
                    Pending Operations <div class="tier-pill" style="background: var(--elite-purple); color: white;">AWAITING INTEL</div>
                </h2>
            </div>

            <div class="elite-grid">
                <?php foreach ($matches as $match): ?>
                    <div class="bento-card primary match-card" style="padding: 0; overflow: hidden; position: relative;">
                        <div style="background: var(--elite-purple); padding: 15px; text-align: center; border-bottom: 2px solid rgba(255,255,255,0.1);">
                            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <?php
                                $sport_icon = $match['sport_icon'] ?? '🏆';
                                if (strpos($sport_icon, '.') !== false || strpos($sport_icon, '/') !== false): ?>
                                    <img src="../assets/images/<?php echo $sport_icon; ?>" style="width: 60%; filter: brightness(0) invert(1);">
                                <?php else: ?>
                                    <?php echo $sport_icon; ?>
                                <?php endif; ?>
                            </div>
                            <span style="color: white; font-weight: 900; font-size: 11px; letter-spacing: 3px; display: block; text-transform: uppercase;">
                                <?php echo htmlspecialchars($match['sport_name']); ?>
                            </span>
                        </div>

                        <div style="padding: 30px; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 25px;">
                                <div style="flex: 1;">
                                    <h4 style="font-weight: 900; font-size: 15px; color: var(--slate-deep);"><?php echo htmlspecialchars($match['team1']); ?></h4>
                                </div>
                                <div style="padding: 10px 15px; background: #f8fafc; border-radius: 50%; font-weight: 900; color: var(--elite-purple); font-size: 11px;">VS</div>
                                <div style="flex: 1;">
                                    <h4 style="font-weight: 900; font-size: 15px; color: var(--slate-deep);"><?php echo htmlspecialchars($match['team2']); ?></h4>
                                </div>
                            </div>

                            <div style="background: #f8fafc; padding: 15px; border-radius: 15px; margin-bottom: 25px;">
                                <span class="meta-subtext" style="display: block; font-size: 10px;">TACTICAL WINDOW // LOCATION</span>
                                <span style="font-weight: 700; color: #64748b; font-size: 12px;">
                                    <?php echo formatDate($match['match_date'], 'M d'); ?> @ <?php echo formatTime($match['match_time']); ?> // <?php echo htmlspecialchars($match['venue']); ?>
                                </span>
                            </div>

                            <a href="enter_scores.php?id=<?php echo $match['id']; ?>" class="elite-action-btn" style="width: 100%; justify-content: center; display: flex; padding: 15px; text-decoration: none;">
                                ENTER SCORE INTEL
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (count($past_ops) > 0): ?>
            <div class="section-heading-pro" style="margin: 60px 0 20px;">
                <h2 style="font-size: 20px; font-weight: 900; color: var(--slate-deep); display: flex; align-items: center; gap: 10px;">
                    Historical Feed <div class="tier-pill">FINALIZED</div>
                </h2>
            </div>
            <div class="ultra-table-container">
                <?php foreach ($past_ops as $op): ?>
                    <div class="ultra-table-row">
                        <div class="identity-cluster" style="flex: 2;">
                            <div class="sport-emoji-box" style="width: 45px; height: 45px; font-size: 22px; background: white; border: 1px solid #f1f5f9; box-shadow: 0 4px 10px rgba(0,0,0,0.02); display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                                <?php
                                $sport_icon = $op['sport_icon'] ?? '🏆';
                                if (strpos($sport_icon, '.') !== false || strpos($sport_icon, '/') !== false): ?>
                                    <img src="../assets/images/<?php echo $sport_icon; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span style="line-height: 1;"><?php echo $sport_icon; ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="meta-handle" style="font-size: 14px;">
                                    <?php echo htmlspecialchars($op['team1']); ?> VS <?php echo htmlspecialchars($op['team2']); ?>
                                </h4>
                                <span class="meta-subtext"><?php echo $op['sport_name']; ?> Division</span>
                            </div>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <span class="meta-subtext" style="display: block;">FINAL SCAN</span>
                            <span style="font-weight: 900; color: var(--slate-deep); font-size: 18px;"><?php echo $op['team1_score']; ?> - <?php echo $op['team2_score']; ?></span>
                        </div>
                        <div style="flex: 1; text-align: right;">
                            <a href="enter_scores.php?id=<?php echo $op['id']; ?>" class="btn-reset-light" style="font-size: 10px; padding: 10px 15px;">UPDATE DATA</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php
    include '../includes/footer.php';
    exit();
}

// Logic for entering score for a specific match
$match = $conn->query("SELECT m.*, s.sport_name, t1.team_name as team1, t2.team_name as team2
                       FROM matches m
                       JOIN sports_categories s ON m.sport_id = s.id
                       JOIN teams t1 ON m.team1_id = t1.id
                       JOIN teams t2 ON m.team2_id = t2.id
                       WHERE m.id = $match_id")->fetch_assoc();

if (!$match) {
    setError('Tactical match data not found.');
    header('Location: enter_scores.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team1_score = intval($_POST['team1_score']);
    $team2_score = intval($_POST['team2_score']);
    $winner_team_id = ($team1_score > $team2_score) ? $match['team1_id'] : (($team2_score > $team1_score) ? $match['team2_id'] : null);
    $result_status = $_POST['result_status'];
    $notes = sanitize($_POST['notes']);

    // Atomic update logic
    $existing = $conn->query("SELECT * FROM match_results WHERE match_id = $match_id")->fetch_assoc();

    if ($existing) {
        $old_winner = $existing['winner_team_id'];
        if ($old_winner) $conn->query("UPDATE teams SET matches_won = matches_won - 1 WHERE id = $old_winner");

        $stmt = $conn->prepare("UPDATE match_results SET team1_score = ?, team2_score = ?, winner_team_id = ?, result_status = ?, notes = ? WHERE match_id = ?");
        $stmt->bind_param("iiiisi", $team1_score, $team2_score, $winner_team_id, $result_status, $notes, $match_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO match_results (match_id, team1_score, team2_score, winner_team_id, result_status, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiiss", $match_id, $team1_score, $team2_score, $winner_team_id, $result_status, $notes);

        $conn->query("UPDATE matches SET status = 'completed' WHERE id = $match_id");
        $conn->query("UPDATE teams SET matches_played = matches_played + 1 WHERE id IN ({$match['team1_id']}, {$match['team2_id']})");
    }

    if ($stmt->execute()) {
        if ($winner_team_id) $conn->query("UPDATE teams SET matches_won = matches_won + 1 WHERE id = $winner_team_id");
        logActivity($conn, $existing ? 'update' : 'create', 'match_results', $match_id, "Staff updated match results for match #$match_id");
        setSuccess('Intel finalized successfully.');
        header('Location: enter_scores.php');
        exit();
    }
}

$current_result = $conn->query("SELECT * FROM match_results WHERE match_id = $match_id")->fetch_assoc();

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Data Finalization</h1>
            <p class="subtitle-text"><?php echo $match['sport_name']; ?> Division • Phase <?php echo formatDate($match['match_date']); ?></p>
        </div>
        <div class="header-actions">
            <a href="enter_scores.php" class="btn-reset-light">← Back to Command Centre</a>
        </div>
    </div>

    <div class="glass-card" style="max-width: 800px; margin: 0 auto; padding: 50px;">
        <div style="background: rgba(140, 0, 255, 0.05); padding: 30px; border-radius: 20px; text-align: center; border: 1px solid rgba(140, 0, 255, 0.1); margin-bottom: 40px;">
            <h2 style="font-size: 22px; color: var(--slate-deep); display: flex; align-items: center; justify-content: center; gap: 20px;">
                <strong><?php echo htmlspecialchars($match['team1']); ?></strong>
                <span style="padding: 10px 15px; background: white; border-radius: 50%; font-size: 11px; font-weight: 900; color: var(--elite-purple);">VS</span>
                <strong><?php echo htmlspecialchars($match['team2']); ?></strong>
            </h2>
            <p class="meta-subtext" style="margin-top: 15px;"><?php echo htmlspecialchars($match['venue']); ?> // Scheduled: <?php echo formatTime($match['match_time']); ?></p>
        </div>

        <form method="POST" class="premium-form">
            <div class="form-grid">
                <div class="form-column">
                    <div class="premium-field">
                        <label class="field-label"><?php echo htmlspecialchars($match['team1']); ?> INTEL</label>
                        <input type="number" name="team1_score" class="premium-input" min="0" required value="<?php echo $current_result['team1_score'] ?? ''; ?>" placeholder="Score">
                    </div>
                </div>
                <div class="form-column">
                    <div class="premium-field">
                        <label class="field-label"><?php echo htmlspecialchars($match['team2']); ?> INTEL</label>
                        <input type="number" name="team2_score" class="premium-input" min="0" required value="<?php echo $current_result['team2_score'] ?? ''; ?>" placeholder="Score">
                    </div>
                </div>
            </div>

            <div class="premium-field">
                <label class="field-label">Operational Status</label>
                <select name="result_status" class="premium-select" required>
                    <option value="final" <?php echo ($current_result['result_status'] ?? '') === 'final' ? 'selected' : ''; ?>>Mission Final</option>
                    <option value="draw" <?php echo ($current_result['result_status'] ?? '') === 'draw' ? 'selected' : ''; ?>>Draw / Stalemate</option>
                    <option value="walkover" <?php echo ($current_result['result_status'] ?? '') === 'walkover' ? 'selected' : ''; ?>>Walkover</option>
                </select>
            </div>

            <div class="premium-field">
                <label class="field-label">Tactical Observations</label>
                <textarea name="notes" class="premium-input" rows="3" placeholder="Enter findings, fouls, or key highlights..."><?php echo htmlspecialchars($current_result['notes'] ?? ''); ?></textarea>
            </div>

            <div class="form-footer mt-8">
                <button type="submit" class="elite-action-btn" style="width: 100%; justify-content: center; height: 60px;">FINALISE SECTOR DATA</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>