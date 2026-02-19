/**
 * College Sports Management System
 * JavaScript Utility Functions
 * NO external dependencies - pure JavaScript only
 */

// ===== AJAX HELPER =====
const ajax = {
    /**
     * Perform GET request
     * @param {string} url - Request URL
     * @param {function} callback - Success callback function(data)
     * @param {function} errorCallback - Error callback function(error)
     */
    get: function(url, callback, errorCallback) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    callback(data);
                } catch(e) {
                    callback(xhr.responseText);
                }
            } else {
                if (errorCallback) errorCallback(xhr.statusText);
            }
        };
        xhr.onerror = function() {
            if (errorCallback) errorCallback('Network Error');
        };
        xhr.send();
    },

    /**
     * Perform POST request
     * @param {string} url - Request URL
     * @param {object} data - Data to send
     * @param {function} callback - Success callback
     * @param {function} errorCallback - Error callback
     */
    post: function(url, data, callback, errorCallback) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const responseData = JSON.parse(xhr.responseText);
                    callback(responseData);
                } catch(e) {
                    callback(xhr.responseText);
                }
            } else {
                if (errorCallback) errorCallback(xhr.statusText);
            }
        };
        xhr.onerror = function() {
            if (errorCallback) errorCallback('Network Error');
        };
        
        // Convert object to URL-encoded string
        const params = Object.keys(data)
            .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key]))
            .join('&');
        xhr.send(params);
    }
};

// ===== VALIDATION FUNCTIONS =====
const validation = {
    /**
     * Check if value is empty
     */
    isEmpty: function(value) {
        return value === null || value === undefined || value.trim() === '';
    },

    /**
     * Validate email format
     */
    isValidEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },

    /**
     * Validate phone number (10 digits)
     */
    isValidPhone: function(phone) {
        const re = /^\d{10}$/;
        return re.test(phone.replace(/[\s-]/g, ''));
    },

    /**
     * Validate username (alphanumeric, 4-20 characters)
     */
    isValidUsername: function(username) {
        const re = /^[a-zA-Z0-9_]{4,20}$/;
        return re.test(username);
    },

    /**
     * Check password strength
     * Returns object: {score: 0-4, feedback: string}
     */
    checkPasswordStrength: function(password) {
        let score = 0;
        const feedback = [];

        if (password.length >= 8) score++;
        else feedback.push('At least 8 characters');

        if (/[a-z]/.test(password)) score++;
        else feedback.push('Lowercase letter');

        if (/[A-Z]/.test(password)) score++;
        else feedback.push('Uppercase letter');

        if (/\d/.test(password)) score++;
        else feedback.push('Number');

        if (/[^a-zA-Z\d]/.test(password)) score++;
        else feedback.push('Special character');

        const strength = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        return {
            score: score,
            strength: strength[score],
            feedback: feedback
        };
    },

    /**
     * Validate date format (YYYY-MM-DD)
     */
    isValidDate: function(dateString) {
        const re = /^\d{4}-\d{2}-\d{2}$/;
        if (!re.test(dateString)) return false;
        const date = new Date(dateString);
        return date instanceof Date && !isNaN(date);
    },

    /**
     * Check if date is in the past
     */
    isPastDate: function(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return date < today;
    }
};

// ===== FORMATTING FUNCTIONS =====
const formatter = {
    /**
     * Format date for display
     */
    formatDate: function(dateString, format = 'DD-MM-YYYY') {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        if (format === 'DD-MM-YYYY') return `${day}-${month}-${year}`;
        if (format === 'MM/DD/YYYY') return `${month}/${day}/${year}`;
        if (format === 'YYYY-MM-DD') return `${year}-${month}-${day}`;
        return dateString;
    },

    /**
     * Format time for display
     */
    formatTime: function(timeString, format = '12h') {
        const [hours, minutes] = timeString.split(':');
        const h = parseInt(hours);
        const m = parseInt(minutes);

        if (format === '12h') {
            const ampm = h >= 12 ? 'PM' : 'AM';
            const displayHour = h % 12 || 12;
            return `${displayHour}:${String(m).padStart(2, '0')} ${ampm}`;
        }
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    },

    /**
     * Format phone number
     */
    formatPhone: function(phone) {
        const cleaned = phone.replace(/\D/g, '');
        if (cleaned.length === 10) {
            return cleaned.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        }
        return phone;
    },

    /**
     * Capitalize first letter
     */
    capitalize: function(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    },

    /**
     * Title case (capitalize each word)
     */
    titleCase: function(str) {
        return str.split(' ')
            .map(word => this.capitalize(word))
            .join(' ');
    }
};

