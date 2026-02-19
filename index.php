<?php

/**
 * College Sports Management System
 * Redesigned Premium Login Page
 */

// Include configuration
require_once 'config.php';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    if (hasRole('admin')) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: staff/dashboard.php');
    }
    exit();
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, username, password, role, status FROM users WHERE username = ? AND status = 'active'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['login_time'] = time();

                logActivity($conn, 'login', 'users', $user['id'], 'User logged in');

                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: staff/dashboard.php');
                }
                exit();
            } else {
                $error = 'Invalid credentials. Please try again.';
            }
        } else {
            $error = 'Account not found or inactive.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CSMS Portal</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo $icons['favicon']; ?>">

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-wrapper">
        <!-- Left Side: Illustration & Branding -->
        <div class="login-side-illustration">
            <img src="<?php echo $icons['login_bg']; ?>" alt="Teamwork" class="illustration-img">
            <div class="illustration-text">
                <h2 class="illustration-title">College Sports Management</h2>
                <p class="illustration-desc">Empowering student-athletes to track performance, manage teams, and excel in collegiate sports.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-side-form">
            <div class="login-card">
                <div class="login-logo-container">
                    <img src="<?php echo $icons['login_character']; ?>" alt="CSMS" class="login-form-logo">
                    <h1 class="login-title">Welcome Back</h1>
                    <p class="login-subtitle">Please enter your details to sign in</p>
                </div>

                <!-- Error Display -->
                <div class="error-alert <?php echo !empty($error) ? 'show' : ''; ?>" id="error-alert">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <img src="<?php echo $icons['delete']; ?>" class="action-icon-sm" style="filter: invert(36%) sepia(84%) saturate(3015%) hue-rotate(345deg) brightness(98%) contrast(92%);">
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                </div>

                <form id="login-form" method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div class="input-container">
                            <span class="input-icon">
                                <img src="<?php echo $icons['users']; ?>" alt="user">
                            </span>
                            <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-container">
                            <span class="input-icon">
                                <img src="<?php echo $icons['settings']; ?>" alt="password">
                            </span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" id="toggle-password">
                                <img src="<?php echo $icons['view']; ?>" alt="toggle visibility">
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-btn" id="login-btn">
                        <div class="spinner" id="btn-spinner" style="display: none;"></div>
                        <span id="btn-text">Sign In</span>
                    </button>
                </form>

                <div class="demo-box">
                    <div class="demo-title">
                        <img src="<?php echo $icons['info'] ?? ''; ?>" style="width:14px; opacity:0.6;">
                        <span>Demo Credentials</span>
                    </div>
                    <p>Admin: <strong>admin</strong> / password</p>
                    <p>Staff: <strong>staff</strong> / password</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const loginBtn = document.getElementById('login-btn');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('toggle-password');

            // Password Show/Hide
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Optional: Swap icon if you have a "hide" icon
                this.classList.toggle('active');
            });

            // Loading state
            loginForm.addEventListener('submit', function() {
                loginBtn.classList.add('loading');
                btnText.style.display = 'none';
                btnSpinner.style.display = 'block';
                loginBtn.disabled = true;
            });
        });
    </script>
</body>

</html>