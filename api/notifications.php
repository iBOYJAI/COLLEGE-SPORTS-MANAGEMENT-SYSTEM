<?php
require_once '../config.php';

header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Try to fetch recent activities
$notifications = [];

// First try activity_log
$result = $conn->query("SELECT COUNT(*) as count FROM activity_log");
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    $stmt = $conn->prepare("
        SELECT a.id, a.description, a.module, a.record_id, a.created_at 
        FROM activity_log a 
        ORDER BY a.created_at DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $activities = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($activities as $a) {
        $url = 'dashboard.php';
        switch ($a['module']) {
            case 'players':
                $url = 'manage_players.php';
                break;
            case 'teams':
                $url = 'manage_teams.php';
                break;
            case 'matches':
                $url = 'manage_matches.php';
                break;
            case 'users':
                $url = 'manage_users.php';
                break;
        }

        $notifications[] = [
            'id' => $a['id'],
            'text' => $a['description'],
            'time' => date('H:i', strtotime($a['created_at'])),
            'url' => $url
        ];
    }
    $stmt->close();
} else {
    // Fallback: Show recent system info
    $notifications[] = [
        'id' => 1,
        'text' => '🎉 Welcome to Sports Management System',
        'time' => date('H:i'),
        'url' => 'dashboard.php'
    ];
    $notifications[] = [
        'id' => 2,
        'text' => '📊 Check out the Analytics dashboard',
        'time' => date('H:i', strtotime('-5 minutes')),
        'url' => 'analytics.php'
    ];
}

echo json_encode($notifications);
