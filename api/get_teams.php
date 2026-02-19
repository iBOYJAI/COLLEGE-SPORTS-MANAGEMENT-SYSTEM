<?php

/**
 * API: Get Teams by Sport
 * Returns JSON list of teams for a given sport
 */

require_once '../config.php';
requireLogin();

header('Content-Type: application/json');

$sport_id = intval($_GET['sport_id'] ?? 0);

if (!$sport_id) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("SELECT id, team_name FROM teams WHERE sport_id = ? AND status = 'active' ORDER BY team_name");
$stmt->bind_param("i", $sport_id);
$stmt->execute();
$result = $stmt->get_result();

$teams = [];
while ($row = $result->fetch_assoc()) {
    $teams[] = $row;
}

echo json_encode($teams);
