<?php

/**
 * College Sports Management System
 * Footer Component
 */
?>
</div> <!-- End main-content -->
</div> <!-- End app-wrapper -->

<!-- JavaScript - All Local, NO CDN -->
<script src="../assets/js/utils.js"></script>
<script>
    // Initialize on DOM load
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        if (sidebarToggle && sidebar && mainContent) {
            const appWrapper = document.querySelector('.app-wrapper');

            // Restore sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('sidebar-collapsed');
                if (appWrapper) {
                    appWrapper.classList.add('sidebar-collapsed');
                }
            }

            // Toggle sidebar and save state
            sidebarToggle.addEventListener('click', function() {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
                if (appWrapper) {
                    appWrapper.classList.toggle('sidebar-collapsed');
                }

                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });
        }

        // Global Search Logic (Live Suggestions)
        const globalSearch = document.getElementById('global-search');
        const suggestionBox = document.getElementById('search-suggestions');
        let searchTimeout;

        if (globalSearch && suggestionBox) {
            globalSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                if (query.length < 2) {
                    suggestionBox.style.display = 'none';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`../api/search.php?q=${encodeURIComponent(query)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Search results:', data);
                            suggestionBox.innerHTML = '';
                            if (data && data.length > 0) {
                                data.forEach(item => {
                                    const div = document.createElement('a');
                                    div.href = item.url;
                                    div.className = 'suggestion-item';
                                    div.innerHTML = `
                                        <div class="suggestion-info">
                                            <span class="suggestion-title">${item.title}</span>
                                            <span class="suggestion-type">${item.type} • ${item.subtitle}</span>
                                        </div>
                                    `;
                                    suggestionBox.appendChild(div);
                                });
                                suggestionBox.style.display = 'block';
                            } else {
                                suggestionBox.innerHTML = '<div style="padding: 12px; text-align: center; color: #999;">No results found</div>';
                                suggestionBox.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            suggestionBox.innerHTML = '<div style="padding: 12px; text-align: center; color: #f44;">Error loading results</div>';
                            suggestionBox.style.display = 'block';
                        });
                }, 300);
            });

            // Close suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (!globalSearch.contains(e.target) && !suggestionBox.contains(e.target)) {
                    suggestionBox.style.display = 'none';
                }
            });
        }

        // Notification toggle & Fetch
        const notifToggle = document.getElementById('notification-toggle');
        const notifMenu = document.getElementById('notification-menu');
        const notifList = document.getElementById('notification-list');
        const notifCount = document.getElementById('notif-count');

        const loadNotifications = () => {
            fetch('../api/notifications.php')
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Notifications:', data);
                    if (notifCount) notifCount.textContent = data.length;
                    if (notifList) {
                        notifList.innerHTML = '';
                        if (data && data.length > 0) {
                            data.forEach(n => {
                                const item = document.createElement('a');
                                item.href = n.url;
                                item.className = 'notif-item';
                                item.innerHTML = `
                                    <div class="notif-content">
                                        <div class="notif-text">${n.text}</div>
                                        <div class="notif-time">${n.time}</div>
                                    </div>
                                `;
                                notifList.appendChild(item);
                            });
                        } else {
                            notifList.innerHTML = '<div style="padding: 16px; text-align: center; color: #999;">No notifications</div>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Notification error:', error);
                    if (notifList) {
                        notifList.innerHTML = '<div style="padding: 16px; text-align: center; color: #f44;">Error loading notifications</div>';
                    }
                });
        };

        if (notifToggle && notifMenu) {
            notifToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                notifMenu.classList.toggle('active');
                if (notifMenu.classList.contains('active')) loadNotifications();
                if (userDropdown) userDropdown.classList.remove('active');
            });

            document.addEventListener('click', function(e) {
                if (!notifToggle.contains(e.target) && !notifMenu.contains(e.target)) {
                    notifMenu.classList.remove('active');
                }
            });
        }

        // Initial Notification Count on page load
        if (notifCount) {
            loadNotifications();
        }

        // User dropdown toggle
        const userMenuToggle = document.getElementById('user-menu-toggle');
        const userDropdown = document.getElementById('user-dropdown');

        console.log('User menu elements:', {
            userMenuToggle,
            userDropdown
        });

        if (userMenuToggle && userDropdown) {
            userMenuToggle.addEventListener('click', function(e) {
                console.log('User menu clicked!');
                e.stopPropagation();
                userDropdown.classList.toggle('active');
                console.log('User dropdown active?', userDropdown.classList.contains('active'));

                // Close notifications when user menu opens
                if (notifMenu) notifMenu.classList.remove('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuToggle.contains(e.target) && !userDropdown.contains(e.target)) {
                    if (userDropdown.classList.contains('active')) {
                        console.log('Closing user dropdown (clicked outside)');
                    }
                    userDropdown.classList.remove('active');
                }
            });
        } else {
            console.error('User menu elements not found!', {
                userMenuToggle,
                userDropdown
            });
        }

        // Display success/error messages from session
        <?php if ($success = getSuccess()): ?>
            utils.notification.success('<?php echo addslashes($success); ?>');
        <?php endif; ?>

        <?php if ($error = getError()): ?>
            utils.notification.error('<?php echo addslashes($error); ?>');
        <?php endif; ?>
    });
</script>

<?php if (isset($extra_js)): ?>
    <?php foreach ($extra_js as $js_file): ?>
        <script src="<?php echo $js_file; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>

</html>