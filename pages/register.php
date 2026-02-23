<?php
/**
 * REGISTRATION PAGE
 * User registration form with modern cyber UI
 */

require_once '../includes/config.php';
require_once '../includes/session_handler.php';
require_once '../includes/auth.php';
require_once '../includes/security.php';
require_once '../includes/functions.php';

// Initialize session handling
if (function_exists('initDatabaseSessions')) {
    initDatabaseSessions();
} else {
    session_start();
}

// Redirect to dashboard if already logged in
if (isset($_SESSION['active_user'])) {
    header("Location: ../dashboard.php?page=home");
    exit();
}

$message = '';
$messageType = '';
$formData = [];

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid request. Please try again.';
        $messageType = 'error';
    } else {
        // Sanitize input
        $formData = [
            'username' => sanitizeInput($_POST['username'] ?? ''),
            'email' => sanitizeInput($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'name' => sanitizeInput($_POST['name'] ?? ''),
            'role' => sanitizeInput($_POST['role'] ?? ''),
            'department' => sanitizeInput($_POST['department'] ?? '')
        ];

        // Validate passwords match
        if ($formData['password'] !== $formData['confirm_password']) {
            $message = 'Passwords do not match.';
            $messageType = 'error';
        } else {
            // Attempt registration
            $result = registerUser($formData);

            if ($result['success']) {
                $message = $result['message'];
                $messageType = 'success';
                // Clear form data on success
                $formData = [];
            } else {
                $message = $result['message'];
                $messageType = 'error';
            }
        }
    }
}

$pageTitle = 'Register - Enterprise OS';
require_once '../includes/header.php';
?>

