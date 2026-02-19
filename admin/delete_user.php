<?php

/**
 * College Sports Management System
 * Delete User
 */

require_once '../config.php';
requireAdmin();

// Get user ID
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id === 0) {
    setError('Invalid user ID');
    header('Location: manage_users.php');
    exit();
}

// Prevent deleting own account
if ($user_id == $_SESSION['user_id']) {
    setError('You cannot delete your own account');
    header('Location: manage_users.php');
    exit();
}

// Check if user exists
$stmt = $conn->prepare("SELECT full_name, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    setError('User not found');
    header('Location: manage_users.php');
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Check if this is the last admin
if ($user['role'] === 'admin') {
    $admin_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin' AND status = 'active'")->fetch_assoc()['count'];

    if ($admin_count <= 1) {
        setError('Cannot delete the last admin user');
        header('Location: manage_users.php');
        exit();
    }
}

// Soft delete (set status to 'deleted')
$stmt = $conn->prepare("UPDATE users SET status = 'deleted', deleted_at = NOW() WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    logActivity($conn, 'delete', 'users', $user_id, "Deleted user: " . $user['full_name']);
    setSuccess('User deleted successfully');
} else {
    setError('Failed to delete user');
}

$stmt->close();
header('Location: manage_users.php');
exit();