// ===== DOM HELPER FUNCTIONS =====
const dom = {
    /**
     * Get element by ID
     */
    id: function(elementId) {
        return document.getElementById(elementId);
    },

    /**
     * Get elements by class name
     */
    class: function(className) {
        return document.getElementsByClassName(className);
    },

    /**
     * Query selector
     */
    query: function(selector) {
        return document.querySelector(selector);
    },

    /**
     * Query selector all
     */
    queryAll: function(selector) {
        return document.querySelectorAll(selector);
    },

    /**
     * Add event listener
     */
    on: function(element, event, handler) {
        if (element) element.addEventListener(event, handler);
    },

    /**
     * Remove event listener
     */
    off: function(element, event, handler) {
        if (element) element.removeEventListener(event, handler);
    },

    /**
     * Show element
     */
    show: function(element) {
        if (element) element.style.display = 'block';
    },

    /**
     * Hide element
     */
    hide: function(element) {
        if (element) element.style.display = 'none';
    },

    /**
     * Toggle element visibility
     */
    toggle: function(element) {
        if (element) {
            element.style.display = element.style.display === 'none' ? 'block' : 'none';
        }
    },

    /**
     * Add class to element
     */
    addClass: function(element, className) {
        if (element) element.classList.add(className);
    },

    /**
     * Remove class from element
     */
    removeClass: function(element, className) {
        if (element) element.classList.remove(className);
    },

    /**
     * Toggle class
     */
    toggleClass: function(element, className) {
        if (element) element.classList.toggle(className);
    },

    /**
     * Check if element has class
     */
    hasClass: function(element, className) {
        return element && element.classList.contains(className);
    }
};

// ===== NOTIFICATION FUNCTIONS =====
const notification = {
    /**
     * Show toast notification
     */
    show: function(message, type = 'info', duration = 3000) {
        // Remove existing notifications
        const existing = document.querySelector('.toast-notification');
        if (existing) existing.remove();

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <span class="toast-message">${message}</span>
            <button class="toast-close">&times;</button>
        `;

        // Add inline styles for toast
        Object.assign(toast.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '16px 24px',
            borderRadius: '8px',
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            gap: '12px',
            animation: 'slideDown 0.3s ease-out',
            fontFamily: 'Poppins, sans-serif',
            fontSize: '14px'
        });

        // Type-specific colors
        const colors = {
            'success': { bg: '#D5F4E6', text: '#27AE60', border: '#27AE60' },
            'error': { bg: '#FADBD8', text: '#E74C3C', border: '#E74C3C' },
            'warning': { bg: '#FEF5E7', text: '#F39C12', border: '#F39C12' },
            'info': { bg: '#D6EAF8', text: '#3498DB', border: '#3498DB' }
        };

        const color = colors[type] || colors['info'];
        toast.style.backgroundColor = color.bg;
        toast.style.color = color.text;
        toast.style.borderLeft = `4px solid ${color.border}`;

        // Add to document
        document.body.appendChild(toast);

        // Close button handler
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.style.cursor = 'pointer';
        closeBtn.style.border = 'none';
        closeBtn.style.background = 'none';
        closeBtn.style.fontSize = '20px';
        closeBtn.style.color = color.text;
        closeBtn.onclick = () => toast.remove();

        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    },

    success: function(message, duration) {
        this.show(message, 'success', duration);
    },

    error: function(message, duration) {
        this.show(message, 'error', duration);
    },

    warning: function(message, duration) {
        this.show(message, 'warning', duration);
    },

    info: function(message, duration) {
        this.show(message, 'info', duration);
    }
};

// ===== LOADING SPINNER =====
const loading = {
    /**
     * Show loading overlay
     */
    show: function(message = 'Loading...') {
        // Remove existing loader
        this.hide();

        const overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.innerHTML = `
            <div class="spinner"></div>
            <p style="margin-top: 16px; color: #fff; font-family: Poppins, sans-serif;">${message}</p>
        `;

        Object.assign(overlay.style, {
            position: 'fixed',
            top: '0',
            left: '0',
            right: '0',
            bottom: '0',
            backgroundColor: 'rgba(0, 0, 0, 0.7)',
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: '99999'
        });

        document.body.appendChild(overlay);
    },

    /**
     * Hide loading overlay
     */
    hide: function() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) overlay.remove();
    }
};

// ===== MODAL HELPER =====
const modal = {
    /**
     * Open modal
     */
    open: function(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            modalElement.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    },

    /**
     * Close modal
     */
    close: function(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            modalElement.classList.remove('active');
            document.body.style.overflow = '';
        }
    },

    /**
     * Initialize modal (click outside to close, ESC key)
     */
    init: function(modalId) {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) return;

        // Click outside to close
        modalElement.addEventListener('click', function(e) {
            if (e.target === modalElement || e.target.classList.contains('modal-backdrop')) {
                modal.close(modalId);
            }
        });

        // ESC key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalElement.classList.contains('active')) {
                modal.close(modalId);
            }
        });

        // Close button
        const closeBtn = modalElement.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.close(modalId);
            });
        }
    }
};

// ===== DEBOUNCE FUNCTION =====
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// ===== IMAGE PREVIEW =====
function previewImage(input, previewElement) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewElement.src = e.target.result;
            previewElement.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ===== CONFIRM DIALOG =====
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Export for use in other scripts
window.utils = {
    ajax,
    validation,
    formatter,
    dom,
    notification,
    loading,
    modal,
    debounce,
    previewImage,
    confirmAction
};
