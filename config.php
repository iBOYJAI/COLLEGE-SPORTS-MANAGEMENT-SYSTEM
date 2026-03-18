<?php

/**
 * College Sports Management System
 * Database Configuration & Core Functions
 * 
 * IMPORTANT: This system works completely offline
 * No external dependencies or CDN links allowed
 */

// Start session if not already started (with extended lifetime)
if (session_status() === PHP_SESSION_NONE) {
    // Extend session cookie lifetime (8 hours)
    $lifetime = 8 * 60 * 60;
    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path'     => '/',
        'secure'   => false, // set true if you serve over HTTPS
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

// Error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================
// DATABASE CONFIGURATION
// ============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sports_management');

// ============================================
// PATH CONFIGURATION
// ============================================
define('BASE_PATH', __DIR__);
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOAD_PATH', ASSETS_PATH . '/uploads');

// ============================================
// DATABASE CONNECTION
// ============================================
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user has specific role
 * @param string $role - Required role (admin/staff)
 * @return bool
 */
function hasRole($role)
{
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Require login - redirect to login page if not logged in
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ' . getBaseUrl() . 'index.php');
        exit();
    }
}

/**
 * Require admin role - redirect if not admin
 */
function requireAdmin()
{
    requireLogin();
    if (!hasRole('admin')) {
        header('Location: ' . getBaseUrl() . 'staff/dashboard.php');
        exit();
    }
}

/**
 * Get base URL of the application
 * @return string
 */
/**
 * Get base URL of the application (Project Root URL)
 * @return string
 */
function getBaseUrl()
{
    if (php_sapi_name() === 'cli') {
        return 'http://localhost/COLLEGE-SPORTS-MANAGEMENT-SYSTEM/';
    }

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Normalize slashes for Windows compatibility
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    $base_path = str_replace('\\', '/', BASE_PATH);

    // Calculate the project path relative to document root
    $project_path = str_replace($doc_root, '', $base_path);

    // Ensure it starts with a slash and ends with a slash
    $project_path = '/' . trim($project_path, '/') . '/';
    // If project is at root, it will be just '/'
    if ($project_path === '//') $project_path = '/';

    return $protocol . '://' . $host . $project_path;
}

/**
 * Sanitize input data
 * @param string $data - Input data
 * @return string
 */
function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 * @param string $email
 * @return bool
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Log activity to database
 * @param mysqli $conn - Database connection
 * @param string $action_type - Type of action (create, update, delete, login, logout)
 * @param string $module - Module name (users, players, teams, etc.)
 * @param int $record_id - ID of affected record
 * @param string $description - Description of action
 */
function logActivity($conn, $action_type, $module, $record_id = null, $description = '')
{
    $user_id = $_SESSION['user_id'] ?? null;
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

    // FK PROTECTION: Only attempt log if user still exists in DB
    // This prevents crashes after a database reset/truncate
    if ($user_id) {
        $check = $conn->query("SELECT id FROM users WHERE id = $user_id");
        if ($check->num_rows === 0) {
            $user_id = null; // Log as system/anonymous if user record is gone
        }
    }

    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action_type, module, record_id, description, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $user_id, $action_type, $module, $record_id, $description, $ip_address);
    $stmt->execute();
    $stmt->close();
}

/**
 * Set success message in session
 * @param string $message
 */
function setSuccess($message)
{
    $_SESSION['success_message'] = $message;
}

/**
 * Set error message in session
 * @param string $message
 */
function setError($message)
{
    $_SESSION['error_message'] = $message;
}

/**
 * Get and clear success message
 * @return string|null
 */
function getSuccess()
{
    if (isset($_SESSION['success_message'])) {
        $message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        return $message;
    }
    return null;
}

/**
 * Get and clear error message
 * @return string|null
 */
function getError()
{
    if (isset($_SESSION['error_message'])) {
        $message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
        return $message;
    }
    return null;
}

/**
 * Format date for display
 * @param string $date - Date string
 * @param string $format - Desired format
 * @return string
 */
