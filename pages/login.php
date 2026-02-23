<?php
/**
 * LOGIN PAGE
 * User login form with modern cyber UI
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

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid request. Please try again.';
        $messageType = 'error';
    } else {
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            $message = 'Please enter both email and password.';
            $messageType = 'error';
        } else {
            // Attempt authentication
            $user = authenticateUser($email, $password);

            if ($user) {
                // Login successful
                $loginResult = loginUser($email, 'password');

                if ($loginResult) {
                    // Handle "Remember Me"
                    if ($remember) {
                        // Set cookie for 30 days
                        setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                    }

                    // Redirect to intended page or dashboard
                    $redirect = $_SESSION['redirect_after_login'] ?? '../dashboard.php?page=home';
                    unset($_SESSION['redirect_after_login']);
                    header("Location: $redirect");
                    exit();
                } else {
                    $message = 'Login failed. Please try again.';
                    $messageType = 'error';
                }
            } else {
                $message = 'Invalid email or password.';
                $messageType = 'error';
            }
        }
    }
}

$pageTitle = 'Login - Enterprise OS';
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

    .login-container {
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

    .login-card {
        background: var(--glass-bg);
        border: 2px solid var(--glass-border);
        border-radius: 24px;
        padding: 3rem;
        width: 100%;
        max-width: 480px;
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-elevated);
        animation: slideUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary), var(--accent-warning));
        animation: shimmer 3s linear infinite;
    }

    .login-card::after {
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

    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .login-header h1 {
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

    .login-header p {
        color: var(--text-secondary);
        font-size: 1rem;
        font-family: var(--font-mono);
        letter-spacing: 1px;
    }

    .form-group {
        margin-bottom: 2rem;
        position: relative;
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

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--accent-primary);
        cursor: pointer;
    }

    .checkbox-group label {
        margin: 0;
        font-size: 0.875rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .checkbox-group input[type="checkbox"]:checked + label {
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

    .login-links {
        text-align: center;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid var(--glass-border);
    }

    .login-links p {
        margin-bottom: 1rem;
    }

    .login-links a {
        color: var(--accent-primary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .login-links a:hover {
        color: var(--accent-secondary);
        transform: translateX(3px);
    }

    .login-links .secondary-link {
        color: var(--text-secondary);
        font-size: 0.8rem;
        margin-top: 1rem;
        display: block;
    }

    .login-links .secondary-link:hover {
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
        .login-card {
            padding: 2rem;
            margin: 1rem;
        }

        .login-header h1 {
            font-size: 2rem;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>Enterprise OS</h1>
            <p>Secure Login Portal</p>
        </div>

        <!-- Message Display -->
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType === 'error' ? 'danger' : 'success' ?>">
                <i class="fas fa-<?= $messageType === 'error' ? 'exclamation-triangle' : 'check-circle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="" novalidate id="loginForm">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <!-- Email Field -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <div style="position: relative;">
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control"
                           value="<?= htmlspecialchars($_POST['email'] ?? $_COOKIE['remember_email'] ?? '') ?>"
                           placeholder="Enter your email"
                           required
                           autofocus>
                    <i class="fas fa-at input-icon"></i>
                </div>
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div class="password-group" style="position: relative;">
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter your password"
                           required>
                    <i class="fas fa-eye password-toggle" id="togglePassword" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary);"></i>
                </div>
                <div class="invalid-feedback">
                    Please enter your password.
                </div>
            </div>

            <!-- Remember Me -->
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember" <?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>>
                <label for="remember">Remember me for 30 days</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Sign In</span>
            </button>
        </form>

        <!-- Links -->
        <div class="login-links">
            <p>
                <a href="register.php">
                    <i class="fas fa-user-plus"></i>
                    Don't have an account? Register here
                </a>
            </p>
            <a href="../home.php" class="secondary-link">
                <i class="fas fa-arrow-left"></i>
                Return to Homepage
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const emailInput = document.getElementById('email');

    // Password toggle functionality
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // Real-time email validation
    emailInput.addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && !emailRegex.test(email)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
            if (email) this.classList.add('is-valid');
        }
    });

    // Form submission with loading state
    loginForm.addEventListener('submit', function(e) {
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
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<span>Authenticating...</span>';
            loginBtn.disabled = true;
        }

        this.classList.add('was-validated');
    });

    // Add shake animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php require_once '../includes/footer.php'; ?>