<?php
header('Content-Type: application/json');
require_once '../config.php';

// Check if admin
if (!isLoggedIn() || !hasRole('admin')) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// 1. Core Statistics
$stats = ['players' => 0, 'teams' => 0, 'sports' => 0, 'matches' => 0];
$stats['players'] = $conn->query("SELECT COUNT(*) as count FROM players WHERE status = 'active'")->fetch_assoc()['count'] ?? 0;
$stats['teams'] = $conn->query("SELECT COUNT(*) as count FROM teams WHERE status = 'active'")->fetch_assoc()['count'] ?? 0;
$stats['sports'] = $conn->query("SELECT COUNT(*) as count FROM sports_categories WHERE status = 'active'")->fetch_assoc()['count'] ?? 0;
$stats['matches'] = $conn->query("SELECT COUNT(*) as count FROM matches WHERE match_date >= CURDATE()")->fetch_assoc()['count'] ?? 0;

// 2. Sport-wise Player Distribution (Pie Chart)
$sport_players = $conn->query("SELECT s.sport_name, COUNT(DISTINCT ps.player_id) as count
                               FROM sports_categories s
                               LEFT JOIN player_sports ps ON s.id = ps.sport_id
                               WHERE s.status = 'active'
                               GROUP BY s.id
                               ORDER BY count DESC
                               LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// 3. Monthly Match Trend (Line Chart)
$monthly_matches = $conn->query("SELECT DATE_FORMAT(match_date, '%b') as month, COUNT(*) as count
                                FROM matches
                                WHERE match_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                                GROUP BY DATE_FORMAT(match_date, '%Y-%m')
                                ORDER BY match_date")->fetch_all(MYSQLI_ASSOC);

// 4. Team Performance - Wins (Bar Chart)
$team_performance = $conn->query("SELECT team_name, matches_won
                                  FROM teams
                                  WHERE status = 'active' AND matches_played > 0
                                  ORDER BY matches_won DESC
                                  LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// 5. Recent Activity
// Note: We need to format the time since we're returning JSON
$activities_raw = $conn->query("SELECT l.*, u.full_name as user_name 
                           FROM activity_log l 
                           LEFT JOIN users u ON l.user_id = u.id 
                           ORDER BY l.created_at DESC 
                           LIMIT 6")->fetch_all(MYSQLI_ASSOC);

$activities = [];
foreach ($activities_raw as $log) {
    $log['time_ago'] = time_elapsed_string($log['created_at']);
    $activities[] = $log;
}

// 6. Upcoming Schedule
$matches_raw = $conn->query("SELECT m.*, s.sport_name, t1.team_name as team1_name, t2.team_name as team2_name 
                                 FROM matches m
                                 JOIN sports_categories s ON m.sport_id = s.id
                                 JOIN teams t1 ON m.team1_id = t1.id
                                 JOIN teams t2 ON m.team2_id = t2.id
                                 WHERE m.match_date >= CURDATE()
                                 ORDER BY m.match_date ASC
                                 LIMIT 4")->fetch_all(MYSQLI_ASSOC);

$upcoming_matches = [];
foreach ($matches_raw as $match) {
    $match['formatted_date'] = formatDate($match['match_date'], 'M d');
    $match['formatted_time'] = formatTime($match['match_time']);
    $upcoming_matches[] = $match;
}

echo json_encode([
    'stats' => $stats,
    'sport_players' => $sport_players,
    'monthly_matches' => $monthly_matches,
    'team_performance' => $team_performance,
    'activities' => $activities,
    'upcoming_matches' => $upcoming_matches
]);
