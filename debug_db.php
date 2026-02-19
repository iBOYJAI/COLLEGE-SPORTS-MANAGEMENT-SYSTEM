<?php
require_once 'config.php';

$tables = ['users', 'sports_categories', 'players', 'player_sports', 'teams', 'matches', 'activity_log'];

echo "<h1>Database Stats</h1>";
echo "<ul>";
foreach ($tables as $table) {
    $result = $conn->query("SELECT COUNT(*) as count FROM $table");
    if ($result) {
        $count = $result->fetch_assoc()['count'];
        echo "<li><strong>$table:</strong> $count</li>";
    } else {
        echo "<li><strong>$table:</strong> Error - " . $conn->error . "</li>";
    }
}
echo "</ul>";
