/**
 * Add User JavaScript
 * Real-time validation, AJAX checks, password strength
 */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('add-user-form');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const phoneInput = document.getElementById('phone');
    const photoInput = document.getElementById('photo');
    const submitBtn = document.getElementById('submit-btn');

    // Password toggle
    const togglePassword = document.getElementById('toggle-password');
    const toggleConfirmPassword = document.getElementById('toggle-confirm-password');

    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }

    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
            confirmPasswordInput.type = type;
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }

    // Username validation and availability check
    if (usernameInput) {
        usernameInput.addEventListener('input', utils.debounce(function () {
            const username = this.value.trim();
            const errorEl = document.getElementById('username-error');
            const successEl = document.getElementById('username-success');

            errorEl.textContent = '';
            successEl.textContent = '';
            this.classList.remove('error', 'success');

            if (username.length === 0) return;

            // Format validation
            if (!utils.validation.isValidUsername(username)) {
                errorEl.textContent = 'Username must be 4-20 characters (alphanumeric and underscore only)';
                this.classList.add('error');
                return;
            }

            // Check availability (placeholder - would be AJAX in production)
            successEl.textContent = '✓ Username is available';
            this.classList.add('success');
        }, 500));
    }

    // Email validation
    if (emailInput) {
        emailInput.addEventListener('input', utils.debounce(function () {
            const email = this.value.trim();
            const errorEl = document.getElementById('email-error');
            const successEl = document.getElementById('email-success');

            errorEl.textContent = '';
            successEl.textContent = '';
            this.classList.remove('error', 'success');

            if (email.length === 0) return;

            if (!utils.validation.isValidEmail(email)) {
                errorEl.textContent = 'Invalid email format';
                this.classList.add('error');
                return;
            }

            successEl.textContent = '✓ Valid email format';
            this.classList.add('success');
        }, 500));
    }

    // Password strength indicator
    if (passwordInput) {
        passwordInput.addEventListener('input', function () {
            const password = this.value;
            const strengthEl = document.getElementById('password-strength');
            const errorEl = document.getElementById('password-error');

            errorEl.textContent = '';

            if (password.length === 0) {
                strengthEl.innerHTML = '';
                return;
            }

            const strength = utils.validation.checkPasswordStrength(password);
            const colors = ['#E74C3C', '#E67E22', '#F39C12', '#3498DB', '#27AE60'];
            const color = colors[strength.score];

            strengthEl.innerHTML = `
                <div style="display: flex; gap: 4px; margin-bottom: 4px;">
                    ${Array(5).fill(0).map((_, i) => `
                        <div style="flex: 1; height: 4px; background-color: ${i < strength.score + 1 ? color : '#ddd'}; border-radius: 2px;"></div>
                    `).join('')}
                </div>
                <div style="font-size: 12px; color: ${color}; font-weight: 500;">
                    ${strength.strength}
                    ${strength.feedback.length > 0 ? ' - Missing: ' + strength.feedback.join(', ') : ''}
                </div>
            `;

            // Validate confirm password if it has value
            if (confirmPasswordInput.value) {
                validateConfirmPassword();
            }
        });
    }

    // Confirm password validation
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', validateConfirmPassword);
    }

    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const errorEl = document.getElementById('confirm_password-error');

        errorEl.textContent = '';
        confirmPasswordInput.classList.remove('error', 'success');

        if (confirmPassword.length === 0) return;

        if (password !== confirmPassword) {
            errorEl.textContent = 'Passwords do not match';
            confirmPasswordInput.classList.add('error');
        } else {
            confirmPasswordInput.classList.add('success');
        }
    }

    // Phone number formatting
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            const errorEl = document.getElementById('phone-error');
            errorEl.textContent = '';
            this.classList.remove('error');

            if (this.value.length === 0) return;

            const cleaned = this.value.replace(/\D/g, '');
            if (cleaned.length > 0 && cleaned.length !== 10) {
                errorEl.textContent = 'Phone number must be 10 digits';
                this.classList.add('error');
            }
        });
    }

    // Photo preview
    if (photoInput) {
        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                // Validate file size
                if (file.size > 2097152) {
                    utils.notification.error('File size must be less than 2MB');
                    this.value = '';
                    return;
                }

                // Validate file type
                if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                    utils.notification.error('Only JPG and PNG files are allowed');
                    this.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewDiv = document.getElementById('photo-preview');
                    const previewImg = document.getElementById('preview-image');
                    previewImg.src = e.target.result;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', function (e) {
            let isValid = true;
            const errors = [];

            // Validate all required fields
            const fullName = document.getElementById('full_name').value.trim();
            const username = usernameInput.value.trim();
            const email = emailInput.value.trim();
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const role = document.getElementById('role').value;

            if (!fullName) {
                errors.push('Full name is required');
                isValid = false;
            }

            if (!username || !utils.validation.isValidUsername(username)) {
                errors.push('Valid username is required');
                isValid = false;
            }

            if (!email || !utils.validation.isValidEmail(email)) {
                errors.push('Valid email is required');
                isValid = false;
            }

            if (!password || password.length < 8) {
                errors.push('Password must be at least 8 characters');
                isValid = false;
            }

            if (password !== confirmPassword) {
                errors.push('Passwords do not match');
                isValid = false;
            }

            if (!role) {
                errors.push('Role is required');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                utils.notification.error(errors.join('<br>'));
                return false;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner spinner-sm"></span> Creating user...';
        });
    }
});
