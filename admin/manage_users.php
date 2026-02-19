<?php

/**
 * College Sports Management System
 * ULTRA-EXTREME RECONSTRUCTION - User Management
 */

require_once '../config.php';
requireAdmin();

$page_title = 'User Management';
$current_page = 'users';

// Search and filter params
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : 'all';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'table';

// Pagination
$per_page = ($view_mode === 'card') ? 12 : 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

// Build query
$where_conditions = ["status != 'deleted'"];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(full_name LIKE ? OR username LIKE ? OR email LIKE ?)";
    $search_param = "%$search%";
    $params = array_fill(0, 3, $search_param);
    $types = 'sss';
}

if ($role_filter !== 'all') {
    $where_conditions[] = "role = ?";
    $params[] = $role_filter;
    $types .= 's';
}

if ($status_filter !== 'all') {
    $where_conditions[] = "status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

$where_clause = implode(' AND ', $where_conditions);

// Count total records
$count_query = "SELECT COUNT(*) as total FROM users WHERE $where_clause";
if (!empty($params)) {
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $total_records = $count_stmt->get_result()->fetch_assoc()['total'];
} else {
    $total_records = $conn->query($count_query)->fetch_assoc()['total'];
}

$total_pages = ceil($total_records / $per_page);

// Fetch entities
$query = "SELECT id, full_name, username, email, role, status, phone, photo, gender, created_at 
          FROM users 
          WHERE $where_clause 
          ORDER BY created_at DESC 
          LIMIT ? OFFSET ?";

$params_fetch = $params;
$params_fetch[] = $per_page;
$params_fetch[] = $offset;
$types_fetch = $types . 'ii';

$stmt = $conn->prepare($query);
$stmt->bind_param($types_fetch, ...$params_fetch);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="dashboard-container" style="background: #f8fafc; padding: 40px;">

    <!-- ULTRA COMMAND HEADER -->
    <div class="ultra-header">
        <div style="display:flex; justify-content: space-between; align-items: center; gap: 30px;">
            <div style="flex: 1;">
                <h1 class="ultra-title">User Management</h1>
                <p style="color: rgba(255,255,255,0.6); font-weight: 600; margin-top: 10px; font-size: 16px;">
                    Active Users: <span style="color: var(--neon-green);"><?php echo $total_records; ?></span> registered users.
                </p>
            </div>

            <div style="display:flex; align-items: center; gap: 20px;">
                <div class="view-toggle-box">
                    <a href="?view=table<?php echo $search ? '&search=' . $search : ''; ?>" class="toggle-btn <?php echo $view_mode === 'table' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['dashboard']; ?>" class="icon-sm">
                        <span>List View</span>
                    </a>
                    <a href="?view=card<?php echo $search ? '&search=' . $search : ''; ?>" class="toggle-btn <?php echo $view_mode === 'card' ? 'active' : ''; ?>">
                        <img src="<?php echo $icons['teams']; ?>" class="icon-sm">
                        <span>Grid View</span>
                    </a>
                </div>
                <a href="add_user.php" class="elite-action-btn">
                    <img src="<?php echo $icons['add']; ?>" style="width: 18px; filter: brightness(0) invert(1);">
                    Add New User
                </a>
            </div>
        </div>

        <!-- BENTO FILTER BAR -->
        <form action="" method="GET" class="bento-filter-bar">
            <input type="hidden" name="view" value="<?php echo $view_mode; ?>">
            <div style="flex: 2; position: relative; display: flex; align-items: center;">
                <img src="<?php echo $icons['search']; ?>" style="position: absolute; left: 20px; width: 18px; opacity: 0.4;">
                <input type="text" name="search" class="bento-search-input" placeholder="Search names, usernames, or email..." value="<?php echo htmlspecialchars($search); ?>" style="padding-left: 55px; width: 100%;">
            </div>

            <select name="role" class="bento-select">
                <option value="all">All Roles</option>
                <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="staff" <?php echo $role_filter === 'staff' ? 'selected' : ''; ?>>Staff</option>
            </select>

            <select name="status" class="bento-select">
                <option value="all">All Status</option>
                <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active Only</option>
                <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive Only</option>
            </select>

            <button type="submit" class="elite-action-btn filter-submit-btn">Apply Filters</button>
        </form>
    </div>

    <?php if ($view_mode === 'table'): ?>
        <!-- ULTRA TILE LIST -->
        <div class="ultra-table-container">
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <div class="ultra-table-row">
                        <div class="identity-cluster">
                            <img src="<?php echo getUserPhoto($user['photo']); ?>" class="ultra-avatar">
                            <div>
                                <h3 class="meta-handle"><?php echo htmlspecialchars($user['full_name']); ?></h3>
                                <p class="meta-subtext">Username: <span style="color: var(--primary-color)">@<?php echo strtoupper($user['username']); ?></span></p>
                            </div>
                        </div>

                        <div style="flex: 1.5; display: flex; flex-direction: column;">
                            <span class="meta-subtext">Email Address</span>
                            <span style="font-weight: 700; color: var(--slate-deep);"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>

                        <div style="flex: 1.2; text-align: center;">
                            <span class="tier-pill <?php echo $user['role']; ?>">
                                <?php echo strtoupper($user['role']); ?>
                            </span>
                        </div>

                        <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
                            <div class="status-neon <?php echo $user['status']; ?>"></div>
                            <span class="meta-subtext" style="font-weight: 900;"><?php echo strtoupper($user['status']); ?></span>
                        </div>

                        <div style="display:flex; gap: 12px; margin-left: 20px;">
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="action-btn edit" style="background: #f1f5f9; border-radius: 15px; padding: 14px; transition: all 0.3s;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 20px;">
                            </a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <button class="action-btn delete delete-user-trigger" data-id="<?php echo $user['id']; ?>" data-name="<?php echo htmlspecialchars($user['full_name']); ?>" style="background: #fff1f2; border-radius: 15px; padding: 14px; transition: all 0.3s;">
                                    <img src="<?php echo $icons['delete']; ?>" style="width: 20px;">
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 100px; background: white; border-radius: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.02);">
                    <img src="<?php echo $icons['info']; ?>" style="width: 60px; opacity: 0.1; margin-bottom: 20px;">
                    <h2 style="color: var(--slate-deep); font-weight: 900;">No Users Found</h2>
                    <p style="color: #94a3b8; font-weight: 600; margin-top: 10px;">We couldn't find any user accounts matching your search.</p>
                    <a href="manage_users.php" class="elite-action-btn" style="width: auto; display: inline-block; margin-top: 30px;">Clear Filters</a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- ELITE GRID DOSSIERS -->
        <div class="elite-grid">
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <div class="elite-dossier">
                        <div class="dossier-header">
                            <div style="display:flex; gap: 18px; align-items: center;">
                                <img src="<?php echo getUserPhoto($user['photo']); ?>" class="ultra-avatar dossier-avatar">
                                <div>
                                    <h3 class="meta-handle" style="font-size: 20px; margin-bottom: 4px;"><?php echo htmlspecialchars($user['full_name']); ?></h3>
                                    <p class="meta-subtext" style="color: var(--primary-color); font-size: 11px;">@<?php echo strtoupper($user['username']); ?></p>
                                </div>
                            </div>
                            <span class="tier-pill <?php echo $user['role']; ?>" style="font-size: 10px;"><?php echo strtoupper($user['role']); ?></span>
                        </div>

                        <div class="detail-box">
                            <p class="meta-subtext" style="font-size: 10px; margin-bottom: 4px;">Email Address</p>
                            <p style="font-weight: 700; font-size: 14px; color: var(--slate-deep);"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>

                        <div class="dossier-details-grid">
                            <div class="detail-box" style="margin-bottom: 0;">
                                <p class="meta-subtext" style="font-size: 10px; margin-bottom: 4px;">Status</p>
                                <div class="state-indicator">
                                    <div class="status-neon <?php echo $user['status']; ?>"></div>
                                    <span class="status-value"><?php echo strtoupper($user['status']); ?></span>
                                </div>
                            </div>
                            <div class="detail-box" style="margin-bottom: 0;">
                                <p class="meta-subtext" style="font-size: 10px; margin-bottom: 4px;">Gender</p>
                                <p class="gender-value"><?php echo strtoupper($user['gender'] ?: 'Other'); ?></p>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 50px 50px; gap: 12px; margin-top: 20px;">
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="elite-action-btn" title="Edit User" style="padding: 0; display: flex; align-items: center; justify-content: center; height: 50px; border-radius: 16px;">
                                <img src="<?php echo $icons['edit']; ?>" style="width: 20px; filter: brightness(0) invert(1);">
                            </a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <button class="delete-user-trigger" data-id="<?php echo $user['id']; ?>" data-name="<?php echo htmlspecialchars($user['full_name']); ?>" title="Delete User" style="background: #fff1f2; border-radius: 16px; border: none; color: #ef4444; cursor: pointer; transition: all 0.3s; font-weight: 900; display: flex; align-items: center; justify-content: center; font-size: 16px; height: 50px;">
                                    <img src="<?php echo $icons['delete']; ?>" style="width: 18px; filter: invert(41%) sepia(85%) saturate(3474%) hue-rotate(338deg) brightness(98%) contrast(92%);">
                                </button>
                            <?php else: ?>
                                <div style="width: 50px;"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- ELITE PAGINATION -->
    <?php if ($total_pages > 1): ?>
        <div style="display:flex; justify-content: center; gap: 15px; margin-top: 80px;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php $isAct = ($i === $page) ? 'background: var(--primary-color); color: white; transform: scale(1.1); box-shadow: 0 15px 35px rgba(140, 0, 255, 0.3);' : 'background: white; color: var(--slate-deep);'; ?>
                <a href="?view=<?php echo $view_mode; ?>&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
                    style="width: 55px; height: 55px; border-radius: 20px; display:flex; align-items: center; justify-content: center; font-weight: 900; text-decoration: none; transition: all 0.3s; <?php echo $isAct; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elite deletion handler
        document.querySelectorAll('.delete-user-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                if (confirm(`Are you sure you want to delete user: ${name}? This action cannot be undone.`)) {
                    window.location.href = `delete_user.php?id=${id}`;
                }
            });
        });

        // Hover effect for elite buttons
        const actionBtns = document.querySelectorAll('.elite-action-btn');
        actionBtns.forEach(btn => {
            btn.onmouseenter = () => btn.style.transform = 'translateY(-4px)';
            btn.onmouseleave = () => btn.style.transform = 'translateY(0)';
        });
    });
</script>

<?php include '../includes/footer.php'; ?>