function formatDate($date, $format = 'd-m-Y')
{
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Format time for display
 * @param string $time - Time string
 * @param string $format - Desired format
 * @return string
 */
function formatTime($time, $format = 'h:i A')
{
    if (empty($time)) return '';
    return date($format, strtotime($time));
}

/**
 * Get profile photo URL (Handles system avatars vs custom uploads)
 * @param string $photo - Filename from database
 * @return string - Full URL to image
 */
function getUserPhoto($photo)
{
    if (empty($photo)) return getBaseUrl() . 'assets/images/Avatar/boy-1.png';

    // Check if it's a system avatar
    if (strpos($photo, 'boy-') === 0 || strpos($photo, 'girl-') === 0) {
        return getBaseUrl() . 'assets/images/Avatar/' . $photo;
    }

    // Check if file exists in uploads
    $upload_path = UPLOAD_PATH . '/users/' . $photo;
    if (file_exists($upload_path)) {
        return getBaseUrl() . 'assets/uploads/users/' . $photo;
    }

    // Fallback to default system avatar
    return getBaseUrl() . 'assets/images/Avatar/boy-1.png';
}

/**
 * Get athlete photo URL (Handles system avatars vs custom uploads)
 * @param int $player_id
 * @param string $photo - Filename from database
 * @param string $gender - "Male" or "Female"
 * @return string - Full URL to image
 */
function getPlayerPhoto($player_id, $photo, $gender = 'Male')
{
    $boy_avatars = ['boy-1.png', 'boy-2.png', 'boy-3.png', 'boy-4.png', 'boy-5.png', 'boy-6.png', 'boy-7.png'];
    $girl_avatars = ['girl-1.png', 'girl-2.png', 'girl-3.png', 'girl-4.png'];

    if (empty($photo)) {
        if (strtolower($gender) === 'female') {
            return getBaseUrl() . 'assets/images/Avatar/' . $girl_avatars[$player_id % count($girl_avatars)];
        }
        return getBaseUrl() . 'assets/images/Avatar/' . $boy_avatars[$player_id % count($boy_avatars)];
    }

    // Check if it's one of the system avatars
    if (strpos($photo, 'boy-') === 0 || strpos($photo, 'girl-') === 0) {
        return getBaseUrl() . 'assets/images/Avatar/' . $photo;
    }

    // Otherwise, assume it's in uploads
    return getBaseUrl() . 'assets/uploads/players/' . $photo;
}

/**
 * Get Sport Icon or Branding (Handles emojis vs custom uploads)
 * @param array $sport - Sport row from database (includes image and icon)
 * @return array - ['type' => 'emoji'|'image', 'value' => string]
 */
function getSportIcon($sport)
{
    if (!empty($sport['icon'])) {
        // Check if it's a file path
        if (strpos($sport['icon'], '.svg') !== false || strpos($sport['icon'], '/') !== false) {
            return [
                'type' => 'image',
                'value' => getBaseUrl() . 'assets/images/' . $sport['icon']
            ];
        }
        return [
            'type' => 'emoji',
            'value' => $sport['icon']
        ];
    }
    return [
        'type' => 'emoji',
        'value' => '🏆'
    ];
}

/**
 * Get Team Logo URL
 * @param array $team - Team row from database
 * @return string - Full URL to image
 */
function getTeamLogo($team)
{
    if (!empty($team['logo'])) {
        return getBaseUrl() . 'assets/images/' . $team['logo'];
    }
    return '';
}

/**
 * Calculate age from date of birth
 * @param string $dob - Date of birth
 * @return int
 */
function calculateAge($dob)
{
    $birthDate = new DateTime($dob);
    $today = new DateTime('today');
    return $birthDate->diff($today)->y;
}

/**
 * Handle file upload
 * @param array $file - $_FILES array element
 * @param string $upload_dir - Upload directory
 * @param array $allowed_types - Allowed file types
 * @param int $max_size - Max file size in bytes
 * @return array - ['success' => bool, 'filename' => string, 'error' => string]
 */
function uploadFile($file, $upload_dir, $allowed_types = ['jpg', 'jpeg', 'png'], $max_size = 2097152)
{
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'error' => 'No file uploaded'];
    }

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error occurred'];
    }

    // Check file size
    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'File size exceeds ' . ($max_size / 1048576) . 'MB'];
    }

    // Get file extension
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Check file type
    if (!in_array($file_ext, $allowed_types)) {
        return ['success' => false, 'error' => 'File type not allowed'];
    }

    // Generate unique filename
    $new_filename = uniqid() . '_' . time() . '.' . $file_ext;
    $target_path = $upload_dir . '/' . $new_filename;

    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
}

/**
 * Generate pagination HTML
 * @param int $current_page - Current page number
 * @param int $total_pages - Total number of pages
 * @param string $base_url - Base URL for pagination links
 * @return string - HTML for pagination
 */
function generatePagination($current_page, $total_pages, $base_url)
{
    if ($total_pages <= 1) return '';

    $html = '<div class="pagination">';

    // Previous button
    if ($current_page > 1) {
        $html .= '<a href="' . $base_url . '?page=' . ($current_page - 1) . '" class="page-link">&laquo; Previous</a>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $current_page) ? 'active' : '';
        $html .= '<a href="' . $base_url . '?page=' . $i . '" class="page-link ' . $active . '">' . $i . '</a>';
    }

    // Next button
    if ($current_page < $total_pages) {
        $html .= '<a href="' . $base_url . '?page=' . ($current_page + 1) . '" class="page-link">Next &raquo;</a>';
    }

    $html .= '</div>';
    return $html;
}

/**
 * Check if username exists
 * @param mysqli $conn - Database connection
 * @param string $username - Username to check
 * @param int $exclude_id - User ID to exclude (for edit)
 * @return bool
 */
function usernameExists($conn, $username, $exclude_id = null)
{
    if ($exclude_id) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $exclude_id);
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

/**
 * Check if email exists
 * @param mysqli $conn - Database connection
 * @param string $email - Email to check
 * @param int $exclude_id - User ID to exclude (for edit)
 * @return bool
 */
function emailExists($conn, $email, $exclude_id = null)
{
    if ($exclude_id) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $exclude_id);
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

/**
 * Get time elapsed string (e.g., "2 hours ago", "just now")
 * @param string $datetime - Datetime string
 * @return string
 */
function time_elapsed_string($datetime)
{
    if (empty($datetime)) return 'Unknown';

    try {
        $now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $ago = new DateTime($datetime, new DateTimeZone('Asia/Kolkata'));
        $diff = $now->diff($ago);

        // If in the future, return the date
        if ($diff->invert == 0) {
            return formatDate($datetime, 'd M Y');
        }

        if ($diff->d == 0) {
            if ($diff->h == 0) {
                if ($diff->i == 0) {
                    return 'just now';
                }
                return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
            }
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        } elseif ($diff->d == 1) {
            return 'yesterday';
        } elseif ($diff->d < 7) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        } elseif ($diff->d < 30) {
            $weeks = floor($diff->d / 7);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
        } else {
            return formatDate($datetime, 'd M Y');
        }
    } catch (Exception $e) {
        return formatDate($datetime, 'd M Y');
    }
}

// Include Icon Configuration
require_once __DIR__ . '/includes/icons.php';


// ============================================
// CONFIGURATION COMPLETE
// ============================================
