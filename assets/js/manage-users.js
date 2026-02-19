/**
 * Manage Users JavaScript
 * Handles search, filters, and user actions
 */

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const roleFilter = document.getElementById('role-filter');
    const statusFilter = document.getElementById('status-filter');
    const resetBtn = document.getElementById('reset-filters');

    // Debounced search
    if (searchInput) {
        searchInput.addEventListener('input', utils.debounce(function () {
            applyFilters();
        }, 500));
    }

    // Filter changes
    if (roleFilter) {
        roleFilter.addEventListener('change', applyFilters);
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }

    // Reset filters
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            window.location.href = 'manage_users.php';
        });
    }

    // Apply filters function
    function applyFilters() {
        const search = searchInput.value;
        const role = roleFilter.value;
        const status = statusFilter.value;

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (role !== 'all') params.append('role', role);
        if (status !== 'all') params.append('status', status);

        window.location.href = 'manage_users.php?' + params.toString();
    }

    // Toggle status buttons
    const toggleButtons = document.querySelectorAll('.toggle-status');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.id;
            const currentStatus = this.dataset.status;
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

            if (confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this user?`)) {
                toggleUserStatus(userId, currentStatus, this);
            }
        });
    });

    // Toggle user status via AJAX
    function toggleUserStatus(userId, currentStatus, button) {
        utils.loading.show('Updating status...');

        const formData = new FormData();
        formData.append('action', 'toggle_status');
        formData.append('user_id', userId);
        formData.append('status', currentStatus);

        fetch('manage_users.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                utils.loading.hide();
                if (data.success) {
                    // Update badge
                    const badge = document.getElementById('status-badge-' + userId);
                    badge.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                    badge.className = 'badge ' + (data.new_status === 'active' ? 'badge-success' : 'badge-danger');

                    // Update button
                    button.dataset.status = data.new_status;
                    button.textContent = data.new_status === 'active' ? '🔴' : '🟢';

                    utils.notification.success('User status updated successfully');
                } else {
                    utils.notification.error(data.error || 'Failed to update status');
                }
            })
            .catch(error => {
                utils.loading.hide();
                utils.notification.error('Network error occurred');
            });
    }

    // Delete user buttons
    const deleteButtons = document.querySelectorAll('.delete-user');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.id;
            const userName = this.dataset.name;

            if (confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
                deleteUser(userId);
            }
        });
    });

    // Delete user function
    function deleteUser(userId) {
        utils.loading.show('Deleting user...');

        window.location.href = 'delete_user.php?id=' + userId;
    }
});
