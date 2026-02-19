<?php

/**
 * Massive Data Seeder for Sports Management System
 * Generates highly realistic data for players, teams, matches, results, and performance.
 */

require_once dirname(__DIR__) . '/config.php';

// Ensure BASE_PATH is available for asset discovery logic
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// disable time limit
set_time_limit(0);

echo "<h1>Starting Massive Data Seeding...</h1>";

// 1. Reset Database Data (Keep Schema)
$conn->query("SET FOREIGN_KEY_CHECKS=0");
$tables = ['players', 'teams', 'matches', 'player_sports', 'team_players', 'match_results', 'player_performance', 'certificates', 'activity_log'];
foreach ($tables as $table) {
    if ($conn->query("TRUNCATE TABLE $table")) {
        echo "Cleared table: $table<br>";
    }
}
$conn->query("SET FOREIGN_KEY_CHECKS=1");

// 1.5 Ensure Default Users (Admin & Staff) First
// This is critical because activity_log references user_id
echo "<h3>Initializing Core Operators...</h3>";
$password_hash = password_hash('password', PASSWORD_DEFAULT);
$conn->query("DELETE FROM users WHERE username IN ('admin', 'staff')");
$status = "active";

$stmt_admin = $conn->prepare("INSERT INTO users (full_name, username, email, gender, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$admin_name = "\\dinesh";
$admin_user = "admin";
$admin_email = "admin@college.edu";
$admin_gender = "male";
$admin_role = "admin";
$stmt_admin->bind_param("sssssss", $admin_name, $admin_user, $admin_email, $admin_gender, $password_hash, $admin_role, $status);
$stmt_admin->execute();
$admin_db_id = $stmt_admin->insert_id;

$stmt_staff = $conn->prepare("INSERT INTO users (full_name, username, email, gender, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$staff_name = "Department Staff";
$staff_user = "staff";
$staff_email = "staff@college.edu";
$staff_gender = "male";
$staff_role = "staff";
$stmt_staff->bind_param("sssssss", $staff_name, $staff_user, $staff_email, $staff_gender, $password_hash, $staff_role, $status);
$stmt_staff->execute();
$staff_db_id = $stmt_staff->insert_id;

echo "System Operators Synchronized.<br>";

// 2. Fetch & Update Sports with Real Emojis from Registry
echo "<h3>Synchronizing Emoji Registry...</h3>";
require_once BASE_PATH . '/includes/sport_list.php';
$sports = $conn->query("SELECT * FROM sports_categories")->fetch_all(MYSQLI_ASSOC);

// Create a lookup map for the registry
$registry_map = [];
foreach ($sport_registry as $item) {
    $registry_map[strtolower($item['name'])] = $item['icon'];
}

foreach ($sports as $s) {
    $sname = strtolower($s['sport_name']);
    $emoji = $registry_map[$sname] ?? '🏆'; // Fallback to trophy

    // Update to use Emoji and Clear Image column for categories
    $conn->query("UPDATE sports_categories SET icon = '$emoji', image = NULL WHERE id = {$s['id']}");
}

// Re-fetch with updated icons
$sports = $conn->query("SELECT * FROM sports_categories")->fetch_all(MYSQLI_ASSOC);
$sport_map = [];
foreach ($sports as $s) $sport_map[$s['id']] = $s;

// 3. Generate Players (200+)
$first_names = ['Anand', 'Arjun', 'Bala', 'Dinesh', 'Ganesh', 'Hari', 'Ilayaraja', 'Jeeva', 'Karthik', 'Loganathan', 'Murali', 'Naveen', 'Prabhu', 'Rajesh', 'Santhosh', 'Thamarai', 'Udaya', 'Vignesh', 'Yuvaraj', 'Abirami', 'Bhuvaneswari', 'Chitra', 'Deepika', 'Ezhil', 'Gayathri', 'Indumathi', 'Janani', 'Kavitha', 'Lakshmi', 'Meenakshi', 'Nandhini', 'Oviya', 'Priyanka', 'Ramya', 'Sangeetha', 'Tamilselvi', 'Uma', 'Vidya', 'Yamini'];
$last_names = ['Rajan', 'Kumar', 'Selvam', 'Manickam', 'Arumugam', 'Pandi', 'Velan', 'Samy', 'Gounder', 'Thevar', 'Iyer', 'Chettiar', 'Nadarp', 'Pillai', 'Mudaliar', 'Reddy', 'Naidu'];
$departments = ['Computer Science', 'Information Technology', 'Electronics', 'Mechanical', 'Civil', 'Electrical', 'Bio-Tech', 'BCA', 'B.Com', 'Physics', 'Mathematics'];
$years = ['I', 'II', 'III', 'IV'];

$generated_players = [];

echo "<h3>Generating Players...</h3>";
$stmt = $conn->prepare("INSERT INTO players (name, register_number, dob, age, gender, blood_group, department, year, mobile, email, status, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

for ($i = 0; $i < 200; $i++) {
    $fname = $first_names[array_rand($first_names)];
    $lname = $last_names[array_rand($last_names)];
    $name = "$fname $lname";

    // Heuristic gender
    $female_names = ['Abirami', 'Bhuvaneswari', 'Chitra', 'Deepika', 'Ezhil', 'Gayathri', 'Indumathi', 'Janani', 'Kavitha', 'Lakshmi', 'Meenakshi', 'Nandhini', 'Oviya', 'Priyanka', 'Ramya', 'Sangeetha', 'Tamilselvi', 'Uma', 'Vidya', 'Yamini'];
    $gender = in_array($fname, $female_names) ? 'Female' : 'Male';

    $reg_num = "REG2022" . str_pad($i, 4, '0', STR_PAD_LEFT);
    $age = rand(18, 22);
    $dob = date('Y-m-d', strtotime("-$age years"));
    $bg = ['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-'][rand(0, 7)];
    $dept = $departments[array_rand($departments)];
    $year = $years[array_rand($years)];
    $mobile = '9' . rand(100000000, 999999999);
    $email = strtolower($fname . '.' . $lname . $i . '@college.edu');

    // Asset Discovery: Player Avatars
    $avatar_dir = BASE_PATH . '/assets/images/Avatar/';
    $boys = glob($avatar_dir . 'boy-*.png');
    $girls = glob($avatar_dir . 'girl-*.png');

    $avatar = '';
    if ($gender === 'Female' && !empty($girls)) {
        $avatar = basename($girls[array_rand($girls)]);
    } elseif (!empty($boys)) {
        $avatar = basename($boys[array_rand($boys)]);
    }

    $statusVal = 'active';
    $stmt->bind_param("sssissssssss", $name, $reg_num, $dob, $age, $gender, $bg, $dept, $year, $mobile, $email, $statusVal, $avatar);
    $stmt->execute();
    $pid = $stmt->insert_id;
    $generated_players[] = ['id' => $pid, 'name' => $name, 'gender' => $gender];

    $num_sports = rand(1, 3);
    $player_sports_keys = array_rand($sport_map, $num_sports);
    if (!is_array($player_sports_keys)) $player_sports_keys = [$player_sports_keys];

    foreach ($player_sports_keys as $sid) {
        $conn->query("INSERT INTO player_sports (player_id, sport_id, is_primary) VALUES ($pid, $sid, 1)");
    }
}
echo "Created " . count($generated_players) . " players.<br>";

// 4. Create Teams
$tn_cities = ['Chennai', 'Madurai', 'Coimbatore', 'Trichy', 'Salem', 'Tirunelveli', 'Erode', 'Vellore', 'Thanjavur', 'Kanchipuram'];
$team_suffixes = ['Kings', 'Warriors', 'Superstars', 'Titans', 'Lions', 'Panthers', 'Sharks', 'Wolves', 'Dragons', 'Falcons', 'Strikers', 'Eagles'];

// Asset Discovery: Team Logos/Icons (Expanded Library)
$logo_pool = [];
$icon_dirs = [
    BASE_PATH . '/assets/images/Notion-Resources/Notion-Icons/Accent-Color/svg/',
    BASE_PATH . '/assets/images/Notion-Resources/Notion-Icons/Duotone/svg/',
    BASE_PATH . '/assets/images/Notion-Resources/Notion-Club/Accent-Color/svg/',
    BASE_PATH . '/assets/images/Notion-Resources/Notion-Icons/Regular/svg/'
];

foreach ($icon_dirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . 'ni-*.svg');
        foreach ($files as $f) {
            // RELATIVE PATH FIX: Store web-accessible paths
            // From: C:/xampp/htdocs/.../assets/images/Notion-Resources/...
            // To: Notion-Resources/...
            $normalized_f = str_replace('\\', '/', $f);
            $rel_path = str_replace(str_replace('\\', '/', BASE_PATH . '/assets/images/'), '', $normalized_f);
            $logo_pool[] = $rel_path;
        }
    }
}

echo "<h3>Generating Teams...</h3>";
$stmt = $conn->prepare("INSERT INTO teams (team_name, sport_id, coach_name, logo, status) VALUES (?, ?, ?, ?, 'active')");
$generated_teams = [];

foreach ($sports as $sport) {
    $count = ($sport['sport_name'] == 'Cricket' || $sport['sport_name'] == 'Football') ? 10 : 6;

    for ($t = 0; $t < $count; $t++) {
        $city = $tn_cities[array_rand($tn_cities)];
        $suffix = $team_suffixes[array_rand($team_suffixes)];
        $tname = "$city $suffix " . ($t + 1);
        $coach = $first_names[array_rand($first_names)] . " " . $last_names[array_rand($last_names)];

        // Assign a diverse real icon as logo
        $logo = !empty($logo_pool) ? $logo_pool[array_rand($logo_pool)] : '';

        $stmt->bind_param("siss", $tname, $sport['id'], $coach, $logo);
        $stmt->execute();
        $tid = $stmt->insert_id;
        $generated_teams[$sport['id']][] = $tid;

        $eligible_players = $conn->query("SELECT player_id FROM player_sports WHERE sport_id = {$sport['id']}")->fetch_all(MYSQLI_ASSOC);
        shuffle($eligible_players);
        $squad_size = min(count($eligible_players), rand($sport['min_players'], $sport['max_players']));
        $squad = array_slice($eligible_players, 0, $squad_size);

        foreach ($squad as $pl) {
            $conn->query("INSERT IGNORE INTO team_players (team_id, player_id) VALUES ($tid, {$pl['player_id']})");
        }
    }
}
echo "Created teams for all sports.<br>";

// 5. Generate Matches and Results (100+)
echo "<h3>Generating 100+ Matches & History...</h3>";

$stmt_match = $conn->prepare("INSERT INTO matches (sport_id, team1_id, team2_id, match_date, match_time, venue, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt_result = $conn->prepare("INSERT INTO match_results (match_id, team1_score, team2_score, winner_team_id, result_status) VALUES (?, ?, ?, ?, ?)");

$venues = ['M. A. Chidambaram Stadium', 'Jawaharlal Nehru Stadium', 'Salem Cricket Foundation Ground', 'NPR College Ground', 'SNR College Cricket Ground', 'ICL Sankar Nagar Ground', 'Tirunelveli Sports Complex'];

$total_matches = 0;
while ($total_matches < 150) {
    $random_sport_id = array_rand($generated_teams);
    $team_ids = $generated_teams[$random_sport_id];

    if (count($team_ids) < 2) continue;

    shuffle($team_ids);
    $t1 = $team_ids[0];
    $t2 = $team_ids[1];

    $is_past = (rand(0, 10) > 2); // 80% past matches
    if ($is_past) {
        $date = date('Y-m-d', strtotime("-" . rand(1, 100) . " days"));
        $status = 'completed';
    } else {
        $date = date('Y-m-d', strtotime("+" . rand(1, 30) . " days"));
        $status = 'scheduled';
    }

    $time = rand(9, 20) . ":" . (rand(0, 1) ? "00" : "30") . ":00";
    $venue = $venues[array_rand($venues)];

    $stmt_match->bind_param("iiissss", $random_sport_id, $t1, $t2, $date, $time, $venue, $status);
    $stmt_match->execute();
    $mid = $stmt_match->insert_id;
    $total_matches++;

    if ($status === 'completed') {
        $s_name = $sport_map[$random_sport_id]['sport_name'];
        if ($s_name == 'Cricket') {
            $s1 = rand(120, 240);
            $s2 = rand(120, 240);
        } else if ($s_name == 'Basketball') {
            $s1 = rand(70, 120);
            $s2 = rand(70, 120);
        } else if ($s_name == 'Football') {
            $s1 = rand(0, 6);
            $s2 = rand(0, 6);
        } else {
            $s1 = rand(0, 5);
            $s2 = rand(0, 5);
        }

        $winner = null;
        if ($s1 > $s2) {
            $winner = $t1;
            $conn->query("UPDATE teams SET matches_played = matches_played + 1, matches_won = matches_won + 1 WHERE id = $t1");
            $conn->query("UPDATE teams SET matches_played = matches_played + 1 WHERE id = $t2");
        } else if ($s2 > $s1) {
            $winner = $t2;
            $conn->query("UPDATE teams SET matches_played = matches_played + 1 WHERE id = $t1");
            $conn->query("UPDATE teams SET matches_played = matches_played + 1, matches_won = matches_won + 1 WHERE id = $t2");
        } else {
            $conn->query("UPDATE teams SET matches_played = matches_played + 1 WHERE id IN ($t1, $t2)");
        }

        $res_status = 'final';
        $stmt_result->bind_param("iiisi", $mid, $s1, $s2, $winner, $res_status);
        $stmt_result->execute();
    }
}


// 6. Seed Activity Logs
echo "<h3>Seeding Intelligence Feed...</h3>";
$activity_types = ['Player Registered', 'Team Created', 'Match Scheduled', 'Result Updated', 'Category Added'];
$tamil_names = ["Arun", "Bala", "Chandru", "Dinesh", "Ezhil", "Farooq", "Ganesh", "Hari", "Indran", "Jaya", "Karthik", "Lokesh", "Murali", "Naveen", "Oviya", "Prabhu", "Qadir", "Ramesh", "Suresh", "Tamil", "Uday", "Vijay", "Wilson", "Xavier", "Yuvraj", "Zahir"];

for ($i = 0; $i < 30; $i++) {
    $type = $activity_types[array_rand($activity_types)];
    $random_name = $tamil_names[array_rand($tamil_names)];
    $desc = "Activity log entry for $type by $random_name";
    $days_ago = rand(0, 10);
    $time = date('Y-m-d H:i:s', strtotime("-$days_ago days"));
    $conn->query("INSERT INTO activity_log (user_id, action_type, module, description, created_at) VALUES ($admin_db_id, 'create', 'system', '$desc', '$time')");
}

// 7. Seed Certificates (20-30 historical entries)
echo "<h3>Generating Historical Credentials...</h3>";
$cert_types = ['Winner', 'Runner-Up', 'Achievement', 'Participation'];
foreach (array_slice($generated_players, 0, 30) as $player) {
    if (rand(0, 10) > 6) { // 40% chance of having a certificate
        $type = $cert_types[array_rand($cert_types)];
        $sid = array_rand($sport_map);
        $sname = $sport_map[$sid]['sport_name'];
        $achievement = "Outstanding performance in $sname Annual Inter-College Meet.";
        $date = date('Y-m-d', strtotime("-" . rand(10, 100) . " days"));
        $conn->query("INSERT INTO certificates (player_id, certificate_type, sport_id, achievement, issue_date, generated_by) 
                      VALUES ({$player['id']}, '$type', $sid, '$achievement', '$date', $admin_db_id)");
    }
}

// 8. Seed Detailed Player Performance (For Analytics)
echo "<h3>Synthesizing Performance Big-Data...</h3>";
$past_matches = $conn->query("SELECT id FROM matches WHERE status = 'completed' LIMIT 50")->fetch_all(MYSQLI_ASSOC);
foreach ($past_matches as $m) {
    $players_in_match = $conn->query("SELECT p.id FROM players p 
                                      JOIN team_players tp ON p.id = tp.player_id 
                                      JOIN matches m ON (tp.team_id = m.team1_id OR tp.team_id = m.team2_id)
                                      WHERE m.id = {$m['id']}")->fetch_all(MYSQLI_ASSOC);

    foreach ($players_in_match as $p) {
        $rating = rand(60, 95) / 10;
        $conn->query("INSERT IGNORE INTO player_performance (match_id, player_id, performance_rating, participated) 
                      VALUES ({$m['id']}, {$p['id']}, $rating, 1)");
    }
}

echo "<h2>Seeding Integrated Reality Complete!</h2>";
