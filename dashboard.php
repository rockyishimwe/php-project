<?php
/**
 * ENTERPRISE OS - COMMAND CENTER v2.0
 * A sophisticated enterprise management dashboard with role-based access control
 * 
 * @version 2.0
 */

session_start();

// Include required files
require_once 'includes/config.php';
require_once 'includes/session_handler.php';
require_once 'includes/auth.php';
require_once 'includes/security.php';
require_once 'includes/functions.php';

// Initialize database sessions if not already done
if (session_status() === PHP_SESSION_NONE) {
    initDatabaseSessions();
}

// =======================
// CONFIGURATION & DATA
// =======================

// Enhanced team data with role-based permissions
$users = [
    'admin' => [
        'name' => 'Rocky Gen',
        'role' => 'System Architect',
        'department' => 'Infrastructure',
        'color' => '#00d4ff',
        'ref_img' => 'assets/images/slack.ico',
        'clearance' => 'ALPHA',
        'status' => 'active',
        'is_admin' => true,  // Admin flag
        'permissions' => ['all'] // Full access
    ],
    'marius' => [
        'name' => 'Marius Strategist',
        'role' => 'Chief Financial Officer',
        'department' => 'Finance',
        'color' => '#00ff88',
        'ref_img' => 'assets/images/Beach Scene 2.png',
        'clearance' => 'BETA',
        'status' => 'active',
        'is_admin' => false,
        'permissions' => ['tasks', 'analytics'] // Limited access
    ],
    'forever' => [
        'name' => 'Forever Smart',
        'role' => 'Lead Developer',
        'department' => 'Engineering',
        'color' => '#ffa500',
        'ref_img' => 'assets/images/smooth_skin_portrait.jpg',
        'clearance' => 'BETA',
        'status' => 'active',
        'is_admin' => false,
        'permissions' => ['tasks', 'analytics']
    ],
    'albert' => [
        'name' => 'Albert Einstein',
        'role' => 'R&D Director',
        'department' => 'Research',
        'color' => '#ff006e',
        'ref_img' => 'assets/images/albert.jpg',
        'clearance' => 'BETA',
        'status' => 'active',
        'is_admin' => false,
        'permissions' => ['tasks', 'analytics']
    ]
];

// System metrics simulation
$systemMetrics = [
    'cpu_usage' => rand(45, 75),
    'memory_usage' => rand(50, 80),
    'network_traffic' => rand(60, 90),
    'active_connections' => rand(150, 300),
    'uptime_days' => 47,
    'threat_level' => 'minimal',
    'nodes_online' => 24,
    'nodes_total' => 24
];

// Project data
$projects = [
    [
        'id' => 'QE-001',
        'title' => 'Quantum Encryption Upgrade',
        'progress' => 85,
        'owner' => 'Albert Einstein',
        'priority' => 'critical',
        'due' => '2026-02-28',
        'status' => 'on-track'
    ],
    [
        'id' => 'FA-002',
        'title' => 'Financial Liquidity Audit',
        'progress' => 42,
        'owner' => 'Marius Strategist',
        'priority' => 'high',
        'due' => '2026-03-15',
        'status' => 'in-progress'
    ],
    [
        'id' => 'NI-003',
        'title' => 'Neural Interface Synchronization',
        'progress' => 91,
        'owner' => 'Forever Smart',
        'priority' => 'medium',
        'due' => '2026-02-20',
        'status' => 'on-track'
    ],
    [
        'id' => 'SI-004',
        'title' => 'Security Infrastructure Overhaul',
        'progress' => 67,
        'owner' => 'Rocky Gen',
        'priority' => 'critical',
        'due' => '2026-03-01',
        'status' => 'on-track'
    ]
];

$currentPage = $_GET['page'] ?? 'home';

// Admin-only pages
$adminOnlyPages = ['users', 'settings', 'system', 'logs'];

// =======================
// AUTHENTICATION LOGIC
// =======================

// Handle email/password login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $loginError = 'Invalid request. Please try again.';
    } else {
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];

        // Attempt authentication
        $user = authenticateUser($email, $password);

        if ($user) {
            // Login successful
            $loginResult = loginUser($email, 'password');

            if ($loginResult) {
                // Handle "Remember Me"
                if (isset($_POST['remember']) && $_POST['remember']) {
                    // Set cookie for 30 days
                    setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                }

                // Redirect to intended page or dashboard
                $redirect = $_SESSION['redirect_after_login'] ?? '?page=home';
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit();
            } else {
                $loginError = 'Login failed. Please try again.';
            }
        } else {
            $loginError = 'Invalid email or password.';
        }
    }
}

// Handle biometric authentication (legacy - keeping for backward compatibility)
if (isset($_POST['biometric_user']) && isset($_POST['csrf_token'])) {
    if ($_POST['csrf_token'] === ($_SESSION['csrf_token'] ?? '')) {
        $username = $_POST['biometric_user'];
        if (isset($users[$username])) {
            $_SESSION['active_user'] = $users[$username];
            $_SESSION['user_id'] = $username;
            $_SESSION['login_time'] = time();

            // Initialize or update audit logs
            if (!isset($_SESSION['audit_logs'])) {
                $_SESSION['audit_logs'] = [];
            }

            array_unshift($_SESSION['audit_logs'], [
                'user' => $users[$username]['name'],
                'action' => 'LOGIN',
                'timestamp' => time(),
                'method' => 'Biometric Authentication',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                'status' => 'SUCCESS'
            ]);

            // Limit logs to last 50 entries
            $_SESSION['audit_logs'] = array_slice($_SESSION['audit_logs'], 0, 50);

            header("Location: ?page=home");
            exit();
        }
    }
}

// Session timeout (30 minutes)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 1800)) {
    session_destroy();
    header("Location: ?page=login");
    exit();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['active_user']);
$user = $_SESSION['active_user'] ?? null;

// If not logged in, force login page
if (!$isLoggedIn) {
    $currentPage = 'login';
}

