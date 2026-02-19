<?php
require_once '../config.php';

header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$query = $_GET['q'] ?? '';
$results = [];

if (strlen($query) >= 2) {
    $search = "%$query%";

    // Search Players
    $stmt = $conn->prepare("SELECT id, name, department FROM players WHERE (name LIKE ? OR register_number LIKE ?) AND status = 'active' LIMIT 5");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $players = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $player_url = ($_SESSION['role'] === 'admin') ? 'manage_players.php' : 'view_players.php';
    foreach ($players as $p) {
        $results[] = [
            'id' => $p['id'],
            'title' => $p['name'],
            'subtitle' => $p['department'],
            'type' => 'Player',
            'url' => $player_url
        ];
    }
    $stmt->close();

    // Search Teams
    $stmt = $conn->prepare("SELECT id, team_name FROM teams WHERE team_name LIKE ? AND status = 'active' LIMIT 5");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $teams = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $team_url = ($_SESSION['role'] === 'admin') ? 'manage_teams.php' : 'view_teams.php';
    foreach ($teams as $t) {
        $results[] = [
            'id' => $t['id'],
            'title' => $t['team_name'],
            'subtitle' => 'Sports Team',
            'type' => 'Team',
            'url' => $team_url
        ];
    }
    $stmt->close();
}

echo json_encode($results);
