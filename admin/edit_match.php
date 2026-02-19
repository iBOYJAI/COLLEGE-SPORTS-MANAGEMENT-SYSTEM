<?php

/**
 * College Sports Management System
 * Premium Match Update - Real-time Configuration
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Edit Match';
$current_page = 'matches';

$match_id = intval($_GET['id'] ?? 0);

if (!$match_id) {
    setError('Invalid fixture selection');
    header('Location: manage_matches.php');
    exit();
}

// Fetch match with team names and sport info
$match = $conn->query("SELECT m.*, s.sport_name, s.icon as sport_icon,
                       t1.team_name as team1, t2.team_name as team2 
                       FROM matches m 
                       JOIN sports_categories s ON m.sport_id = s.id 
                       JOIN teams t1 ON m.team1_id = t1.id
                       JOIN teams t2 ON m.team2_id = t2.id
                       WHERE m.id = $match_id")->fetch_assoc();

if (!$match) {
    setError('Match fixture node not found');
    header('Location: manage_matches.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $match_date = $_POST['match_date'];
    $match_time = $_POST['match_time'];
    $venue = sanitize($_POST['venue']);
    $status = $_POST['status'];

    if (empty($match_date) || empty($match_time)) $errors[] = 'Event timing parameters are required';
    if (empty($venue)) $errors[] = 'Match venue must be specified';

    // Conflict detection on update
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM matches 
                                 WHERE id != ? 
                                 AND match_date = ? 
                                 AND match_time = ? 
                                 AND venue = ? 
                                 AND status != 'cancelled'");
        $check->bind_param("isss", $match_id, $match_date, $match_time, $venue);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = 'Venue Conflict: Another fixture is already scheduled for this venue/time.';
        }
        $check->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE matches SET match_date = ?, match_time = ?, venue = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $match_date, $match_time, $venue, $status, $match_id);

        if ($stmt->execute()) {
            logActivity($conn, 'update', 'matches', $match_id, "Refined match fixture parameters");
            setSuccess('Match fixture updated successfully');
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
            <h1 class="welcome-text">Edit Match Fixture</h1>
            <p class="subtitle-text">Modifying logistics for <strong><?php echo htmlspecialchars($match['team1']); ?> vs <?php echo htmlspecialchars($match['team2']); ?></strong>.</p>
        </div>
        <div class="header-actions">
            <a href="manage_matches.php" class="btn-reset-light">
                Return to Schedule
            </a>
        </div>
    </div>

    <div class="main-grid">
        <div class="charts-column">
            <div class="glass-card">
                <form action="" method="POST" class="premium-form">
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
                            <h3 class="form-section-title">Schedule & Location</h3>

                            <div class="premium-field">
                                <label class="field-label">Match Date</label>
                                <input type="date" name="match_date" class="premium-input" value="<?php echo $match['match_date']; ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Scheduled Time</label>
                                <input type="time" name="match_time" class="premium-input" value="<?php echo $match['match_time']; ?>" required>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Venue / Stadium</label>
                                <input type="text" name="venue" class="premium-input" value="<?php echo htmlspecialchars($match['venue']); ?>" required>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3 class="form-section-title">Match State</h3>

                            <div class="premium-field">
                                <label class="field-label">Current Status</label>
                                <div class="premium-radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="scheduled" <?php echo ($match['status'] === 'scheduled') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Scheduled / Pending</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="completed" <?php echo ($match['status'] === 'completed') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Completed / Finished</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="status" value="cancelled" <?php echo ($match['status'] === 'cancelled') ? 'checked' : ''; ?>>
                                        <span class="radio-label">Cancelled / Aborted</span>
                                    </label>
                                </div>
                            </div>

                            <div class="premium-field">
                                <label class="field-label">Linked Discipline</label>
                                <div style="display:flex; align-items:center; gap: 10px; padding: 12px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; opacity: 0.7;">
                                    <span style="font-size: 20px;"><?php echo htmlspecialchars($match['sport_icon'] ?? '🏆'); ?></span>
                                    <span style="font-weight: 700; color: var(--slate-deep);"><?php echo htmlspecialchars($match['sport_name']); ?> Category</span>
                                </div>
                                <p style="font-size: 10px; color: var(--text-light); margin-top: 5px;">* Categorization and competing teams are immutable after scheduling.</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer mt-8">
                        <button type="submit" class="btn-premium-search" style="min-width: 220px;">Save Schedule Changes</button>
                        <a href="manage_matches.php" class="btn-reset-light" style="margin-left: 10px;">Cancel Modification</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Contingent Matchup</h3>
                <div style="display: flex; justify-content: space-around; align-items: center; margin: 30px 0;">
                    <div style="width: 70px; height: 70px; background: #f8fafc; border-radius: 20px; display:flex; align-items:center; justify-content:center; border: 3px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.06); font-weight: 900; color: var(--primary-color);">
                        <?php echo substr($match['team1'], 0, 1); ?>
                    </div>
                    <span style="font-weight: 900; color: #cbd5e1;">VS</span>
                    <div style="width: 70px; height: 70px; background: #f8fafc; border-radius: 20px; display:flex; align-items:center; justify-content:center; border: 3px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.06); font-weight: 900; color: var(--primary-color);">
                        <?php echo substr($match['team2'], 0, 1); ?>
                    </div>
                </div>
                <h4 style="font-size: 14px; font-weight: 800; color: var(--slate-deep);"><?php echo htmlspecialchars($match['team1']); ?> vs <?php echo htmlspecialchars($match['team2']); ?></h4>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Validation Logs</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        Scheduled on: <?php echo date('M d, Y', strtotime($match['created_at'])); ?><br>
                        Fixture ID: #FE-0<?php echo $match['id']; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>