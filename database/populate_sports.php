<?php

/**
 * College Sports Management System
 * Database Population Routine - Expanded Sports
 * 
 * Run this script to sync the 100+ sport registry with the database.
 */

require_once '../config.php';
require_once '../includes/sport_list.php';

// Only allow Admins to run this
requireAdmin();

$added = 0;
$skipped = 0;
$errors = [];

foreach ($sport_registry as $sport) {
    $name = $sport['name'];
    $icon = $sport['icon'];
    $type = $sport['type'];

    // Check if already exists
    $check = $conn->prepare("SELECT id FROM sports_categories WHERE sport_name = ?");
    $check->bind_param("s", $name);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $skipped++;
        $check->close();
        continue;
    }
    $check->close();

    // Set default squad sizes based on type
    $min = 1;
    $max = 1;
    if ($type === 'team' || $type === 'both') {
        $min = 2; // Default for team sports
        $max = 20; // Default max
    }

    $desc = "Official $name discipline for college athletics.";
    $status = 'active';

    $stmt = $conn->prepare("INSERT INTO sports_categories (sport_name, icon, description, category_type, min_players, max_players, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssiss", $name, $icon, $desc, $type, $min, $max, $status);

    if ($stmt->execute()) {
        $added++;
    } else {
        $errors[] = "Error adding $name: " . $conn->error;
    }
    $stmt->close();
}

echo "<div style='font-family: sans-serif; padding: 40px; line-height: 1.6;'>";
echo "<h1>Sport Synchronization Complete</h1>";
echo "<p style='color: green;'>Added: <strong>$added</strong> new sports.</p>";
echo "<p style='color: orange;'>Skipped (already exists): <strong>$skipped</strong>.</p>";

if (!empty($errors)) {
    echo "<h2>Errors Encountered:</h2><ul>";
    foreach ($errors as $err) echo "<li style='color: red;'>$err</li>";
    echo "</ul>";
}

echo "<br><a href='../admin/manage_sports.php' style='display: inline-block; background: #8c00ff; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;'>Return to Dashboard</a>";
echo "</div>";
