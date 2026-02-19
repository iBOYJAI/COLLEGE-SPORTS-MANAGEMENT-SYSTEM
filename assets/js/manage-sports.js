/**
 * Manage Sports JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const resetBtn = document.getElementById('reset-filters');

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', utils.debounce(applyFilters, 500));
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => window.location.href = 'manage_sports.php');
    }

    function applyFilters() {
        const search = searchInput.value;
        const status = statusFilter.value;
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status !== 'all') params.append('status', status);
        window.location.href = 'manage_sports.php?' + params.toString();
    }

    // Delete sport
    const deleteButtons = document.querySelectorAll('.delete-sport');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const players = parseInt(this.dataset.players);
            const teams = parseInt(this.dataset.teams);

            if (players > 0 || teams > 0) {
                const msg = `Cannot delete "${name}" because it has:\n${players} registered players\n${teams} active teams\n\nPlease remove all associations first.`;
                utils.notification.error(msg);
                return;
            }

            if (confirm(`Are you sure you want to delete "${name}"?\n\nThis action cannot be undone.`)) {
                window.location.href = 'delete_sport.php?id=' + id;
            }
        });
    });
});
