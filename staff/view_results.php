<?php

/**
 * Staff View Results - Premium Interface
 */
require_once '../config.php';
requireLogin();

$match_id = intval($_GET['id'] ?? 0);
if (!$match_id) {
    header('Location: view_matches.php');
    exit();
}

$match = $conn->query("SELECT m.*, s.sport_name, t1.team_name as team1, t2.team_name as team2,
                      mr.team1_score, mr.team2_score, mr.winner_team_id, mr.result_status, mr.notes
                      FROM matches m
                      JOIN sports_categories s ON m.sport_id = s.id
                      JOIN teams t1 ON m.team1_id = t1.id
                      JOIN teams t2 ON m.team2_id = t2.id
                      LEFT JOIN match_results mr ON m.id = mr.match_id
                      WHERE m.id = $match_id")->fetch_assoc();

if (!$match) {
    header('Location: view_matches.php');
    exit();
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="padding: 40px; background: #f8fafc;">
    <div class="ultra-header">
        <h1 class="ultra-title">Engagement Analysis</h1>
        <p class="meta-subtext" style="color: rgba(255,255,255,0.7); margin-top: 10px;">Post-operation review and finalized data metrics.</p>
    </div>

    <div class="bento-card" style="margin-top: -30px; text-align: center;">
        <div style="background: rgba(140, 0, 255, 0.05); padding: 40px; border-radius: 30px; border: 1px solid rgba(140, 0, 255, 0.1);">
            <div style="display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 40px;">
                <div>
                    <h2 style="font-weight: 900; color: var(--slate-deep); font-size: 28px;"><?php echo htmlspecialchars($match['team1']); ?></h2>
                    <div style="font-size: 80px; font-weight: 900; color: var(--elite-purple); margin-top: 20px;"><?php echo $match['team1_score'] ?? '0'; ?></div>
                </div>
                <div style="font-size: 30px; font-weight: 900; color: #94a3b8;">VS</div>
                <div>
                    <h2 style="font-weight: 900; color: var(--slate-deep); font-size: 28px;"><?php echo htmlspecialchars($match['team2']); ?></h2>
                    <div style="font-size: 80px; font-weight: 900; color: var(--elite-purple); margin-top: 20px;"><?php echo $match['team2_score'] ?? '0'; ?></div>
                </div>
            </div>

            <?php if ($match['winner_team_id']): ?>
                <div class="tier-pill" style="margin-top: 40px; background: var(--elite-green); color: black; padding: 15px 40px; font-size: 14px;">
                    VICTOR IDENTIFIED: <?php echo ($match['winner_team_id'] == $match['team1_id']) ? $match['team1'] : $match['team2']; ?>
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top: 40px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; text-align: left;">
            <div style="background: #f8fafc; padding: 20px; border-radius: 20px;">
                <span class="meta-subtext">Discipline</span>
                <p style="font-weight: 800; color: var(--slate-deep);"><?php echo $match['sport_name']; ?></p>
            </div>
            <div style="background: #f8fafc; padding: 20px; border-radius: 20px;">
                <span class="meta-subtext">Engagement Window</span>
                <p style="font-weight: 800; color: var(--slate-deep);"><?php echo formatDate($match['match_date']); ?></p>
            </div>
            <div style="background: #f8fafc; padding: 20px; border-radius: 20px;">
                <span class="meta-subtext">Sector (Venue)</span>
                <p style="font-weight: 800; color: var(--slate-deep);"><?php echo htmlspecialchars($match['venue']); ?></p>
            </div>
        </div>

        <?php if ($match['notes']): ?>
            <div style="margin-top: 30px; text-align: left; background: #fff; padding: 30px; border-radius: 25px; border: 1px dashed #e2e8f0;">
                <span class="meta-subtext">Field Intelligence Report</span>
                <p style="margin-top: 15px; line-height: 1.8; color: #475569; font-weight: 500;"><?php echo nl2br(htmlspecialchars($match['notes'])); ?></p>
            </div>
        <?php endif; ?>

        <div style="margin-top: 50px;">
            <a href="view_matches.php" class="elite-action-btn" style="padding: 15px 40px;">RETURN TO SCHEDULE</a>
            <?php if ($match['status'] === 'completed'): ?>
                <a href="enter_scores.php?id=<?php echo $match['id']; ?>" class="btn-reset-light" style="margin-left: 15px; padding: 15px 30px;">CORRECT INTEL</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>