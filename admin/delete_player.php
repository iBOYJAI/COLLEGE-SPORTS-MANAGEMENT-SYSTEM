<?php

/**
 * Delete Player
 */

require_once '../config.php';
requireAdmin();

$player_id = intval($_GET['id'] ?? 0);

if (!$player_id) {
    setError('Invalid player ID');
    header('Location: manage_players.php');
    exit();
}

// Check team assignments
$team_count = $conn->query("SELECT COUNT(*) as c FROM team_players WHERE player_id = $player_id")->fetch_assoc()['c'];

if ($team_count > 0) {
    setError("Cannot delete player: assigned to $team_count team(s). Remove from teams first.");
    header('Location: manage_players.php');
    exit();
}

// Soft delete
$stmt = $conn->prepare("UPDATE players SET status = 'deleted' WHERE id = ?");
$stmt->bind_param("i", $player_id);

if ($stmt->execute()) {
    // Remove sport associations
    $conn->query("DELETE FROM player_sports WHERE player_id = $player_id");
    logActivity($conn, 'delete', 'players', $player_id, "Deleted player");
    setSuccess('Player deleted successfully');
} else {
    setError('Failed to delete player');
}

header('Location: manage_players.php');
exit();