// Admin-only page protection
if ($isLoggedIn && in_array($currentPage, $adminOnlyPages)) {
    if (!isAdmin($_SESSION['active_user'])) {
        header("Location: ?page=home&error=access_denied");
        exit();
    }
}

// Logout handler
if ($currentPage === 'logout') {
    if (isset($_SESSION['active_user'])) {
        array_unshift($_SESSION['audit_logs'], [
            'user' => $_SESSION['active_user']['name'],
            'action' => 'LOGOUT',
            'timestamp' => time(),
            'method' => 'Manual',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'status' => 'SUCCESS'
        ]);
    }
    session_destroy();
    header("Location: ?page=login");
    exit();
}

// Generate CSRF token for login page
if ($currentPage === 'login' && !isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise OS | Command Center</title>
    
    <!-- External Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Orbitron:wght@400;600;800&display=swap');
        
        :root {
            /* Colors */
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
            
            /* Effects */
            --glow-sm: 0 0 10px rgba(0, 212, 255, 0.3);
            --glow-md: 0 0 20px rgba(0, 212, 255, 0.4);
            --glow-lg: 0 0 30px rgba(0, 212, 255, 0.5);
            --shadow-elevated: 0 10px 40px rgba(0, 0, 0, 0.5);
            
            /* Spacing */
            --spacing-xs: 0.5rem;
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --spacing-lg: 2rem;
            --spacing-xl: 3rem;
            
            --font-display: 'Orbitron', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --font-body: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-body);
            color: var(--text-primary);
            background: var(--bg-primary);
            overflow-x: hidden;
            line-height: 1.6;
        }
        
        /* Animated background grid */
        .background-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridPulse 20s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }
        
        @keyframes gridPulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }
        
        /* =======================
           ACCESS DENIED / NOT LOGGED IN STYLES
           ======================= */
        .access-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        
        .access-card {
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-elevated);
            animation: slideUp 0.6s ease-out;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        
        .access-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary), var(--accent-warning));
            animation: shimmer 3s linear infinite;
        }
        
        .access-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 0, 110, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            border: 2px solid var(--accent-danger);
        }
        
        .access-icon i {
            font-size: 3rem;
            color: var(--accent-danger);
        }
        
        .access-card h2 {
            font-family: var(--font-display);
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .access-card p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }
        
        .btn-home-large {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border: none;
            border-radius: 12px;
            color: var(--bg-primary);
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: var(--glow-md);
        }
        
        .btn-home-large:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow-lg);
        }
        
        .btn-home-large i {
            transition: transform 0.3s ease;
        }
        
        .btn-home-large:hover i {
            transform: translateX(-4px);
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
        }
        
        .form-control:focus {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.15), var(--glow-sm);
            background: rgba(15, 20, 32, 0.9);
            transform: translateY(-2px);
        }
        
        .form-control:valid {
            border-color: var(--accent-secondary);
        }
        
        .form-control:invalid:not(:placeholder-shown) {
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
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--glow-md);
            background: linear-gradient(135deg, var(--accent-secondary), var(--accent-primary));
        }
        
        .btn-primary:active {
            transform: translateY(-1px);
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
        }
        
        .login-links .secondary-link:hover {
            color: var(--text-primary);
        }
        
        /* Loading state */
        .btn.loading {
            pointer-events: none;
            position: relative;
        }
        
        .btn.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 0.5rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Error/Success messages */
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
        }
        
        .form-select option {
            background: var(--bg-secondary);
            padding: 0.5rem;
        }
        
        .identity-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(15, 20, 32, 0.4);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            margin-top: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .identity-preview.visible {
            opacity: 1;
        }
        
        .identity-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid var(--accent-primary);
            object-fit: cover;
            box-shadow: var(--glow-sm);
        }
        
        .identity-info h3 {
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .identity-info p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-family: var(--font-mono);
        }
        
        .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: var(--bg-primary);
            box-shadow: var(--glow-md);
        }
        
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--glow-lg);
        }
        
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-primary:active::after {
            width: 300px;
            height: 300px;
        }
        
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--accent-primary);
            border-radius: 8px;
            color: var(--accent-primary);
            font-family: var(--font-mono);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            width: fit-content;
        }
        
        .btn-home:hover {
            background: rgba(0, 212, 255, 0.2);
            transform: translateX(-4px);
            box-shadow: var(--glow-sm);
        }
        
        .btn-home i {
            transition: transform 0.3s ease;
        }
        
        .btn-home:hover i {
            transform: translateX(-4px);
        }
        
        .scanning-indicator {
            display: none;
            text-align: center;
            margin-top: 1rem;
            font-family: var(--font-mono);
            font-size: 0.875rem;
            color: var(--accent-primary);
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        .scanning-indicator.active {
            display: block;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* =======================
           DASHBOARD LAYOUT
           ======================= */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background: var(--bg-primary);
            position: relative;
        }
        
        /* Sidebar Navigation */
        .sidebar {
            width: 280px;
            background: var(--glass-bg);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 10;
        }
        
        .sidebar-header {
            margin-bottom: 3rem;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .sidebar-logo i {
            font-size: 1.5rem;
            color: var(--accent-primary);
            text-shadow: var(--glow-sm);
        }
        
        .sidebar-logo h1 {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: 1px;
        }
        
        .user-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--accent-primary);
            object-fit: cover;
        }
        
        .user-info h3 {
            font-size: 0.875rem;
            color: var(--text-primary);
            margin-bottom: 0.125rem;
        }
        
        .user-info p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-family: var(--font-mono);
        }
        
        .nav-menu {
            flex: 1;
            margin-top: 2rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            position: relative;
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        .nav-link:hover {
            background: rgba(0, 212, 255, 0.1);
            color: var(--accent-primary);
            transform: translateX(4px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(0, 255, 136, 0.1));
            color: var(--accent-primary);
            border-left: 3px solid var(--accent-primary);
            padding-left: calc(1rem - 3px);
        }
        
        .nav-link.logout {
            margin-top: auto;
            color: var(--accent-danger);
            border-top: 1px solid var(--glass-border);
            padding-top: 1.5rem;
        }
        
        .nav-link.logout:hover {
            background: rgba(255, 0, 110, 0.1);
            color: var(--accent-danger);
        }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            position: relative;
            z-index: 1;
        }
        
        .page-header {
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }
        
        .page-title-wrapper {
            flex: 1;
        }
        
        .page-title {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .page-title i {
            color: var(--accent-primary);
            text-shadow: var(--glow-sm);
        }
        
        .page-subtitle {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-family: var(--font-mono);
        }
        
        /* System Monitor Sidebar */
        .system-monitor {
            width: 320px;
            background: var(--glass-bg);
            border-left: 1px solid var(--glass-border);
            padding: 1.5rem;
            overflow-y: auto;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 10;
        }
        
        .monitor-header {
            margin-bottom: 1.5rem;
        }
        
        .monitor-title {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--accent-primary);
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }
        
        .metric-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .metric-card {
            background: rgba(15, 20, 32, 0.6);
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px solid var(--glass-border);
        }
        
        .metric-label {
            font-size: 0.625rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-family: var(--font-mono);
            margin-bottom: 0.25rem;
        }
        
        .metric-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent-primary);
            font-family: var(--font-display);
        }
        
        .metric-bar {
            width: 100%;
            height: 4px;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        
        .metric-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 2px;
            transition: width 0.3s ease;
            box-shadow: var(--glow-sm);
        }
        
        .terminal-output {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            padding: 1rem;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: var(--accent-secondary);
            max-height: 400px;
            overflow-y: auto;
        }
        
        .terminal-line {
            margin-bottom: 0.5rem;
            opacity: 0;
            animation: fadeInTerminal 0.3s forwards;
        }
        
        .terminal-line:before {
            content: '>';
            color: var(--accent-primary);
            margin-right: 0.5rem;
        }
        
        @keyframes fadeInTerminal {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 0.8;
                transform: translateX(0);
            }
        }
        
        /* =======================
           CARDS & COMPONENTS
           ======================= */
        .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            border-color: rgba(0, 212, 255, 0.3);
            box-shadow: 0 8px 24px rgba(0, 212, 255, 0.1);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-title i {
            color: var(--accent-primary);
        }
        
        .grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .grid-4 {
            grid-template-columns: repeat(4, 1fr);
        }
        
        @media (max-width: 1200px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 768px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
        
        /* Stat Cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.05), rgba(0, 255, 136, 0.05));
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--accent-primary), var(--accent-secondary));
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .stat-icon i {
            font-size: 1.5rem;
            color: var(--accent-primary);
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            font-family: var(--font-display);
            color: var(--text-primary);
        }
        
        .stat-change {
            font-size: 0.75rem;
            color: var(--accent-secondary);
            margin-top: 0.5rem;
            font-family: var(--font-mono);
        }
        
        .stat-change.negative {
            color: var(--accent-danger);
        }
        
        /* Project Cards */
        .project-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .project-card:hover {
            transform: translateY(-4px);
            border-color: var(--accent-primary);
            box-shadow: 0 12px 24px rgba(0, 212, 255, 0.15);
        }
        
        .project-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .project-id {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: var(--accent-primary);
            background: rgba(0, 212, 255, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        
        .project-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .project-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
        
        .project-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
            border-radius: 4px;
            box-shadow: var(--glow-sm);
            transition: width 0.6s ease;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            font-family: var(--font-mono);
        }
        
        .progress-label .percent {
            color: var(--accent-primary);
            font-weight: 600;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            font-family: var(--font-mono);
        }
        
        .badge-critical {
            background: rgba(255, 0, 110, 0.2);
            color: var(--accent-danger);
            border: 1px solid var(--accent-danger);
        }
        
        .badge-high {
            background: rgba(255, 165, 0, 0.2);
            color: var(--accent-warning);
            border: 1px solid var(--accent-warning);
        }
        
        .badge-medium {
            background: rgba(0, 212, 255, 0.2);
            color: var(--accent-primary);
            border: 1px solid var(--accent-primary);
        }
        
        .badge-success {
            background: rgba(0, 255, 136, 0.2);
            color: var(--accent-secondary);
            border: 1px solid var(--accent-secondary);
        }
        
        /* Audit Log Table */
        .audit-table {
            width: 100%;
            border-collapse: collapse;
            font-family: var(--font-mono);
            font-size: 0.875rem;
        }
        
        .audit-table thead {
            background: rgba(0, 212, 255, 0.05);
            border-bottom: 2px solid var(--glass-border);
        }
        
        .audit-table th {
            text-align: left;
            padding: 1rem;
            font-weight: 600;
            color: var(--accent-primary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }
        
        .audit-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--glass-border);
            color: var(--text-secondary);
        }
        
        .audit-table tr:hover {
            background: rgba(0, 212, 255, 0.05);
        }
        
        .audit-table .timestamp {
            color: var(--accent-secondary);
        }
        
        .audit-table .action {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
            animation: pulseStatus 2s ease-in-out infinite;
        }
        
        .status-success {
            background: var(--accent-secondary);
            box-shadow: 0 0 10px var(--accent-secondary);
        }
        
        .status-warning {
            background: var(--accent-warning);
            box-shadow: 0 0 10px var(--accent-warning);
        }
        
        .status-error {
            background: var(--accent-danger);
            box-shadow: 0 0 10px var(--accent-danger);
        }
        
        @keyframes pulseStatus {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 1rem;
        }
        
        /* Status Panel */
        .status-panel {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(0, 255, 136, 0.05);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 8px;
        }
        
        .status-panel.warning {
            background: rgba(255, 165, 0, 0.05);
            border-color: rgba(255, 165, 0, 0.2);
        }
        
        .status-panel.error {
            background: rgba(255, 0, 110, 0.05);
            border-color: rgba(255, 0, 110, 0.2);
        }
        
        /* =======================
           RESPONSIVE DESIGN
           ======================= */
        @media (max-width: 1024px) {
            .system-monitor {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--glass-border);
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
            }
        }
        
        /* =======================
           SCROLLBAR STYLING
           ======================= */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(0, 212, 255, 0.3);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 212, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="background-grid"></div>
    
    <?php if ($currentPage === 'login'): ?>
        <!-- Login Page -->
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <h1>Enterprise OS</h1>
                    <p>Secure Login Portal</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="" novalidate id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email Address
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="Enter your email"
                               required
                               autofocus>
                        <i class="fas fa-at input-icon"></i>
                        <div class="invalid-feedback">
                            Please enter a valid email address.
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Password
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control"
                               placeholder="Enter your password"
                               required>
                        <i class="fas fa-eye input-icon" id="togglePassword" style="cursor: pointer;"></i>
                        <div class="invalid-feedback">
                            Please enter your password.
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
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
                        <a href="../pages/register.php">
                            <i class="fas fa-user-plus"></i>
                            Don't have an account? Register here
                        </a>
                    </p>
                    <a href="../" class="secondary-link">
                        <i class="fas fa-arrow-left"></i>
                        Return to Homepage
                    </a>
                </div>
            </div>
        </div>

        <script>
            // Enhanced form validation and interactions
            document.addEventListener('DOMContentLoaded', function() {
                const loginForm = document.getElementById('loginForm');
                const loginBtn = document.getElementById('loginBtn');
                const passwordInput = document.getElementById('password');
                const togglePassword = document.getElementById('togglePassword');
                const emailInput = document.getElementById('email');

                // Password toggle functionality
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });

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

                // Password strength indicator
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    const hasLength = password.length >= 8;
                    const hasUpper = /[A-Z]/.test(password);
                    const hasLower = /[a-z]/.test(password);
                    const hasNumber = /\d/.test(password);

                    if (password && hasLength && hasUpper && hasLower && hasNumber) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (password) {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
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

                // Auto-focus and smooth scrolling
                if (window.location.hash === '#login') {
                    emailInput.focus();
                    emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        </script>
        
    <?php elseif (!$isLoggedIn): ?>
        <!-- Not Logged In - Show Access Denied with Return to Homepage -->
        <div class="access-container">
            <div class="access-card">
                <div class="access-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>Access Denied</h2>
                <p>You need to be logged in to access the dashboard. Please log in first or return to the homepage.</p>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <a href="?page=login" class="btn-home-large">
                        <i class="fas fa-fingerprint"></i>
                        Go to Login
                    </a>
                    <a href="home.php" class="btn-home" style="margin: 0 auto; width: fit-content;">
                        <i class="fas fa-arrow-left"></i>
                        Return to Homepage
                    </a>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Logged In - Show Dashboard -->
        <div class="dashboard-container">
            <!-- Sidebar Navigation -->
            <aside class="sidebar">
                <div class="sidebar-header">
                    <div class="sidebar-logo">
                        <i class="fas fa-cube"></i>
                        <h1>Rocky Linux</h1>
                    </div>
                    
                    <div class="user-badge">
                        <img src="<?= $user['ref_img'] ?>" alt="<?= $user['name'] ?>" class="user-avatar">
                        <div class="user-info">
                            <h3>
                                <?= $user['name'] ?>
                                <?php if (isAdmin($user)): ?>
                                    <i class="fas fa-crown" style="color: #ffa500; font-size: 0.75rem; margin-left: 0.25rem;" title="Administrator"></i>
                                <?php endif; ?>
                            </h3>
                            <p><?= $user['role'] ?></p>
                        </div>
                    </div>
                </div>
                
                <nav class="nav-menu">
                    <a href="?page=home" class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>">
                        <i class="fas fa-chart-line"></i>
                        <span><?= isAdmin($user) ? 'Admin Dashboard' : 'My Dashboard' ?></span>
                    </a>
                    <a href="?page=tasks" class="nav-link <?= $currentPage === 'tasks' ? 'active' : '' ?>">
                        <i class="fas fa-tasks"></i>
                        <span><?= isAdmin($user) ? 'All Projects' : 'My Projects' ?></span>
                    </a>
                    
                    <?php if (isAdmin($user)): ?>
                        <!-- Admin-only menu items -->
                        <a href="?page=users" class="nav-link <?= $currentPage === 'users' ? 'active' : '' ?>">
                            <i class="fas fa-users-cog"></i>
                            <span>User Management</span>
                        </a>
                        <a href="?page=logs" class="nav-link <?= $currentPage === 'logs' ? 'active' : '' ?>">
                            <i class="fas fa-shield-alt"></i>
                            <span>Security Logs</span>
                        </a>
                        <a href="?page=system" class="nav-link <?= $currentPage === 'system' ? 'active' : '' ?>">
                            <i class="fas fa-cog"></i>
                            <span>System Settings</span>
                        </a>
                    <?php endif; ?>
                    
                    <a href="?page=analytics" class="nav-link <?= $currentPage === 'analytics' ? 'active' : '' ?>">
                        <i class="fas fa-chart-pie"></i>
                        <span>Analytics</span>
                    </a>
                    
                    <a href="?page=logout" class="nav-link logout">
                        <i class="fas fa-power-off"></i>
                        <span>System Shutdown</span>
                    </a>
                </nav>
            </aside>
            
            <!-- Main Content Area -->
            <main class="main-content">
                <!-- Return to Homepage Button - Added to every page except home -->
                <?php if ($currentPage !== 'home'): ?>
                <a href="home.php" class="btn-home">
                    <i class="fas fa-arrow-left"></i>
                    Return to Homepage
                </a>
                <?php endif; ?>
                
                <?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied'): ?>
                    <div style="background: rgba(255, 0, 110, 0.1); border: 2px solid var(--accent-danger); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: var(--accent-danger);"></i>
                        <div>
                            <strong style="color: var(--accent-danger); font-size: 1.125rem;">Access Denied</strong>
                            <p style="color: var(--text-secondary); margin-top: 0.25rem;">
                                You attempted to access an administrator-only page. This action has been logged.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if($currentPage === 'users' && isAdmin($user)): ?>
                    <!-- USER MANAGEMENT PAGE (Admin Only) -->
                    <div class="page-header">
                        <div class="page-title-wrapper">
                            <h1 class="page-title">
                                <i class="fas fa-users-cog"></i>
                                User Management
                            </h1>
                            <p class="page-subtitle">Manage team members, roles, and permissions</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-2" style="margin-bottom: 2rem;">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-label">Total Users</div>
                            <div class="stat-value"><?= count($users) ?></div>
                            <div class="stat-change">
                                <i class="fas fa-check"></i> All Active
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="stat-label">Admin Users</div>
                            <div class="stat-value">1</div>
                            <div class="stat-change">
                                <i class="fas fa-lock"></i> Secure
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-id-card"></i>
                                Team Directory
                            </h2>
                        </div>
                        
                        <table class="audit-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Clearance</th>
                                    <th>Status</th>
                                    <th>Admin</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $userId => $userData): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <img src="<?= $userData['ref_img'] ?>" 
                                                     style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid <?= $userData['color'] ?>;">
                                                <strong><?= $userData['name'] ?></strong>
                                            </div>
                                        </td>
                                        <td><?= $userData['role'] ?></td>
                                        <td><?= $userData['department'] ?></td>
                                        <td>
                                            <span class="badge badge-<?= $userData['clearance'] === 'ALPHA' ? 'critical' : 'medium' ?>">
                                                <?= $userData['clearance'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-indicator status-success"></span>
                                            <?= ucfirst($userData['status']) ?>
                                        </td>
                                        <td>
                                            <?php if(isset($userData['is_admin']) && $userData['is_admin']): ?>
                                                <i class="fas fa-crown" style="color: #ffa500;"></i> Yes
                                            <?php else: ?>
                                                <span style="color: var(--text-muted);">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.75rem; width: auto;">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                <?php elseif($currentPage === 'system' && isAdmin($user)): ?>
                    <!-- SYSTEM SETTINGS PAGE (Admin Only) -->
                    <div class="page-header">
                        <div class="page-title-wrapper">
                            <h1 class="page-title">
                                <i class="fas fa-cog"></i>
                                System Settings
                            </h1>
                            <p class="page-subtitle">Configure system parameters and security settings</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-3" style="margin-bottom: 2rem;">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="stat-label">Database Size</div>
                            <div class="stat-value">2.4 GB</div>
                            <div class="stat-change">
                                <i class="fas fa-arrow-up"></i> +120 MB this month
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-server"></i>
                            </div>
                            <div class="stat-label">Server Uptime</div>
                            <div class="stat-value"><?= $systemMetrics['uptime_days'] ?>d</div>
                            <div class="stat-change">
                                <i class="fas fa-check"></i> 99.9% reliability
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="stat-label">Security Level</div>
                            <div class="stat-value">High</div>
                            <div class="stat-change status-success">
                                <i class="fas fa-lock"></i> Encrypted
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-2">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <i class="fas fa-shield-virus"></i>
                                    Security Configuration
                                </h2>
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 212, 255, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>Two-Factor Authentication</strong>
                                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Require 2FA for all users</p>
                                    </div>
                                    <div class="badge badge-success">ENABLED</div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 212, 255, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>Session Timeout</strong>
                                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Auto-logout after inactivity</p>
                                    </div>
                                    <div class="badge badge-success">30 MIN</div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 212, 255, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>IP Whitelisting</strong>
                                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Restrict access by IP address</p>
                                    </div>
                                    <div class="badge badge-medium">DISABLED</div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 212, 255, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>Encryption Standard</strong>
                                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Data encryption protocol</p>
                                    </div>
                                    <div class="badge badge-success">AES-256</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <i class="fas fa-bell"></i>
                                    Notification Settings
                                </h2>
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 255, 136, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>Failed Login Alerts</strong>
                                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Alert on suspicious activity</p>
                                    </div>
                                    <div class="badge badge-success">ON</div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 255, 136, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>System Health Reports</strong>
                                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Daily performance summaries</p>
                                    </div>
                                    <div class="badge badge-success">ON</div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 255, 136, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>Project Deadline Alerts</strong>
                                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Notify 48h before due date</p>
                                    </div>
                                    <div class="badge badge-success">ON</div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 255, 136, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>Email Notifications</strong>
                                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">Send email summaries</p>
                                    </div>
                                    <div class="badge badge-medium">OFF</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php elseif($currentPage === 'tasks'): ?>
                    <!-- Project Management Page -->
                    <div class="page-header">
                        <div class="page-title-wrapper">
                            <h1 class="page-title">
                                <i class="fas fa-tasks"></i>
                                <?= isAdmin($user) ? 'All Projects' : 'My Projects' ?>
                            </h1>
                            <p class="page-subtitle">
                                <?= isAdmin($user) ? 'Complete project portfolio and task tracking' : 'Your assigned projects and tasks' ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php if (isAdmin($user)): ?>
                        <!-- Admin view: Show statistics -->
                        <div class="grid grid-4" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                <div class="stat-label">Total Projects</div>
                                <div class="stat-value"><?= count($projects) ?></div>
                                <div class="stat-change">
                                    <i class="fas fa-arrow-up"></i> 2 new
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div class="stat-label">In Progress</div>
                                <div class="stat-value"><?= count(array_filter($projects, fn($p) => $p['status'] === 'in-progress')) ?></div>
                                <div class="stat-change">Active</div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-label">On Track</div>
                                <div class="stat-value"><?= count(array_filter($projects, fn($p) => $p['status'] === 'on-track')) ?></div>
                                <div class="stat-change status-success">
                                    <i class="fas fa-check"></i> Good
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stat-label">Critical Priority</div>
                                <div class="stat-value"><?= count(array_filter($projects, fn($p) => $p['priority'] === 'critical')) ?></div>
                                <div class="stat-change">Needs attention</div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="grid">
                        <?php 
                        // Filter projects for regular users
                        $displayProjects = $projects;
                        if (!isAdmin($user)) {
                            // Show only projects assigned to the user
                            $displayProjects = array_filter($projects, function($project) use ($user) {
                                return $project['owner'] === $user['name'];
                            });
                        }
                        
                        foreach($displayProjects as $project): 
                        ?>
                            <div class="project-card">
                                <div class="project-header">
                                    <div>
                                        <span class="project-id"><?= $project['id'] ?></span>
                                        <h3 class="project-title"><?= $project['title'] ?></h3>
                                    </div>
                                    <span class="badge badge-<?= $project['priority'] ?>">
                                        <?= strtoupper($project['priority']) ?>
                                    </span>
                                </div>
                                
                                <div class="project-meta">
                                    <span><i class="fas fa-user"></i> <?= $project['owner'] ?></span>
                                    <span><i class="fas fa-calendar"></i> Due: <?= date('M d', strtotime($project['due'])) ?></span>
                                </div>
                                
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= $project['progress'] ?>%"></div>
                                </div>
                                <div class="progress-label">
                                    <span><?= $project['status'] ?></span>
                                    <span class="percent"><?= $project['progress'] ?>%</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (!isAdmin($user) && count($displayProjects) === 0): ?>
                            <div class="card" style="text-align: center; padding: 3rem;">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                                <h3>No Projects Assigned</h3>
                                <p style="color: var(--text-secondary);">You don't have any projects assigned at the moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                <?php elseif($currentPage === 'logs'): ?>
                    <?php if (isAdmin($user)): ?>
                        <!-- Audit Trail Page (Admin Only) -->
                        <div class="page-header">
                            <div class="page-title-wrapper">
                                <h1 class="page-title">
                                    <i class="fas fa-shield-alt"></i>
                                    Security Audit Trail
                                </h1>
                                <p class="page-subtitle">System access logs and authentication events</p>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <i class="fas fa-history"></i>
                                    Recent Activity
                                </h2>
                            </div>
                            
                            <table class="audit-table">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Method</th>
                                        <th>IP Address</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $logs = $_SESSION['audit_logs'] ?? [];
                                    foreach($logs as $log): 
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="status-indicator status-<?= strtolower($log['status']) === 'success' ? 'success' : 'error' ?>"></span>
                                            </td>
                                            <td><?= $log['user'] ?></td>
                                            <td class="action"><?= $log['action'] ?></td>
                                            <td><?= $log['method'] ?></td>
                                            <td><?= $log['ip'] ?></td>
                                            <td class="timestamp"><?= date('Y-m-d H:i:s', $log['timestamp']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    
                                    <?php if(empty($logs)): ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                                No audit logs available
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Access Denied -->
                        <div class="card" style="text-align: center; padding: 4rem;">
                            <i class="fas fa-lock" style="font-size: 4rem; color: var(--accent-danger); margin-bottom: 1rem;"></i>
                            <h2>Access Denied</h2>
                            <p style="color: var(--text-secondary); margin-top: 1rem;">
                                You do not have permission to view this page.<br>
                                This area is restricted to administrators only.
                            </p>
                        </div>
                    <?php endif; ?>
                    
                <?php elseif($currentPage === 'analytics'): ?>
                    <!-- Analytics Page -->
                    <div class="page-header">
                        <div class="page-title-wrapper">
                            <h1 class="page-title">
                                <i class="fas fa-chart-pie"></i>
                                Analytics Dashboard
                            </h1>
                            <p class="page-subtitle">Performance metrics and system insights</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-4" style="margin-bottom: 2rem;">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <div class="stat-label">Active Projects</div>
                            <div class="stat-value"><?= count($projects) ?></div>
                            <div class="stat-change">
                                <i class="fas fa-arrow-up"></i> 2 new this month
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-label">Completion Rate</div>
                            <div class="stat-value">73%</div>
                            <div class="stat-change">
                                <i class="fas fa-arrow-up"></i> +12% from last quarter
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-label">Team Members</div>
                            <div class="stat-value"><?= count($users) ?></div>
                            <div class="stat-change">
                                <i class="fas fa-minus"></i> No change
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-label">Avg. Response Time</div>
                            <div class="stat-value">2.3h</div>
                            <div class="stat-change">
                                <i class="fas fa-arrow-down"></i> -30min improvement
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-chart-bar"></i>
                                Project Progress Overview
                            </h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="analyticsChart"></canvas>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Operations Center (Home) -->
                    <div class="page-header">
                        <div class="page-title-wrapper">
                            <h1 class="page-title">
                                <i class="fas fa-chart-line"></i>
                                <?= isAdmin($user) ? 'Admin Command Center' : 'My Dashboard' ?>
                            </h1>
                            <p class="page-subtitle">
                                <?= isAdmin($user) ? 'Complete system overview and global metrics' : 'Your personal metrics and task overview' ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php if (isAdmin($user)): ?>
                        <!-- ADMIN DASHBOARD -->
                        <!-- Status Panel -->
                        <div class="status-panel" style="margin-bottom: 2rem;">
                            <span class="status-indicator status-success"></span>
                            <div>
                                <strong>All Systems Operational</strong><br>
                                <small>Last checked: <?= date('H:i:s') ?> | Uptime: <?= $systemMetrics['uptime_days'] ?> days | Admin Access Level: ALPHA</small>
                            </div>
                        </div>
                        
                        <!-- Key Metrics Grid -->
                        <div class="grid grid-4" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-server"></i>
                                </div>
                                <div class="stat-label">Server Health</div>
                                <div class="stat-value">98%</div>
                                <div class="stat-change">
                                    <i class="fas fa-check"></i> Optimal
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-network-wired"></i>
                                </div>
                                <div class="stat-label">Network Traffic</div>
                                <div class="stat-value"><?= $systemMetrics['network_traffic'] ?>%</div>
                                <div class="stat-change">
                                    <i class="fas fa-arrow-up"></i> +5% from baseline
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-shield-virus"></i>
                                </div>
                                <div class="stat-label">Threat Level</div>
                                <div class="stat-value">Low</div>
                                <div class="stat-change status-success">
                                    <i class="fas fa-check-circle"></i> Minimal
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="stat-label">Global Nodes</div>
                                <div class="stat-value"><?= $systemMetrics['nodes_online'] ?>/<?= $systemMetrics['nodes_total'] ?></div>
                                <div class="stat-change">
                                    <i class="fas fa-check"></i> All Online
                                </div>
                            </div>
                        </div>
                        
                        <!-- Admin Stats -->
                        <div class="grid grid-3" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-label">Active Users</div>
                                <div class="stat-value"><?= count($users) ?></div>
                                <div class="stat-change">
                                    <i class="fas fa-check"></i> All logged in today
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <div class="stat-label">Total Projects</div>
                                <div class="stat-value"><?= count($projects) ?></div>
                                <div class="stat-change">
                                    <i class="fas fa-arrow-up"></i> 2 added this month
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-label">System Load</div>
                                <div class="stat-value"><?= $systemMetrics['cpu_usage'] ?>%</div>
                                <div class="stat-change">
                                    <i class="fas fa-check"></i> Normal
                                </div>
                            </div>
                        </div>
                        
                        <!-- Charts Section -->
                        <div class="grid grid-2">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title">
                                        <i class="fas fa-chart-area"></i>
                                        System Performance
                                    </h2>
                                </div>
                                <div class="chart-container">
                                    <canvas id="performanceChart"></canvas>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title">
                                        <i class="fas fa-tasks"></i>
                                        Project Distribution
                                    </h2>
                                </div>
                                <div class="chart-container">
                                    <canvas id="projectChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <!-- REGULAR USER DASHBOARD -->
                        <!-- Welcome Message -->
                        <div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 255, 136, 0.1));">
                            <h2>Welcome back, <?= explode(' ', $user['name'])[0] ?>!</h2>
                            <p style="color: var(--text-secondary); margin-top: 0.5rem;">
                                Here's an overview of your current activities and tasks.
                            </p>
                        </div>
                        
                        <!-- User Stats -->
                        <div class="grid grid-3" style="margin-bottom: 2rem;">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <div class="stat-label">My Active Projects</div>
                                <div class="stat-value">
                                    <?php 
                                    $userProjects = array_filter($projects, fn($p) => $p['owner'] === $user['name']);
                                    echo count($userProjects);
                                    ?>
                                </div>
                                <div class="stat-change">
                                    <?php 
                                    $userInProgress = array_filter($userProjects, fn($p) => $p['progress'] < 100);
                                    echo count($userInProgress);
                                    ?> in progress
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="stat-label">Avg. Progress</div>
                                <div class="stat-value">
                                    <?php 
                                    if (count($userProjects) > 0) {
                                        $avgProgress = array_sum(array_column($userProjects, 'progress')) / count($userProjects);
                                        echo round($avgProgress) . '%';
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </div>
                                <div class="stat-change">
                                    <i class="fas fa-arrow-up"></i> Good pace
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-label">Upcoming Deadlines</div>
                                <div class="stat-value">
                                    <?php 
                                    $upcomingDeadlines = array_filter($userProjects, function($p) {
                                        $daysUntil = (strtotime($p['due']) - time()) / 86400;
                                        return $daysUntil <= 7 && $daysUntil > 0;
                                    });
                                    echo count($upcomingDeadlines);
                                    ?>
                                </div>
                                <div class="stat-change">
                                    This week
                                </div>
                            </div>
                        </div>
                        
                        <!-- My Recent Projects -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <i class="fas fa-briefcase"></i>
                                    My Recent Projects
                                </h2>
                            </div>
                            
                            <?php if (count($userProjects) > 0): ?>
                                <div style="display: grid; gap: 1rem;">
                                    <?php foreach(array_slice($userProjects, 0, 3) as $project): ?>
                                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(0, 212, 255, 0.05); border-radius: 8px; border-left: 3px solid <?= $project['priority'] === 'critical' ? 'var(--accent-danger)' : 'var(--accent-primary)' ?>;">
                                            <div style="flex: 1;">
                                                <strong><?= $project['title'] ?></strong>
                                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                                    <i class="fas fa-calendar"></i> Due: <?= date('M d, Y', strtotime($project['due'])) ?>
                                                </div>
                                            </div>
                                            <div style="width: 150px; margin: 0 1rem;">
                                                <div class="progress-bar" style="margin: 0;">
                                                    <div class="progress-fill" style="width: <?= $project['progress'] ?>%"></div>
                                                </div>
                                            </div>
                                            <div style="text-align: right;">
                                                <div style="font-size: 1.25rem; font-weight: 700; color: var(--accent-primary);">
                                                    <?= $project['progress'] ?>%
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                    <p>No projects assigned yet</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="grid grid-2" style="margin-top: 2rem;">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title">
                                        <i class="fas fa-info-circle"></i>
                                        Your Information
                                    </h2>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Department:</span>
                                        <strong><?= $user['department'] ?></strong>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Clearance Level:</span>
                                        <span class="badge badge-<?= $user['clearance'] === 'ALPHA' ? 'critical' : 'medium' ?>">
                                            <?= $user['clearance'] ?>
                                        </span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: var(--text-secondary);">Status:</span>
                                        <span style="color: var(--accent-secondary);">
                                            <span class="status-indicator status-success"></span>
                                            <?= ucfirst($user['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title">
                                        <i class="fas fa-bell"></i>
                                        Quick Links
                                    </h2>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    <a href="?page=tasks" style="padding: 0.75rem; background: rgba(0, 212, 255, 0.1); border-radius: 6px; text-decoration: none; color: var(--accent-primary); display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-arrow-right"></i>
                                        View All My Projects
                                    </a>
                                    <a href="?page=analytics" style="padding: 0.75rem; background: rgba(0, 212, 255, 0.1); border-radius: 6px; text-decoration: none; color: var(--accent-primary); display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="fas fa-arrow-right"></i>
                                        View Analytics
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </main>
            
            <!-- System Monitor Sidebar -->
            <aside class="system-monitor">
                <div class="monitor-header">
                    <h3 class="monitor-title">
                        <i class="fas fa-microchip"></i> System Monitor
                    </h3>
                </div>
                
                
                <?php if (isAdmin($user)): ?>
                   
                    <div class="metric-grid">
                        <div class="metric-card">
                            <div class="metric-label">CPU Usage</div>
                            <div class="metric-value"><?= $systemMetrics['cpu_usage'] ?>%</div>
                            <div class="metric-bar">
                                <div class="metric-bar-fill" style="width: <?= $systemMetrics['cpu_usage'] ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-label">Memory</div>
                            <div class="metric-value"><?= $systemMetrics['memory_usage'] ?>%</div>
                            <div class="metric-bar">
                                <div class="metric-bar-fill" style="width: <?= $systemMetrics['memory_usage'] ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-label">Network</div>
                            <div class="metric-value"><?= $systemMetrics['network_traffic'] ?>%</div>
                            <div class="metric-bar">
                                <div class="metric-bar-fill" style="width: <?= $systemMetrics['network_traffic'] ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-label">Connections</div>
                            <div class="metric-value"><?= $systemMetrics['active_connections'] ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    
                    <div class="card" style="margin-bottom: 1rem; padding: 1rem;">
                        <div style="text-align: center;">
                            <i class="fas fa-user-circle" style="font-size: 3rem; color: var(--accent-primary); margin-bottom: 0.5rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;"><?= $user['name'] ?></h4>
                            <p style="font-size: 0.75rem; color: var(--text-secondary);"><?= $user['department'] ?></p>
                            <div class="badge badge-<?= $user['clearance'] === 'ALPHA' ? 'critical' : 'medium' ?>" style="margin-top: 0.5rem;">
                                <?= $user['clearance'] ?> ACCESS
                            </div>
                        </div>
                    </div>
                    
                    <div style="padding: 1rem; background: rgba(0, 212, 255, 0.05); border-radius: 8px; margin-bottom: 1rem;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Your Projects</div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent-primary);">
                            <?php 
                            $userProjects = array_filter($projects, fn($p) => $p['owner'] === $user['name']);
                            echo count($userProjects);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                
                <h3 class="monitor-title" style="margin-top: 1.5rem;">
                    <i class="fas fa-terminal"></i> <?= isAdmin($user) ? 'System Feed' : 'Activity Feed' ?>
                </h3>
                <div class="terminal-output" id="terminalOutput">
                    <?php if (isAdmin($user)): ?>
                        <div class="terminal-line">System initialized...</div>
                        <div class="terminal-line">Secure tunnel established</div>
                        <div class="terminal-line">Admin access granted</div>
                        <div class="terminal-line">Monitoring all nodes</div>
                    <?php else: ?>
                        <div class="terminal-line">User session active</div>
                        <div class="terminal-line">Projects synced</div>
                        <div class="terminal-line">Dashboard loaded</div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
        
        
        <script>
            
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.08)';
            Chart.defaults.font.family = "'JetBrains Mono', monospace";
            
            
            const performanceCtx = document.getElementById('performanceChart');
            if (performanceCtx) {
                new Chart(performanceCtx, {
                    type: 'line',
                    data: {
                        labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                        datasets: [{
                            label: 'CPU',
                            data: [45, 52, 48, 65, 59, <?= $systemMetrics['cpu_usage'] ?>],
                            borderColor: '#00d4ff',
                            backgroundColor: 'rgba(0, 212, 255, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Memory',
                            data: [60, 58, 55, 70, 68, <?= $systemMetrics['memory_usage'] ?>],
                            borderColor: '#00ff88',
                            backgroundColor: 'rgba(0, 255, 136, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
            
            
            const projectCtx = document.getElementById('projectChart');
            if (projectCtx) {
                new Chart(projectCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['On Track', 'In Progress', 'At Risk', 'Completed'],
                        datasets: [{
                            data: [45, 30, 15, 10],
                            backgroundColor: [
                                '#00ff88',
                                '#00d4ff',
                                '#ffa500',
                                '#ff006e'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
            }
            
           
            const analyticsCtx = document.getElementById('analyticsChart');
            if (analyticsCtx) {
                new Chart(analyticsCtx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_column($projects, 'title')) ?>,
                        datasets: [{
                            label: 'Progress (%)',
                            data: <?= json_encode(array_column($projects, 'progress')) ?>,
                            backgroundColor: [
                                'rgba(0, 212, 255, 0.6)',
                                'rgba(0, 255, 136, 0.6)',
                                'rgba(255, 165, 0, 0.6)',
                                'rgba(255, 0, 110, 0.6)'
                            ],
                            borderColor: [
                                '#00d4ff',
                                '#00ff88',
                                '#ffa500',
                                '#ff006e'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
            
            const terminal = document.getElementById('terminalOutput');
            if (terminal) {
                <?php if (isAdmin($user)): ?>
                
                const messages = [
                    'neza niel na robert',
                    'Encrypting data stream...',
                    'Admin action logged',
                    'User session validated',
                    'Syncing with backup server',
                    'Firewall status: ACTIVE',
                    'Database query executed',
                    'Cache updated successfully',
                    'System health check: OK',
                    'Security scan complete',
                    'All nodes responding',
                    'Backup completed successfully'
                ];
                <?php else: ?>
                
                const messages = [
                    'Project data synced',
                    'Task update received',
                    'Dashboard refreshed',
                    'Activity logged',
                    'Session active',
                    'Data saved successfully'
                ];
                <?php endif; ?>
                
                setInterval(() => {
                    const line = document.createElement('div');
                    line.className = 'terminal-line';
                    line.textContent = messages[Math.floor(Math.random() * messages.length)];
                    
                    terminal.insertBefore(line, terminal.firstChild);
                    
                    
                    while (terminal.children.length > 15) {
                        terminal.removeChild(terminal.lastChild);
                    }
                }, 3000);
            }
            
            
            let sessionTimeout;
            function resetSessionTimeout() {
                clearTimeout(sessionTimeout);
                sessionTimeout = setTimeout(() => {
                    alert('Your session will expire in 2 minutes due to inactivity.');
                }, 28 * 60 * 1000); 
            }
            
            
            document.addEventListener('mousemove', resetSessionTimeout);
            document.addEventListener('keypress', resetSessionTimeout);
            resetSessionTimeout();
        </script>
    <?php endif; ?>
</body>
</html>