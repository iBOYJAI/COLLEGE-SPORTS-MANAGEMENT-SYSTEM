<?php

/**
 * College Sports Management System
 * Premium Match Scheduling - Optimization & Conflict Detection
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Schedule Match';
$current_page = 'matches';

// Get active sports for schedule categorization
$sports = $conn->query("SELECT id, sport_name FROM sports_categories WHERE status = 'active' ORDER BY sport_name")->fetch_all(MYSQLI_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sport_id = intval($_POST['sport_id']);
    $team1_id = intval($_POST['team1_id']);
    $team2_id = intval($_POST['team2_id']);
    $match_date = $_POST['match_date'];
    $match_time = $_POST['match_time'];
    $venue = sanitize($_POST['venue']);

    // Validation
    if ($sport_id === 0) $errors[] = 'Please select a sports category';
    if ($team1_id === 0 || $team2_id === 0) $errors[] = 'Both competing teams must be selected';
    if ($team1_id === $team2_id) $errors[] = 'A team cannot compete against itself';
    if (empty($venue)) $errors[] = 'Match venue is required';

    // Conflict detection (Venue & Time)
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM matches WHERE match_date = ? AND match_time = ? AND venue = ? AND status != 'cancelled'");
        $check->bind_param("sss", $match_date, $match_time, $venue);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = 'Venue Conflict: Another match is already scheduled at this location and time.';
        }
        $check->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO matches (sport_id, team1_id, team2_id, match_date, match_time, venue, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'scheduled', NOW())");
        $stmt->bind_param("iiisss", $sport_id, $team1_id, $team2_id, $match_date, $match_time, $venue);

        if ($stmt->execute()) {
            logActivity($conn, 'create', 'matches', $stmt->insert_id, "Scheduled fixture: Team $team1_id vs Team $team2_id");
            setSuccess('Match fixture initialized successfully');
            header('Location: manage_matches.php');
            exit();
        } else {
            $errors[] = 'Database synchronization error: ' . $conn->error;
        }
        $stmt->close();
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-info">
            <h1 class="welcome-text">Schedule New Match</h1>
            <p class="subtitle-text">Initialize a new athletic fixture and verify venue availability.</p>
        </div>
        <div class="header-actions">
            <a href="manage_matches.php" class="btn-reset-light">
                Back to Schedule
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form" id="schedule-form">
                    <?php if (!empty($errors)): ?>
                        <div class="premium-alert error">
                            <div class="alert-icon">⚠️</div>
                            <div class="alert-msgs">
                                <?php foreach ($errors as $err): ?>
                                    <p><?php echo $err; ?></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-column">
                            <h3 class="form-section-title">Match Category</h3>

                            <div class="premium-field">
                                <label class="field-label">Discipline / Sport</label>
                                <select name="sport_id" id="sport_select" class="premium-select" required>
                                    <option value="0">Select Sport</option>
                                    <?php foreach ($sports as $sport): ?>
                                        <option value="<?php echo $sport['id']; ?>" <?php echo ($_POST['sport_id'] ?? '') == $sport['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sport['sport_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Venue Location</label>
                                <input type="text" name="venue" class="premium-input" value="<?php echo htmlspecialchars($_POST['venue'] ?? ''); ?>" required placeholder="e.g. Main Athletic Track">
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Event Timing</h3>

                            <div class="premium-field">
                                <label class="field-label">Match Date</label>
                                <input type="date" name="match_date" class="premium-input" value="<?php echo htmlspecialchars($_POST['match_date'] ?? date('Y-m-d')); ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Kick-off Time</label>
                                <input type="time" name="match_time" class="premium-input" value="<?php echo htmlspecialchars($_POST['match_time'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="premium-divider"></div>

                    <h3 class="form-section-title">Contingent Matchup</h3>
                    <div class="form-grid" style="grid-template-columns: 1fr 60px 1fr; align-items: center;">
                        <div class="premium-field">
                            <label class="field-label">Home Team</label>
                            <select name="team1_id" id="team1_select" class="premium-select" required>
                                <option value="0">Select Home Squad</option>
                            </select>
                        </div>

                        <div style="text-align: center; font-weight: 900; color: #cbd5e1; font-size: 20px; padding-top: 15px;">VS</div>

                        <div class="premium-field">
                            <label class="field-label">Away Team</label>
                            <select name="team2_id" id="team2_select" class="premium-select" required>
                                <option value="0">Select Away Squad</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 220px;">Initialize Fixture</button>
                        <a href="manage_matches.php" class="btn-reset-light" style="margin-left: 10px;">Cancel Scheduling</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Visual Matchup</h3>
                <div style="display: flex; justify-content: space-around; align-items: center; margin: 30px 0;">
                    <div id="team1-icon" style="width: 70px; height: 70px; background: #f8fafc; border-radius: 20px; display:flex; align-items:center; justify-content:center; border: 3px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.06); font-weight: 900; color: var(--primary-color);">H</div>
                    <span style="font-weight: 900; color: #cbd5e1;">VS</span>
                    <div id="team2-icon" style="width: 70px; height: 70px; background: #f8fafc; border-radius: 20px; display:flex; align-items:center; justify-content:center; border: 3px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.06); font-weight: 900; color: var(--primary-color);">A</div>
                </div>
                <p class="subtitle-text" style="font-size:12px; line-height: 1.6; opacity: 0.8">Select teams to see the matchup visualization.</p>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Conflict Guard</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        The system automatically prevents **Venue Overlaps**. Scheduling a match requires at least two registered teams for the selected sports category.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sportSelect = document.getElementById('sport_select');
        const team1Select = document.getElementById('team1_select');
        const team2Select = document.getElementById('team2_select');
        const team1Icon = document.getElementById('team1-icon');
        const team2Icon = document.getElementById('team2-icon');

        function updateIcons() {
            team1Icon.innerHTML = team1Select.options[team1Select.selectedIndex].text.substring(0, 1).toUpperCase() || 'H';
            team2Icon.innerHTML = team2Select.options[team2Select.selectedIndex].text.substring(0, 1).toUpperCase() || 'A';
        }

        sportSelect.addEventListener('change', function() {
            const sportId = this.value;
            if (sportId == 0) {
                team1Select.innerHTML = '<option value="0">Select Home Squad</option>';
                team2Select.innerHTML = '<option value="0">Select Away Squad</option>';
                updateIcons();
                return;
            }

            fetch(`../api/get_teams.php?sport_id=${sportId}`)
                .then(r => r.json())
                .then(teams => {
                    const options = teams.map(t => `<option value="${t.id}">${t.team_name}</option>`).join('');
                    const placeholder1 = '<option value="0">Select Home Squad</option>';
                    const placeholder2 = '<option value="0">Select Away Squad</option>';
                    team1Select.innerHTML = placeholder1 + options;
                    team2Select.innerHTML = placeholder2 + options;
                    updateIcons();
                });
        });

        team1Select.addEventListener('change', updateIcons);
        team2Select.addEventListener('change', updateIcons);
    });
</script>

<?php include '../includes/footer.php'; ?>