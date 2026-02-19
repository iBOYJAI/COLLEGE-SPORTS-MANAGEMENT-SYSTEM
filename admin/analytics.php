<?php

/**
 * Analytics with Performance Graphs
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Analytics & Graphs';
$current_page = 'analytics';

// Get sport-wise player distribution
$sport_players = $conn->query("SELECT s.sport_name, COUNT(DISTINCT ps.player_id) as count
                               FROM sports_categories s
                               LEFT JOIN player_sports ps ON s.id = ps.sport_id
                               WHERE s.status = 'active'
                               GROUP BY s.id
                               ORDER BY count DESC")->fetch_all(MYSQLI_ASSOC);

// Get monthly match count
$monthly_matches = $conn->query("SELECT DATE_FORMAT(match_date, '%b %Y') as month, COUNT(*) as count
                                FROM matches
                                WHERE match_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                                GROUP BY DATE_FORMAT(match_date, '%Y-%m')
                                ORDER BY match_date")->fetch_all(MYSQLI_ASSOC);

// Get team performance
$team_performance = $conn->query("SELECT team_name, matches_won, matches_played
                                  FROM teams
                                  WHERE status = 'active' AND matches_played > 0
                                  ORDER BY matches_won DESC
                                  LIMIT 5")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">📊 Analytics & Performance Graphs</h1>
        <p class="page-subtitle">Visual insights into sports data</p>
    </div>

    <!-- Sport-wise Player Distribution -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h3 class="card-title">Players by Sport</h3>
        </div>
        <div class="card-body">
            <div id="sport-chart"></div>
        </div>
    </div>

    <!-- Monthly Match Trend -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h3 class="card-title">Match Activity (Last 6 Months)</h3>
        </div>
        <div class="card-body">
            <div id="monthly-chart"></div>
        </div>
    </div>

    <!-- Top Teams Performance -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Top 5 Teams - Wins</h3>
        </div>
        <div class="card-body">
            <div id="team-chart"></div>
        </div>
    </div>
</div>

<script src="../assets/js/charts.js"></script>
<script>
    // Sport Distribution Pie Chart
    const sportData = <?php echo json_encode(array_map(function ($s) {
                            return ['label' => $s['sport_name'], 'value' => intval($s['count'])];
                        }, $sport_players)); ?>;

    if (sportData.some(d => d.value > 0)) {
        createPieChart('sport-chart', sportData);
    } else {
        document.getElementById('sport-chart').innerHTML = '<p class="text-secondary" style="text-align: center; padding: 40px;">No player data available</p>';
    }

    // Monthly Match Line Chart
    const monthlyData = <?php echo json_encode(array_map(function ($m) {
                            return ['label' => $m['month'], 'value' => intval($m['count'])];
                        }, $monthly_matches)); ?>;

    if (monthlyData.length > 0) {
        createLineChart('monthly-chart', monthlyData);
    } else {
        document.getElementById('monthly-chart').innerHTML = '<p class="text-secondary" style="text-align: center; padding: 40px;">No match data available</p>';
    }

    // Team Performance Bar Chart
    const teamData = <?php echo json_encode(array_map(function ($t) {
                            return ['label' => $t['team_name'], 'value' => intval($t['matches_won'])];
                        }, $team_performance)); ?>;

    if (teamData.length > 0) {
        createBarChart('team-chart', teamData, {
            colors: ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#00f2fe']
        });
    } else {
        document.getElementById('team-chart').innerHTML = '<p class="text-secondary" style="text-align: center; padding: 40px;">No team performance data available</p>';
    }
</script>

<?php include '../includes/footer.php'; ?>