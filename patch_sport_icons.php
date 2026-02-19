<?php

/**
 * Database Patch: Sync Sport Emojis
 */

require_once 'config.php';

// Include the registry
require_once 'includes/sport_list.php';

echo "Starting Sport Icon Sync...\n";

foreach ($sport_registry as $sport) {
    $name = $conn->real_escape_string($sport['name']);
    $icon = $conn->real_escape_string($sport['icon']);

    // Attempt to update existing category by name
    $sql = "UPDATE sports_categories SET icon = '$icon' WHERE sport_name = '$name'";
    if ($conn->query($sql)) {
        if ($conn->affected_rows > 0) {
            echo "Updated: $name -> $icon\n";
        }
    } else {
        echo "Error updating $name: " . $conn->error . "\n";
    }
}

echo "Sync Complete.\n";
