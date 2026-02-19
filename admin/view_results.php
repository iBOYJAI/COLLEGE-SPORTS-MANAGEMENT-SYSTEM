<?php

/**
 * View Match Results
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Match Results';
$current_page = 'results';

$match_id = intval($_GET['id'] ?? 0);

if (!$match_id) {
    setError('Invalid match ID');
    header('Location: manage_matches.php');
    exit();
}

$query = "SELECT m.*, s.sport_name, t1.team_name as team1, t2.team_name as team2,
          mr.team1_score, mr.team2_score, mr.winner_team_id, mr.result_status, mr.notes, mr.created_at as result_date
          FROM matches m
          JOIN sports_categories s ON m.sport_id = s.id
          JOIN teams t1 ON m.team1_id = t1.id
          JOIN teams t2 ON m.team2_id = t2.id
          LEFT JOIN match_results mr ON m.id = mr.match_id
          WHERE m.id = $match_id";

$match = $conn->query($query)->fetch_assoc();

if (!$match) {
    setError('Match not found');
    header('Location: manage_matches.php');
    exit();
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="page-header">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="manage_matches.php" class="btn btn-secondary">← Back</a>
            <div>
                <h1 class="page-title">Match Results</h1>
                <p class="page-subtitle"><?php echo $match['sport_name']; ?> • <?php echo formatDate($match['match_date']); ?></p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Match Header -->
            <div style="background: var(--bg-secondary); padding: 32px; border-radius: 12px; text-align: center; margin-bottom: 32px;">
                <div style="display: flex; justify-content: space-around; align-items: center;">
                    <div style="flex: 1; text-align: center;">
                        <h2 style="font-size: 28px; margin-bottom: 8px;"><?php echo htmlspecialchars($match['team1']); ?></h2>
                        <?php if ($match['team1_score'] !== null): ?>
                            <div style="font-size: 64px; font-weight: bold; color: var(--primary-color);">
                                <?php echo $match['team1_score']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="font-size: 32px; color: var(--text-secondary); padding: 0 32px;">VS</div>

                    <div style="flex: 1; text-align: center;">
                        <h2 style="font-size: 28px; margin-bottom: 8px;"><?php echo htmlspecialchars($match['team2']); ?></h2>
                        <?php if ($match['team2_score'] !== null): ?>
                            <div style="font-size: 64px; font-weight: bold; color: var(--primary-color);">
                                <?php echo $match['team2_score']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($match['winner_team_id']): ?>
                    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-light);">
                        <div style="font-size: 18px; color: var(--success-color);">
                            <img src="<?php echo $icons['certificates']; ?>" class="action-icon-sm"> Winner: <strong><?php echo ($match['winner_team_id'] == $match['team1_id']) ? $match['team1'] : $match['team2']; ?></strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Match Details -->
            <div class="grid grid-cols-2" style="margin-bottom: 24px;">
                <div>
                    <strong>Date & Time:</strong><br>
                    <?php echo formatDate($match['match_date']); ?> at <?php echo formatTime($match['match_time']); ?>
                </div>
                <div>
                    <strong>Venue:</strong><br>
                    <?php echo htmlspecialchars($match['venue']); ?>
                </div>
                <div>
                    <strong>Sport:</strong><br>
                    <span class="badge badge-info"><?php echo $match['sport_name']; ?></span>
                </div>
                <div>
                    <strong>Status:</strong><br>
                    <span class="badge badge-<?php echo $match['status'] === 'completed' ? 'success' : 'warning'; ?>">
                        <?php echo ucfirst($match['status']); ?>
                    </span>
                </div>
            </div>

            <?php if ($match['notes']): ?>
                <div style="background: var(--info-bg); padding: 16px; border-radius: 8px; border-left: 4px solid var(--info-color);">
                    <strong>Match Notes:</strong><br>
                    <?php echo nl2br(htmlspecialchars($match['notes'])); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>