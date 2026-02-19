<?php

/**
 * College Sports Management System
 * Logout Handler
 */

// Include configuration
require_once 'config.php';

// Log logout activity before destroying session
if (isLoggedIn()) {
    logActivity($conn, 'logout', 'users', $_SESSION['user_id'], 'User logged out');
}

// Destroy session
session_unset();
session_destroy();

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to login page
header('Location: index.php');
exit();
