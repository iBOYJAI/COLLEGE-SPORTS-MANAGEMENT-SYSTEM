<?php

/**
 * College Sports Management System
 * Premium Player Assignment - Optimized Roster Management
 */

require_once '../config.php';
requireAdmin();

$page_title = 'Assign Players';
$current_page = 'teams';

$team_id = intval($_GET['id'] ?? 0);
if (!$team_id) {
    setError('Invalid squad selection');
    header('Location: manage_teams.php');
    exit();
}

// Fetch team with its sports category info
$team = $conn->query("SELECT t.*, s.sport_name, s.id as sport_id, s.icon as sport_icon 
                      FROM teams t 
                      JOIN sports_categories s ON t.sport_id = s.id 
                      WHERE t.id = $team_id")->fetch_assoc();

if (!$team) {
    setError('Sports squad node not found');
    header('Location: manage_teams.php');
    exit();
}

// Get current team players
$current_players = array_column($conn->query("SELECT player_id FROM team_players WHERE team_id = $team_id")->fetch_all(MYSQLI_ASSOC), 'player_id');

// Get available players for this sport
$available_players = $conn->query("SELECT DISTINCT p.id, p.name, p.register_number, p.department, p.gender
                                   FROM players p
                                   JOIN player_sports ps ON p.id = ps.player_id
                                   WHERE ps.sport_id = {$team['sport_id']} AND p.status = 'active'
                                   ORDER BY p.name")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_players = $_POST['players'] ?? [];

    // Remove all current assignments
    $conn->query("DELETE FROM team_players WHERE team_id = $team_id");

    // Add new assignments
    if (!empty($selected_players)) {
        $stmt = $conn->prepare("INSERT INTO team_players (team_id, player_id) VALUES (?, ?)");
        foreach ($selected_players as $p_id) {
            $stmt->bind_param("ii", $team_id, $p_id);
            $stmt->execute();
        }
        $stmt->close();
    }

    logActivity($conn, 'update', 'teams', $team_id, "Updated roster for team: {$team['team_name']}");
    setSuccess('Squad roster synchronized successfully');
    header('Location: manage_teams.php');
    exit();
}

/**
 * Avatar Resolver for Player Grid
 */
function getPlayerAvatarSmall($player_id, $gender = 'Male')
{
    $boy_avatars = ['boy-1.png', 'boy-2.png', 'boy-3.png', 'boy-4.png', 'boy-5.png', 'boy-6.png', 'boy-7.png'];
    $girl_avatars = ['girl-1.png', 'girl-2.png', 'girl-3.png', 'girl-4.png'];

    if (strtolower($gender) === 'female') {
        return '../assets/images/Avatar/' . $girl_avatars[$player_id % count($girl_avatars)];
    }
    return '../assets/images/Avatar/' . $boy_avatars[$player_id % count($boy_avatars)];
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="background: #f8fafc; padding: 40px;">

    <!-- PREMIUM ASSIGNMENT HEADER -->
    <div class="ultra-header">
        <div style="display:flex; justify-content: space-between; align-items: center; gap: 30px;">
            <div style="flex: 1;">
                <h1 class="ultra-title">Assign Squad Roster</h1>
                <p style="color: rgba(255,255,255,0.6); font-weight: 600; margin-top: 10px; font-size: 16px;">
                    Team: <span style="color: var(--neon-green);"><?php echo htmlspecialchars($team['team_name']); ?></span> • Category: <span style="color: var(--neon-green);"><?php echo htmlspecialchars($team['sport_name']); ?></span>
                </p>
            </div>

            <div style="display:flex; align-items: center; gap: 20px;">
                <a href="manage_teams.php" class="btn-reset-light" style="color: white; border-color: rgba(255,255,255,0.2);">
                    Discard Changes
                </a>
                <button type="submit" form="assignment-form" class="elite-action-btn">
                    Update Roster
                </button>
            </div>
        </div>

        <!-- PLAYER SEARCH BAR -->
        <div class="bento-filter-bar" style="margin-top: 30px;">
            <div style="flex: 1; position: relative; display: flex; align-items: center;">
                <img src="<?php echo $icons['search']; ?>" style="position: absolute; left: 20px; width: 18px; opacity: 0.4;">
                <input type="text" id="player_search" class="bento-search-input" placeholder="Search available athletes by name or registration number..." style="padding-left: 55px; width: 100%;">
            </div>
            <div style="display:flex; align-items:center; gap: 15px; padding: 0 20px; color: var(--slate-deep); font-weight: 700;">
                <span style="font-size: 13px;">Selected: <span id="selected-count" style="color: var(--primary-color);">0</span></span>
            </div>
        </div>
    </div>

    <div class="main-grid" style="margin-top: 30px;">
        <div class="charts-column">
            <div class="glass-card" style="padding: 0; overflow: hidden;">
                <form action="" method="POST" id="assignment-form" class="premium-form">
                    <div style="max-height: 650px; overflow-y: auto; padding: 25px;">
                        <?php if (count($available_players) > 0): ?>
                            <div class="player-selection-grid" id="player-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                                <?php foreach ($available_players as $player):
                                    $is_assigned = in_array($player['id'], $current_players);
                                ?>
                                    <label class="player-select-card <?php echo $is_assigned ? 'active' : ''; ?>" data-name="<?php echo strtolower($player['name']); ?>" data-reg="<?php echo strtolower($player['register_number']); ?>">
                                        <input type="checkbox" name="players[]" value="<?php echo $player['id']; ?>" <?php echo $is_assigned ? 'checked' : ''; ?> class="player-checkbox">

                                        <div class="player-select-inner">
                                            <div class="player-identity">
                                                <img src="<?php echo getPlayerAvatarSmall($player['id'], $player['gender']); ?>" class="ultra-avatar" style="width: 45px; height: 45px; border-radius: 12px;">
                                                <div style="flex: 1;">
                                                    <h4 class="player-name"><?php echo htmlspecialchars($player['name']); ?></h4>
                                                    <p class="player-meta"><?php echo htmlspecialchars($player['register_number']); ?></p>
                                                </div>
                                                <div class="check-indicator">
                                                    <div class="check-circle"></div>
                                                </div>
                                            </div>
                                            <div class="player-footer">
                                                <span><?php echo htmlspecialchars($player['department']); ?></span>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 100px 20px;">
                                <div style="font-size: 50px; margin-bottom: 20px;">🏃‍♂️</div>
                                <h3 style="color: var(--slate-deep); font-weight: 800;">No Available Athletes</h3>
                                <p style="color: #94a3b8; font-weight: 600; margin-bottom: 15px;">
                                    There are no active players registered for <strong><?php echo htmlspecialchars($team['sport_name']); ?></strong>.
                                </p>
                                <p style="color: #94a3b8; font-weight: 500; font-size: 13px; margin-bottom: 20px;">
                                    To assign players to this team, first register players for this sport.
                                </p>
                                <a href="add_player.php" class="elite-action-btn" style="display: inline-block; margin-right: 10px; text-decoration: none;">Register New Player</a>
                                <a href="manage_players.php" class="btn-reset-light" style="display: inline-block; text-decoration: none;">View All Players</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="side-column">
            <div class="glass-card text-center" style="padding: 40px 20px;">
                <h3 class="field-label mb-6 block">Target Squad</h3>
                <div class="sport-preview-box" style="display:flex; justify-content:center; margin: 30px 0;">
                    <div style="background: #f8fafc; width: 140px; height: 140px; display:flex; align-items:center; justify-content:center; border-radius: 40px; border: 4px solid white; box-shadow: 0 15px 35px rgba(0,0,0,0.08); overflow: hidden;">
                        <span style="font-size: 80px;"><?php echo htmlspecialchars($team['sport_icon'] ?? '🏆'); ?></span>
                    </div>
                </div>
                <h3 class="meta-handle" style="font-size: 20px; margin-bottom: 5px;"><?php echo htmlspecialchars($team['team_name']); ?></h3>
                <p class="subtitle-text" style="font-size:12px; line-height: 1.6; opacity: 0.8">Roster management for the official **<?php echo $team['sport_name']; ?>** lineup.</p>
            </div>

            <div class="glass-card mt-8">
                <h3 class="field-label mb-4 block">Assignment Rules</h3>
                <div class="stats-mini">
                    <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        • Only players registered for **<?php echo $team['sport_name']; ?>** are listed.<br>
                        • Changes take effect immediately upon saving.<br>
                        • Inactive players cannot be drafted into squads.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .player-select-card {
        cursor: pointer;
        position: relative;
    }

    .player-checkbox {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .player-select-inner {
        background: white;
        border: 1px solid #eef2ff;
        border-radius: 20px;
        padding: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .player-identity {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 12px;
    }

    .player-name {
        font-size: 15px;
        font-weight: 800;
        color: var(--slate-deep);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }

    .player-meta {
        font-size: 11px;
        color: var(--primary-color);
        font-weight: 700;
        margin: 2px 0 0 0;
    }

    .player-footer {
        border-top: 1px solid #f1f5f9;
        padding-top: 10px;
        font-size: 10px;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .check-indicator {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        border: 2px solid #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .check-circle {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: transparent;
        transition: all 0.3s;
    }

    /* Active State */
    .player-select-card.active .player-select-inner {
        border-color: var(--primary-color);
        background: var(--primary-lighter);
        box-shadow: 0 10px 25px rgba(140, 0, 255, 0.1);
        transform: translateY(-2px);
    }

    .player-select-card.active .check-indicator {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .player-select-card.active .check-circle {
        background: white;
    }

    .player-select-card:hover .player-select-inner {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    /* Selection Highlighting */
    .player-select-card input:checked+.player-select-inner {
        border-color: var(--primary-color);
        background: var(--primary-lighter);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('player_search');
        const countDisplay = document.getElementById('selected-count');
        const cards = document.querySelectorAll('.player-select-card');
        const checkboxes = document.querySelectorAll('.player-checkbox');

        function updateCount() {
            const checkedCount = document.querySelectorAll('.player-checkbox:checked').length;
            countDisplay.innerText = checkedCount;
        }

        // Initialize count
        updateCount();

        // Card Click Handler
        cards.forEach(card => {
            const checkbox = card.querySelector('.player-checkbox');
            card.addEventListener('click', function(e) {
                // Prevent double trigger if clicking directly on checkbox (though it's hidden)
                if (e.target.type !== 'checkbox') {
                    checkbox.checked = !checkbox.checked;
                    card.classList.toggle('active', checkbox.checked);
                    updateCount();
                }
            });

            // Sync initial state
            if (checkbox.checked) {
                card.classList.add('active');
            }
        });

        // Search Logic
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const reg = card.getAttribute('data-reg');
                if (name.includes(query) || reg.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>