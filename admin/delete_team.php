<?php

/**
 * Delete Team
 */

require_once '../config.php';
requireAdmin();

$team_id = intval($_GET['id'] ?? 0);

if (!$team_id) {
    setError('Invalid team ID');
    header('Location: manage_teams.php');
    exit();
}

// Check if team has matches
$match_count = $conn->query("SELECT COUNT(*) as c FROM matches WHERE team1_id = $team_id OR team2_id = $team_id")->fetch_assoc()['c'];

if ($match_count > 0) {
    setError("Cannot delete team: has $match_count scheduled/completed matches");
    header('Location: manage_teams.php');
    exit();
}

// Delete team and player assignments
$conn->query("DELETE FROM team_players WHERE team_id = $team_id");
$stmt = $conn->prepare("UPDATE teams SET status = 'deleted' WHERE id = ?");
$stmt->bind_param("i", $team_id);

if ($stmt->execute()) {
    logActivity($conn, 'delete', 'teams', $team_id, "Deleted team");
    setSuccess('Team deleted successfully');
} else {
    setError('Failed to delete team');
}

header('Location: manage_teams.php');
exit();
