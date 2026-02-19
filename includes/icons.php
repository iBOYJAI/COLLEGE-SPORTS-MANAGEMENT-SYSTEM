<?php

/**
 * College Sports Management System
 * Icon and Image Configuration
 * 
 * Maps logical names to local asset paths.
 * Usage: $icons['dashboard']
 */

// Ensure getBaseUrl is available or use relative paths if included in context where it's known
// However, since this is usually included in config or header, we can rely on relative paths from the root
// Or better, return the path relative to the public root.

// We will use a helper function or just simple strings. 
// Assuming this is used where we can output logical paths.

$base_url = getBaseUrl();

$icons = [
    // Sidebar & Navigation Icons (Small 20x20)
    'dashboard'   => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-diagram-project.png',
    'users'       => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-users.png',
    'players'     => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-user-square.png',
    'teams'       => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-diagram-nested.png',
    'sports'      => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-award.png',
    'matches'     => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-calendar-check-circle.png',
    'results'     => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-clipboard-check.png',
    'performance' => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-diagram-cells.png',
    'statistics'  => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-ranking-star.png',
    'reports'     => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-file-text.png',
    'certificates' => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-award.png',
    'settings'    => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-laptop-fill.png',
    'logout'      => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-power-off.png',

    // Action Icons
    'add'         => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-plus.png',
    'edit'        => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-pen-line.png',
    'delete'      => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-x.png',
    'view'        => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-side-peek.png',
    'search'      => $base_url . 'assets/images/Notion-Resources/Notion-Club/Regular/png/nc-research-panel.png',
    'info'        => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-info.png',
    'notification' => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-bell.png',


    // Feature Illustrations (Large)
    'ill_dashboard' => $base_url . 'assets/images/0015-economy-finance-illustrations/DrawKit - Economy & Finance/PNG/9 - ECONOMY ANALYSIS.png',
    'ill_players'   => $base_url . 'assets/images/0010-people-working-illustrations/DrawKit - People Working Illustration Pack/PNG/character 1.png',
    'ill_teams'     => $base_url . 'assets/images/0073-teamwork-illustrations/DrawKit Vector Illustration Team Work/PNG/DrawKit Vector Illustration Team Work (1).png',
    'ill_sports'    => $base_url . 'assets/images/0015-economy-finance-illustrations/DrawKit - Economy & Finance/PNG/1 - REBUILD THE ECONOMY.png',
    'ill_matches'   => $base_url . 'assets/images/0015-economy-finance-illustrations/DrawKit - Economy & Finance/PNG/4 - BUDGETTING.png',
    'ill_reports'   => $base_url . 'assets/images/0015-economy-finance-illustrations/DrawKit - Economy & Finance/PNG/6 - FINANCES.png',

    // Login Icons
    'login_bg'      => $base_url . 'assets/images/0073-teamwork-illustrations/DrawKit Vector Illustration Team Work/PNG/DrawKit Vector Illustration Team Work (11).png',
    'login_character' => $base_url . 'assets/images/0126-phonies-illustrations/Phonies by DrawKit Vector Illustrations/PNG/Selfie_DrawKit_Vector_Illustrations.png',
    'favicon'       => $base_url . 'assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-medal.png',
];
