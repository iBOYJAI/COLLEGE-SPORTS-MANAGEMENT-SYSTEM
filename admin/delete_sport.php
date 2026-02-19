<?php

/**
 * Delete Sport
 */

require_once '../config.php';
requireAdmin();

$sport_id = intval($_GET['id'] ?? 0);

if (!$sport_id) {
    setError('Invalid sport ID');
    header('Location: manage_sports.php');
    exit();
}

// Check dependencies
$player_count = $conn->query("SELECT COUNT(*) as c FROM player_sports WHERE sport_id = $sport_id")->fetch_assoc()['c'];
$team_count = $conn->query("SELECT COUNT(*) as c FROM teams WHERE sport_id = $sport_id")->fetch_assoc()['c'];

if ($player_count > 0 || $team_count > 0) {
    setError("Cannot delete sport: $player_count players and $team_count teams are associated");
    header('Location: manage_sports.php');
    exit();
}

$stmt = $conn->prepare("DELETE FROM sports_categories WHERE id = ?");
$stmt->bind_param("i", $sport_id);

if ($stmt->execute()) {
    logActivity($conn, 'delete', 'sports_categories', $sport_id, "Deleted sport");
    setSuccess('Sport deleted successfully');
} else {
    setError('Failed to delete sport');
}

$stmt->close();
header('Location: manage_sports.php');
exit();