<style>
    :root {
        --bg-primary: #0a0e1a;
        --bg-secondary: #0f1420;
        --bg-tertiary: #141824;
        --glass-bg: rgba(15, 20, 32, 0.85);
        --glass-border: rgba(0, 212, 255, 0.15);
        --accent-primary: #00d4ff;
        --accent-secondary: #00ff88;
        --accent-warning: #ffa500;
        --accent-danger: #ff006e;
        --text-primary: #e0e7ff;
        --text-secondary: #94a3b8;
        --text-muted: #64748b;
        --glow-sm: 0 0 10px rgba(0, 212, 255, 0.3);
        --glow-md: 0 0 20px rgba(0, 212, 255, 0.4);
        --glow-lg: 0 0 30px rgba(0, 212, 255, 0.5);
        --shadow-elevated: 0 10px 40px rgba(0, 0, 0, 0.5);
        --font-display: 'Orbitron', sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
        --font-body: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    body {
        font-family: var(--font-body);
        color: var(--text-primary);
        background: var(--bg-primary);
        overflow-x: hidden;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background:
            radial-gradient(ellipse at top, rgba(0, 212, 255, 0.15), transparent 50%),
            radial-gradient(ellipse at bottom, rgba(255, 0, 110, 0.1), transparent 50%),
            var(--bg-primary);
        position: relative;
    }

    .register-card {
        background: var(--glass-bg);
        border: 2px solid var(--glass-border);
        border-radius: 24px;
        padding: 3rem;
        width: 100%;
        max-width: 600px;
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-elevated);
        animation: slideUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary), var(--accent-warning));
        animation: shimmer 3s linear infinite;
    }

    .register-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg, transparent, rgba(0, 212, 255, 0.1), transparent 60deg);
        animation: rotate 20s linear infinite;
        pointer-events: none;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .register-header {
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .register-header h1 {
        font-family: var(--font-display);
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--accent-primary);
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 0.5rem;
        text-shadow: var(--glow-md);
        background: linear-gradient(45deg, var(--accent-primary), var(--accent-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .register-header p {
        color: var(--text-secondary);
        font-size: 1rem;
        font-family: var(--font-mono);
        letter-spacing: 1px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 2rem;
        position: relative;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-family: var(--font-mono);
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--text-secondary);
        margin-bottom: 0.75rem;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        padding: 1.25rem 1rem;
        background: rgba(15, 20, 32, 0.8);
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        color: var(--text-primary);
        font-family: var(--font-mono);
        font-size: 1rem;
        outline: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.15), var(--glow-sm);
        background: rgba(15, 20, 32, 0.9);
        transform: translateY(-2px);
    }

    .form-control.is-valid {
        border-color: var(--accent-secondary);
    }

    .form-control.is-invalid {
        border-color: var(--accent-danger);
        box-shadow: 0 0 0 4px rgba(255, 0, 110, 0.15);
    }

    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1.1rem;
        transition: color 0.3s ease;
        pointer-events: none;
    }

    .form-control:focus + .input-icon {
        color: var(--accent-primary);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1rem;
        padding-right: 2.5rem;
    }

    .password-group {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1.1rem;
        cursor: pointer;
        transition: color 0.3s ease;
        z-index: 10;
    }

    .password-toggle:hover {
        color: var(--accent-primary);
    }

    .btn {
        padding: 1.25rem 2rem;
        border: none;
        border-radius: 12px;
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
        color: var(--bg-primary);
        box-shadow: var(--glow-sm);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--glow-md);
        background: linear-gradient(135deg, var(--accent-secondary), var(--accent-primary));
    }

    .btn-primary:active {
        transform: translateY(-1px);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn.loading {
        pointer-events: none;
    }

    .register-links {
        text-align: center;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid var(--glass-border);
    }

    .register-links a {
        color: var(--accent-primary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .register-links a:hover {
        color: var(--accent-secondary);
        transform: translateX(3px);
    }

    .register-links .secondary-link {
        color: var(--text-secondary);
        font-size: 0.8rem;
        margin-top: 1rem;
        display: block;
    }

    .register-links .secondary-link:hover {
        color: var(--text-primary);
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 1px solid;
        font-family: var(--font-mono);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: rgba(0, 255, 136, 0.1);
        border-color: var(--accent-secondary);
        color: var(--accent-secondary);
    }

    .alert-danger {
        background: rgba(255, 0, 110, 0.1);
        border-color: var(--accent-danger);
        color: var(--accent-danger);
    }

    .alert i {
        font-size: 1.1rem;
    }

    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        font-family: var(--font-mono);
    }

    .strength-weak { color: var(--accent-danger); }
    .strength-medium { color: var(--accent-warning); }
    .strength-strong { color: var(--accent-secondary); }

    .invalid-feedback {
        color: var(--accent-danger);
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: none;
    }

    .was-validated .form-control:invalid ~ .invalid-feedback {
        display: block;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .register-card {
            padding: 2rem;
            margin: 1rem;
        }

        .register-header h1 {
            font-size: 2rem;
        }
    }
</style>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <h1>Create Account</h1>
            <p>Join the Enterprise OS platform</p>
        </div>

        <!-- Message Display -->
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?>">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="POST" action="" novalidate id="registerForm">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <!-- First Row: Username and Email -->
            <div class="form-row">
                <div class="form-group">
                    <label for="username" class="form-label">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <div style="position: relative;">
                        <input type="text"
                               name="username"
                               id="username"
                               class="form-control"
                               value="<?= htmlspecialchars($formData['username'] ?? '') ?>"
                               placeholder="Choose a username"
                               required
                               pattern="[a-zA-Z0-9_]{3,50}"
                               title="Username must be 3-50 characters, letters, numbers, and underscores only">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    <div class="invalid-feedback">
                        Please enter a valid username (3-50 characters, letters, numbers, underscores only).
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <div style="position: relative;">
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control"
                               value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                               placeholder="Enter your email"
                               required>
                        <i class="fas fa-at input-icon"></i>
                    </div>
                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div>
                </div>
            </div>

            <!-- Second Row: Full Name and Role -->
            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-id-card"></i> Full Name
                    </label>
                    <div style="position: relative;">
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control"
                               value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                               placeholder="Enter your full name"
                               required
                               minlength="2"
                               maxlength="100">
                        <i class="fas fa-signature input-icon"></i>
                    </div>
                    <div class="invalid-feedback">
                        Please enter your full name (2-100 characters).
                    </div>
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">
                        <i class="fas fa-briefcase"></i> Role
                    </label>
                    <select name="role" id="role" class="form-control form-select" required>
                        <option value="">Select your role</option>
                        <option value="Developer" <?= ($formData['role'] ?? '') === 'Developer' ? 'selected' : '' ?>>Developer</option>
                        <option value="Designer" <?= ($formData['role'] ?? '') === 'Designer' ? 'selected' : '' ?>>Designer</option>
                        <option value="Manager" <?= ($formData['role'] ?? '') === 'Manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="Analyst" <?= ($formData['role'] ?? '') === 'Analyst' ? 'selected' : '' ?>>Analyst</option>
                        <option value="Administrator" <?= ($formData['role'] ?? '') === 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                        <option value="Other" <?= ($formData['role'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select your role.
                    </div>
                </div>
            </div>

            <!-- Third Row: Department -->
            <div class="form-group">
                <label for="department" class="form-label">
                    <i class="fas fa-building"></i> Department
                </label>
                <select name="department" id="department" class="form-control form-select" required>
                    <option value="">Select your department</option>
                    <option value="Engineering" <?= ($formData['department'] ?? '') === 'Engineering' ? 'selected' : '' ?>>Engineering</option>
                    <option value="Design" <?= ($formData['department'] ?? '') === 'Design' ? 'selected' : '' ?>>Design</option>
                    <option value="Marketing" <?= ($formData['department'] ?? '') === 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                    <option value="Sales" <?= ($formData['department'] ?? '') === 'Sales' ? 'selected' : '' ?>>Sales</option>
                    <option value="HR" <?= ($formData['department'] ?? '') === 'HR' ? 'selected' : '' ?>>Human Resources</option>
                    <option value="Finance" <?= ($formData['department'] ?? '') === 'Finance' ? 'selected' : '' ?>>Finance</option>
                    <option value="Operations" <?= ($formData['department'] ?? '') === 'Operations' ? 'selected' : '' ?>>Operations</option>
                    <option value="Other" <?= ($formData['department'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
                <div class="invalid-feedback">
                    Please select your department.
                </div>
            </div>

            <!-- Password Fields -->
            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="password-group">
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control"
                               placeholder="Create a password"
                               required
                               minlength="<?= PASSWORD_MIN_LENGTH ?>">
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                    <div class="invalid-feedback">
                        Password must be at least <?= PASSWORD_MIN_LENGTH ?> characters with uppercase, lowercase, and number.
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <div style="position: relative;">
                        <input type="password"
                               name="confirm_password"
                               id="confirm_password"
                               class="form-control"
                               placeholder="Confirm your password"
                               required>
                        <i class="fas fa-check-double input-icon"></i>
                    </div>
                    <div class="invalid-feedback">
                        Passwords do not match.
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="registerBtn">
                <i class="fas fa-user-plus"></i>
                <span>Create Account</span>
            </button>
        </form>

        <!-- Links -->
        <div class="register-links">
            <a href="../dashboard.php?page=login">
                <i class="fas fa-sign-in-alt"></i>
                Already have an account? Sign in here
            </a>
            <a href="../home.php" class="secondary-link">
                <i class="fas fa-arrow-left"></i>
                Return to Homepage
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const togglePassword = document.getElementById('togglePassword');
    const passwordStrength = document.getElementById('passwordStrength');

    // Password toggle functionality
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const minLength = <?= PASSWORD_MIN_LENGTH ?>;
        const hasLength = password.length >= minLength;
        const hasUpper = /[A-Z]/.test(password);
        const hasLower = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);

        let strength = 0;
        let feedback = [];

        if (hasLength) strength++;
        else feedback.push(minLength + '+ characters');

        if (hasUpper) strength++;
        else feedback.push('uppercase letter');

        if (hasLower) strength++;
        else feedback.push('lowercase letter');

        if (hasNumber) strength++;
        else feedback.push('number');

        if (password.length === 0) {
            passwordStrength.textContent = '';
            this.classList.remove('is-valid', 'is-invalid');
        } else if (strength < 4) {
            passwordStrength.textContent = 'Weak: Missing ' + feedback.join(', ');
            passwordStrength.className = 'password-strength strength-weak';
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        } else {
            passwordStrength.textContent = 'Strong password';
            passwordStrength.className = 'password-strength strength-strong';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }

        // Check password match when password changes
        if (confirmPasswordInput.value) {
            checkPasswordMatch();
        }
    });

    // Password confirmation validation
    function checkPasswordMatch() {
        if (confirmPasswordInput.value !== passwordInput.value) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
            confirmPasswordInput.classList.remove('is-valid');
            confirmPasswordInput.classList.add('is-invalid');
        } else {
            confirmPasswordInput.setCustomValidity('');
            confirmPasswordInput.classList.remove('is-invalid');
            if (confirmPasswordInput.value) {
                confirmPasswordInput.classList.add('is-valid');
            }
        }
    }

    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    // Real-time email validation
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && !emailRegex.test(email)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
            if (email) this.classList.add('is-valid');
        }
    });

    // Username validation
    document.getElementById('username').addEventListener('blur', function() {
        const username = this.value;
        const usernameRegex = /^[a-zA-Z0-9_]{3,50}$/;

        if (username && !usernameRegex.test(username)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
            if (username) this.classList.add('is-valid');
        }
    });

    // Form submission with loading state
    registerForm.addEventListener('submit', function(e) {
        const isValid = this.checkValidity();

        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();

            // Add shake animation to invalid fields
            const invalidFields = this.querySelectorAll(':invalid');
            invalidFields.forEach(field => {
                field.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => field.style.animation = '', 500);
            });
        } else {
            // Show loading state
            registerBtn.classList.add('loading');
            registerBtn.innerHTML = '<span>Creating Account...</span>';
            registerBtn.disabled = true;
        }

        this.classList.add('was-validated');
    });

    // Add shake animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);

    // Auto-focus first field
    document.getElementById('username').focus();
});
</script>

<?php require_once '../includes/footer.php'; ?>