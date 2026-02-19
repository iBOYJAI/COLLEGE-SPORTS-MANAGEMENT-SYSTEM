<?php
require_once '../config.php';
requireAdmin();

$migrations = [
    "ALTER TABLE players ADD COLUMN IF NOT EXISTS photo VARCHAR(255) DEFAULT '' AFTER gender",
    "ALTER TABLE sports_categories ADD COLUMN IF NOT EXISTS image VARCHAR(255) DEFAULT '' AFTER icon",
    "ALTER TABLE teams ADD COLUMN IF NOT EXISTS logo VARCHAR(255) DEFAULT '' AFTER coach_name"
];

echo "<h1>Database Migration</h1>";
foreach ($migrations as $sql) {
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>Success: $sql</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . " ($sql)</p>";
    }
}

echo "<br><a href='../admin/dashboard.php'>Back to Dashboard</a>";
