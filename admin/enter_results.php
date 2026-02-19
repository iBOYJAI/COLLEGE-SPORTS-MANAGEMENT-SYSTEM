<?php

/**
 * Enter Match Results
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Enter Results';
$current_page = 'results';

$match_id = intval($_GET['id'] ?? 0);

// If no match ID, show match selection interface
if (!$match_id) {
    // Fetch all scheduled and completed matches
    $matches = $conn->query("SELECT m.*, s.sport_name, s.icon as sport_icon,
                             t1.team_name as team1, t2.team_name as team2,
                             t1.logo as team1_logo, t2.logo as team2_logo
                             FROM matches m
                             JOIN sports_categories s ON m.sport_id = s.id
                             JOIN teams t1 ON m.team1_id = t1.id
                             JOIN teams t2 ON m.team2_id = t2.id
                             ORDER BY m.status ASC, m.match_date DESC, m.match_time ASC")->fetch_all(MYSQLI_ASSOC);

    // Fetch all sports for filtering
    $sports_filter = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status = 'active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);

    include '../includes/header.php';
    include '../includes/sidebar.php';

    // Grouping matches
    $upcoming_matches = array_filter($matches, function ($m) {
        return $m['status'] === 'scheduled';
    });
    $past_matches = array_filter($matches, function ($m) {
        return $m['status'] === 'completed';
    });
?>

    <div class="dashboard-container">
        <div class="dashboard-header" style="margin-bottom: 40px;">
            <div class="header-info">
                <h1 class="welcome-text">Results Command Centre</h1>
                <p class="subtitle-text">Manage field operations and finalize match analytics</p>
            </div>
            <div class="header-actions" style="display: flex; gap: 15px; align-items: center;">
                <div class="filter-group" style="display: flex; align-items: center; gap: 10px; background: white; padding: 8px 15px; border-radius: 15px; border: 1px solid #f1f5f9;">
                    <span style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Filter:</span>
                    <select id="sport-filter" class="premium-select" style="border: none; padding: 5px; font-weight: 700; width: 150px;">
                        <option value="all">Global Ops</option>
                        <?php foreach ($sports_filter as $s): ?>
                            <option value="<?php echo strtolower($s['sport_name']); ?>"><?php echo $s['sport_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <a href="manage_matches.php" class="btn-reset-light">← Back to Matches</a>
            </div>
        </div>

        <?php if (count($matches) > 0): ?>

            <?php if (count($upcoming_matches) > 0): ?>
                <div class="section-heading-pro" style="margin: 40px 0 20px;">
                    <h2 style="font-size: 20px; font-weight: 900; color: var(--slate-deep); display: flex; align-items: center; gap: 10px;">
                        Upcoming Operations <div class="tier-pill" style="background: var(--elite-purple); color: white;">ACTIVE</div>
                    </h2>
                </div>
                <div class="elite-grid matches-section" id="upcoming-section">
                    <?php foreach ($upcoming_matches as $match): ?>
                        <div class="bento-card primary match-card" data-sport="<?php echo strtolower($match['sport_name']); ?>" style="padding: 0; position: relative;">
                            <div style="background: var(--elite-purple); padding: 12px; text-align: center;">
                                <span style="color: white; font-weight: 800; font-size: 11px; letter-spacing: 2px;"><?php echo strtoupper($match['sport_name']); ?></span>
                            </div>

                            <div style="padding: 30px;">
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 20px; margin-bottom: 25px;">
                                    <div style="text-align: center;">
                                        <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.03);">
                                            <span style="font-size: 28px;">🏆</span>
                                        </div>
                                        <h4 style="font-size: 16px; color: var(--slate-deep); font-weight: 900;"><?php echo htmlspecialchars($match['team1']); ?></h4>
                                    </div>

                                    <div class="vs">VS</div>

                                    <div style="text-align: center;">
                                        <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.03);">
                                            <span style="font-size: 28px;">🏆</span>
                                        </div>
                                        <h4 style="font-size: 16px; color: var(--slate-deep); font-weight: 900;"><?php echo htmlspecialchars($match['team2']); ?></h4>
                                    </div>
                                </div>

                                <div style="background: rgba(148, 163, 184, 0.05); padding: 15px; border-radius: 20px; margin-bottom: 25px; text-align: center;">
                                    <p style="font-size: 13px; color: var(--slate-deep); font-weight: 700; margin-bottom: 5px;">📅 <?php echo formatDate($match['match_date'], 'M d, Y'); ?></p>
                                    <p style="font-size: 12px; color: #94a3b8; font-weight: 600;">📍 <?php echo htmlspecialchars($match['venue']); ?></p>
                                </div>

                                <a href="enter_results.php?id=<?php echo $match['id']; ?>"
                                    class="elite-action-btn"
                                    style="width: 100%; text-align: center; text-decoration: none; display: block; padding: 15px;">
                                    Enter Results
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($past_matches) > 0): ?>
                <div class="section-heading-pro" style="margin: 60px 0 20px;">
                    <h2 style="font-size: 20px; font-weight: 900; color: var(--slate-deep); display: flex; align-items: center; gap: 10px;">
                        Past Operations <div class="tier-pill">FINALIZED</div>
                    </h2>
                </div>
                <div class="elite-grid matches-section" id="past-section">
                    <?php foreach ($past_matches as $match): ?>
                        <div class="bento-card success match-card" data-sport="<?php echo strtolower($match['sport_name']); ?>" style="padding: 0; position: relative;">
                            <div class="tier-pill" style="position: absolute; top: 15px; right: 15px; background: var(--elite-green); color: black;">COMPLETED</div>

                            <div style="background: var(--elite-green); padding: 12px; text-align: center;">
                                <span style="color: black; font-weight: 800; font-size: 11px; letter-spacing: 2px;"><?php echo strtoupper($match['sport_name']); ?></span>
                            </div>

                            <div style="padding: 30px;">
                                <div style="display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 20px; margin-bottom: 25px;">
                                    <div style="text-align: center;">
                                        <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.03);">
                                            <span style="font-size: 28px;">🏆</span>
                                        </div>
                                        <h4 style="font-size: 16px; color: var(--slate-deep); font-weight: 900;"><?php echo htmlspecialchars($match['team1']); ?></h4>
                                    </div>

                                    <div class="vs">VS</div>

                                    <div style="text-align: center;">
                                        <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.03);">
                                            <span style="font-size: 28px;">🏆</span>
                                        </div>
                                        <h4 style="font-size: 16px; color: var(--slate-deep); font-weight: 900;"><?php echo htmlspecialchars($match['team2']); ?></h4>
                                    </div>
                                </div>

                                <div style="background: rgba(148, 163, 184, 0.05); padding: 15px; border-radius: 20px; margin-bottom: 25px; text-align: center;">
                                    <p style="font-size: 13px; color: var(--slate-deep); font-weight: 700; margin-bottom: 5px;">📅 <?php echo formatDate($match['match_date'], 'M d, Y'); ?></p>
                                    <p style="font-size: 12px; color: #94a3b8; font-weight: 600;">📍 <?php echo htmlspecialchars($match['venue']); ?></p>
                                </div>

                                <a href="enter_results.php?id=<?php echo $match['id']; ?>"
                                    class="btn-reset-light"
                                    style="width: 100%; text-align: center; text-decoration: none; display: block; padding: 15px;">
                                    Update Reality
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <script>
                document.getElementById('sport-filter').addEventListener('change', function() {
                    const selectedSport = this.value;
                    const cards = document.querySelectorAll('.match-card');

                    cards.forEach(card => {
                        if (selectedSport === 'all' || card.getAttribute('data-sport') === selectedSport) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Hide headers if no cards visible in section
                    ['upcoming-section', 'past-section'].forEach(id => {
                        const section = document.getElementById(id);
                        if (section) {
                            const sectionCards = Array.from(section.querySelectorAll('.match-card'));
                            const visibleCards = sectionCards.filter(c => c.style.display !== 'none');
                            const heading = section.previousElementSibling;
                            heading.style.display = visibleCards.length > 0 ? 'block' : 'none';
                        }
                    });
                });
            </script>
        <?php else: ?>
            <div class="glass-card" style="padding: 60px; text-align: center;">
                <div style="font-size: 60px; margin-bottom: 20px;">🏆</div>
                <h3 style="color: var(--slate-deep); font-weight: 800; margin-bottom: 10px;">No Scheduled Matches</h3>
                <p style="color: var(--text-secondary); margin-bottom: 20px;">There are no scheduled matches waiting for results.</p>
                <a href="schedule_match.php" class="elite-action-btn" style="text-decoration: none;">Schedule New Match</a>
            </div>
        <?php endif; ?>
    </div>

<?php
    include '../includes/footer.php';
    exit();
}

// If match ID provided, fetch the match
$match = $conn->query("SELECT m.*, s.sport_name, t1.team_name as team1, t2.team_name as team2
                       FROM matches m
                       JOIN sports_categories s ON m.sport_id = s.id
                       JOIN teams t1 ON m.team1_id = t1.id
                       JOIN teams t2 ON m.team2_id = t2.id
                       WHERE m.id = $match_id")->fetch_assoc();

if (!$match) {
    setError('Match not found in database. It may have been deleted.');
    header('Location: enter_results.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team1_score = intval($_POST['team1_score']);
    $team2_score = intval($_POST['team2_score']);
    $winner_team_id = ($team1_score > $team2_score) ? $match['team1_id'] : (($team2_score > $team1_score) ? $match['team2_id'] : null);
    $result_status = $_POST['result_status'];
    $notes = sanitize($_POST['notes']);

    // Check if result already exists (Editing)
    $existing = $conn->query("SELECT * FROM match_results WHERE match_id = $match_id")->fetch_assoc();

    if ($existing) {
        // UPDATE Logic: Revert old stats first
        $old_winner = $existing['winner_team_id'];

        // If it was already completed, we don't need to increment played again, but we might need to change winner
        if ($old_winner) {
            $conn->query("UPDATE teams SET matches_won = matches_won - 1 WHERE id = $old_winner");
        }

        $stmt = $conn->prepare("UPDATE match_results SET team1_score = ?, team2_score = ?, winner_team_id = ?, result_status = ?, notes = ? WHERE match_id = ?");
        $stmt->bind_param("iiiisi", $team1_score, $team2_score, $winner_team_id, $result_status, $notes, $match_id);
    } else {
        // INSERT Logic
        $stmt = $conn->prepare("INSERT INTO match_results (match_id, team1_score, team2_score, winner_team_id, result_status, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiiss", $match_id, $team1_score, $team2_score, $winner_team_id, $result_status, $notes);

        // Update match status and played count only on first entry
        $conn->query("UPDATE matches SET status = 'completed' WHERE id = $match_id");
        $conn->query("UPDATE teams SET matches_played = matches_played + 1 WHERE id IN ({$match['team1_id']}, {$match['team2_id']})");
    }

    if ($stmt->execute()) {
        if ($winner_team_id) {
            $conn->query("UPDATE teams SET matches_won = matches_won + 1 WHERE id = $winner_team_id");
        }

        logActivity($conn, $existing ? 'update' : 'create', 'match_results', $match_id, $existing ? "Updated match result" : "Entered match result");
        setSuccess($existing ? 'Match result updated successfully' : 'Match result recorded successfully');
        header('Location: enter_results.php');
        exit();
    }
}

// Fetch current scores if editing
$current_result = $conn->query("SELECT * FROM match_results WHERE match_id = $match_id")->fetch_assoc();

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Enter Match Results</h1>
            <p class="subtitle-text"><?php echo $match['sport_name']; ?> • <?php echo formatDate($match['match_date']); ?></p>
        </div>
        <div class="header-actions">
            <a href="enter_results.php" class="btn-reset-light">← Back to Match List</a>
        </div>
    </div>

    <div class="glass-card">
        <div style="background: rgba(140, 0, 255, 0.05); padding: 24px; border-radius: 15px; margin-bottom: 24px; text-align: center; border: 1px solid rgba(140, 0, 255, 0.1);">
            <h2 style="font-size: 24px; margin-bottom: 16px; color: var(--slate-deep);">
                <strong><?php echo htmlspecialchars($match['team1']); ?></strong>
                <span style="color: var(--text-secondary); margin: 0 15px;">vs</span>
                <strong><?php echo htmlspecialchars($match['team2']); ?></strong>
            </h2>
            <div style="color: var(--text-secondary);">
                <?php echo formatDate($match['match_date']); ?> • <?php echo formatTime($match['match_time']); ?> • <?php echo htmlspecialchars($match['venue']); ?>
            </div>
        </div>

        <form method="POST" class="premium-form">
            <div class="form-grid">
                <div class="form-column">
                    <div class="premium-field">
                        <label class="field-label"><?php echo htmlspecialchars($match['team1']); ?> Score</label>
                        <input type="number" name="team1_score" class="premium-input" min="0" required value="<?php echo $current_result['team1_score'] ?? ''; ?>">
                    </div>
                </div>

                <div class="form-column">
                    <div class="premium-field">
                        <label class="field-label"><?php echo htmlspecialchars($match['team2']); ?> Score</label>
                        <input type="number" name="team2_score" class="premium-input" min="0" required value="<?php echo $current_result['team2_score'] ?? ''; ?>">
                    </div>
                </div>
            </div>

            <div class="premium-field">
                <label class="field-label">Result Status</label>
                <select name="result_status" class="premium-select" required>
                    <option value="final" <?php echo ($current_result['result_status'] ?? '') === 'final' ? 'selected' : ''; ?>>Final</option>
                    <option value="draw" <?php echo ($current_result['result_status'] ?? '') === 'draw' ? 'selected' : ''; ?>>Draw</option>
                    <option value="walkover" <?php echo ($current_result['result_status'] ?? '') === 'walkover' ? 'selected' : ''; ?>>Walkover</option>
                </select>
            </div>

            <div class="premium-field">
                <label class="field-label">Match Notes</label>
                <textarea name="notes" class="premium-input" rows="3" placeholder="Any additional notes about the match..."><?php echo htmlspecialchars($current_result['notes'] ?? ''); ?></textarea>
            </div>

            <div class="form-footer mt-8">
                <button type="submit" class="elite-action-btn">
                    <?php echo $current_result ? 'Update Reality' : 'Submit Results'; ?>
                </button>
                <a href="enter_results.php" class="btn-reset-light" style="margin-left: 10px;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>