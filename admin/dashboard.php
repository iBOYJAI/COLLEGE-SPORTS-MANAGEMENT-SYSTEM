<?php

/**
 * College Sports Management System
 * Final Polished Admin Dashboard - Real-time Data & Premium Styling
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Dashboard Overview';
$current_page = 'dashboard';

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header" style="margin-bottom: 40px;">
        <div class="header-info">
            <h1 class="ultra-title" style="font-size: 48px; color: black">Dashboard</h1>
            <p class="subtitle-text" style="color: #64748b; font-weight: 600; font-size: 16px;">
                <span class="glow-indicator online"></span> System operational - Data synced <?php echo date('H:i:s'); ?>
            </p>
        </div>
        <div class="header-actions" style="display: flex; gap: 15px;">
            <button class="elite-action-btn" id="manual-refresh" style="padding: 15px 30px; border-radius: 20px;">
                Sync Reality
            </button>
            <a href="reports.php" class="btn-reset-light" style="padding: 15px 30px; border-radius: 20px; display: flex; align-items: center;">Download Intel</a>
        </div>
    </div>

    <!-- MAIN BENTO GRID -->
    <div class="dashboard-bento-grid">
        <!-- Stats Row -->
        <div class="bento-card stat-bento primary" style="animation-delay: 0.1s;">
            <div class="stat-icon">
                <img src="<?php echo $icons['players']; ?>" alt="Players" style="width: 24px;">
            </div>
            <div>
                <span class="stat-label">Total Cadre</span>
                <h2 class="stat-value" id="stat-players">0</h2>
                <div class="bento-card-subtitle">Active Players</div>
            </div>
        </div>

        <div class="bento-card stat-bento success" style="animation-delay: 0.2s;">
            <div class="stat-icon">
                <img src="<?php echo $icons['teams']; ?>" alt="Teams" style="width: 24px;">
            </div>
            <div>
                <span class="stat-label">Deployments</span>
                <h2 class="stat-value" id="stat-teams">0</h2>
                <div class="bento-card-subtitle">Organized Squads</div>
            </div>
        </div>

        <div class="bento-card stat-bento warning" style="animation-delay: 0.3s;">
            <div class="stat-icon">
                <img src="<?php echo $icons['sports']; ?>" alt="Sports" style="width: 24px;">
            </div>
            <div>
                <span class="stat-label">Disciplines</span>
                <h2 class="stat-value" id="stat-sports">0</h2>
                <div class="bento-card-subtitle">Sport Categories</div>
            </div>
        </div>

        <div class="bento-card stat-bento info" style="animation-delay: 0.4s;">
            <div class="stat-icon">
                <img src="<?php echo $icons['matches']; ?>" alt="Matches" style="width: 24px;">
            </div>
            <div>
                <span class="stat-label">Operations</span>
                <h2 class="stat-value" id="stat-matches">0</h2>
                <div class="bento-card-subtitle">Scheduled Events</div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="bento-card chart-bento" style="animation-delay: 0.5s;">
            <div class="bento-card-header">
                <div>
                    <span class="bento-card-subtitle">Analytics Vector</span>
                    <h3 class="bento-card-title">Registration Velocity</h3>
                </div>
                <div class="tier-pill">LIVE</div>
            </div>
            <div id="line-chart-container" class="bento-chart-container">
                <div class="loading-placeholder">Decrypting Trends...</div>
            </div>
        </div>

        <div class="bento-card chart-bento" style="animation-delay: 0.6s;">
            <div class="bento-card-header">
                <div>
                    <span class="bento-card-subtitle">Dominance Index</span>
                    <h3 class="bento-card-title">Squad Performance</h3>
                </div>
                <div class="tier-pill">RANKED</div>
            </div>
            <div id="bar-chart-container" class="bento-chart-container">
                <div class="loading-placeholder">Calculating Dominance...</div>
            </div>
        </div>

        <!-- Middle Row: Distribution & System Stats -->
        <div class="bento-card" style="grid-column: span 1; animation-delay: 0.7s;">
            <div class="bento-card-header">
                <h3 class="bento-card-title">Spread</h3>
            </div>
            <div id="pie-chart-container" style="height: 300px;">
                <div class="loading-placeholder">Mapping Distribution...</div>
            </div>
        </div>

        <div class="bento-card" style="grid-column: span 3; animation-delay: 0.8s;">
            <div class="bento-card-header">
                <h3 class="bento-card-title">Pending Operations</h3>
                <a href="manage_matches.php" class="meta-subtext" style="text-decoration: none; color: var(--primary-color);">Global Ops Center</a>
            </div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Unit Alpha vs Beta</th>
                            <th>Field</th>
                            <th>Timeline</th>
                        </tr>
                    </thead>
                    <tbody id="upcoming-matches-body">
                        <tr>
                            <td colspan="3" class="text-center py-8">
                                <div class="loading-placeholder">Scanning Horizon...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom: Activities -->
        <div class="bento-card" style="grid-column: span 4; animation-delay: 0.9s;">
            <div class="bento-card-header">
                <h3 class="bento-card-title">System Intel Feed</h3>
            </div>
            <div class="activity-timeline" id="activity-timeline-body" style="display: flex; gap: 30px; overflow-x: auto; padding: 20px 0;">
                <div class="loading-placeholder">Intercepting Logs...</div>
            </div>
        </div>
    </div>
</div>




<script src="../assets/js/charts.js"></script>
<script>
    /**
     * Real-time Dashboard Core
     * Fetch data via AJAX and update UI components
     */
    async function updateDashboard() {
        try {
            const response = await fetch('../api/get_dashboard_stats.php');
            const data = await response.json();

            if (data.error) {
                console.error("Dashboard Sync Error:", data.error);
                return;
            }

            // 1. Update Stats Counters
            document.getElementById('stat-players').innerText = data.stats.players.toLocaleString();
            document.getElementById('stat-teams').innerText = data.stats.teams.toLocaleString();
            document.getElementById('stat-sports').innerText = data.stats.sports.toLocaleString();
            document.getElementById('stat-matches').innerText = data.stats.matches.toLocaleString();

            // 2. Render Charts with Zero-Data Handling
            renderCharts(data);

            // 3. Update Match Table
            updateMatchTable(data.upcoming_matches);

            // 4. Update Activity Timeline
            updateTimeline(data.activities);

        } catch (error) {
            console.error("Critical Dashboard Sync Failure:", error);
        }
    }

    function renderCharts(data) {
        // Line Chart
        const lineContainer = document.getElementById('line-chart-container');
        if (data.monthly_matches.length > 0 && data.monthly_matches.some(m => m.count > 0)) {
            const lineData = data.monthly_matches.map(m => ({
                label: m.month,
                value: parseInt(m.count)
            }));
            createLineChart('line-chart-container', lineData);
        } else {
            lineContainer.innerHTML = '<div class="no-data-msg">No recent match trends found</div>';
        }

        // Bar Chart
        const barContainer = document.getElementById('bar-chart-container');
        if (data.team_performance.length > 0) {
            const barData = data.team_performance.map(t => ({
                label: t.team_name.substring(0, 8),
                value: parseInt(t.matches_won)
            }));
            createBarChart('bar-chart-container', barData, {
                colors: ['#8C00FF', '#A8DF8E', '#FF3F7F', '#0ea5e9']
            });
        } else {
            barContainer.innerHTML = '<div class="no-data-msg">No competitive data available</div>';
        }

        // Pie Chart
        const pieContainer = document.getElementById('pie-chart-container');
        const hasPlayers = data.sport_players.some(s => parseInt(s.count) > 0);
        if (data.sport_players.length > 0 && hasPlayers) {
            const pieData = data.sport_players.map(s => ({
                label: s.sport_name,
                value: parseInt(s.count)
            }));
            createPieChart('pie-chart-container', pieData, {
                colors: ['#8C00FF', '#A8DF8E', '#FF3F7F', '#0ea5e9']
            });
        } else {
            pieContainer.innerHTML = '<div class="no-data-msg">Registration data required for chart</div>';
        }
    }

    function updateMatchTable(matches) {
        const body = document.getElementById('upcoming-matches-body');
        if (matches.length === 0) {
            body.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-secondary">No upcoming operations found</td></tr>';
            return;
        }

        let html = '';
        matches.forEach(m => {
            html += `
                <tr>
                    <td>
                        <div class="match-teams">
                            <span class="team-name">${m.team1_name}</span>
                            <span class="vs">VS</span>
                            <span class="team-name">${m.team2_name}</span>
                        </div>
                    </td>
                    <td><span class="sport-badge">${m.sport_name}</span></td>
                    <td>
                        <div class="schedule-info">
                            <span class="date">${m.formatted_date}</span>
                            <span class="time">${m.formatted_time} @ ${m.venue}</span>
                        </div>
                    </td>
                </tr>
            `;
        });
        body.innerHTML = html;
    }

    function updateTimeline(logs) {
        const body = document.getElementById('activity-timeline-body');
        if (logs.length === 0) {
            body.innerHTML = '<div class="loading-placeholder">No sync logs found</div>';
            return;
        }

        let html = '';
        logs.forEach(l => {
            html += `
                <div class="activity-node">
                    <div class="node-icon"></div>
                    <div class="node-content">
                        <span class="node-desc">${l.description}</span>
                        <div class="node-meta">
                            <span class="node-user">${l.user_name || 'System'}</span>
                            <span class="node-time">${l.time_ago}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        body.innerHTML = html;
    }

    // Initialize Dashboard
    document.addEventListener('DOMContentLoaded', () => {
        updateDashboard();

        // Setup polling every 30 seconds
        setInterval(updateDashboard, 30000);

        // Manual refresh button
        document.getElementById('manual-refresh').addEventListener('click', function() {
            this.innerText = 'Refreshing...';
            this.disabled = true;
            updateDashboard().finally(() => {
                this.innerText = 'Refresh Data';
                this.disabled = false;
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>