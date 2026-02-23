<?php
/**
 * AUTHENTICATION FUNCTIONS
 * User authentication and session management
 */

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/session_handler.php';
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/functions.php';

/**
 * Log user activity
 */
function logActivity($action, $details = '') {
    error_log("Activity: $action - $details - User: " . ($_SESSION['user_id'] ?? 'unknown'));
}

/**
 * Authenticate user with database
 */
function authenticateUser($email, $password = null) {
    try {
        $db = Database::getInstance();

        // Get user from database
        $user = $db->selectOne(
            "SELECT id, username, email, password, name, role, department, color, ref_img, clearance, status, is_admin, permissions
             FROM users
             WHERE email = ? AND status = 'active'",
            [$email]
        );

        if (!$user) {
            return false; // User not found or inactive
        }

        // If password is provided, verify it
        if ($password !== null) {
            if (!password_verify($password, $user['password'])) {
                return false; // Invalid password
            }
        }

        // Convert permissions from JSON string to array
        $user['permissions'] = json_decode($user['permissions'], true);

        return $user;

    } catch (Exception $e) {
        error_log("Authentication error: " . $e->getMessage());
        return false;
    }
}

/**
 * Login user
 */
function loginUser($email, $method = 'password') {
    $user = authenticateUser($email);

    if ($user) {
        // Initialize database session if not already done
        if (session_status() === PHP_SESSION_NONE) {
            initDatabaseSessions();
        }

        // Store user data in session
        $_SESSION['active_user'] = $user;
        $_SESSION['user_id'] = $user['email'];
        $_SESSION['login_time'] = time();
        $_SESSION['login_method'] = $method;

        // Update session in database with user info
        $db = Database::getInstance();
        $db->execute(
            "UPDATE sessions SET user_id = ?, last_activity = CURRENT_TIMESTAMP WHERE id = ?",
            [$user['id'], session_id()]
        );

        // Log the login
        logActivity('LOGIN', "Method: $method");

        return true;
    }

    return false;
}

/**
 * Logout user
 */
function logoutUser() {
    if (isset($_SESSION['active_user'])) {
        $user = $_SESSION['active_user']['name'];
        logActivity('LOGOUT', "User: $user");
    }

    // Clear session data
    session_unset();
    session_destroy();

    // Start a new session to replace the destroyed one
    if (function_exists('initDatabaseSessions')) {
        initDatabaseSessions();
    } else {
        session_start();
    }
}

/**
 * Check session timeout
 */
function checkSessionTimeout() {
    if (isset($_SESSION['login_time'])) {
        if ((time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
            logoutUser();
            redirect('dashboard.php?page=login', 'Session expired. Please login again.', 'warning');
        }
    }
}

/**
 * Require authentication
 */
function requireAuth() {
    if (!isLoggedIn()) {
        redirect('dashboard.php?page=login', 'Please login to access this page.', 'info');
    }

    checkSessionTimeout();
}

/**
 * Require admin access
 */
function requireAdmin() {
    requireAuth();

    if (!isAdmin()) {
        redirect('dashboard.php?page=home', 'Access denied. Admin privileges required.', 'danger');
    }
}

/**
 * Get user by ID from database
 */
function getUserById($userId) {
    try {
        $db = Database::getInstance();

        $user = $db->selectOne(
            "SELECT id, username, email, name, role, department, color, ref_img, clearance, status, is_admin, permissions
             FROM users
             WHERE id = ? AND status = 'active'",
            [$userId]
        );

        if ($user) {
            // Convert permissions from JSON string to array
            $user['permissions'] = json_decode($user['permissions'], true);
        }

        return $user;

    } catch (Exception $e) {
        error_log("Get user by ID error: " . $e->getMessage());
        return null;
    }
}

/**
 * Get all users from database
 */
function getAllUsers() {
    try {
        $db = Database::getInstance();

        $users = $db->select(
            "SELECT id, username, email, name, role, department, color, ref_img, clearance, status, is_admin, permissions
             FROM users
             WHERE status = 'active'
             ORDER BY username"
        );

        // Convert permissions from JSON string to array for each user
        foreach ($users as &$user) {
            $user['permissions'] = json_decode($user['permissions'], true);
        }

        return $users;

    } catch (Exception $e) {
        error_log("Get all users error: " . $e->getMessage());
        return [];
    }
}

/**
 * Validate current session
 */
function validateSession() {
    if (!isLoggedIn()) {
        return false;
    }

    // Check session timeout
    if (isset($_SESSION['login_time'])) {
        if (time() - $_SESSION['login_time'] > SESSION_TIMEOUT) {
            logoutUser();
            return false;
        }
    }

    // Update last activity in database
    $db = Database::getInstance();
    $db->execute(
        "UPDATE sessions SET last_activity = CURRENT_TIMESTAMP WHERE id = ?",
        [session_id()]
    );

    return true;
}

/**
 * Require login - redirect if not authenticated
 */
function requireLogin() {
    if (!validateSession()) {
        // Store current URL for redirect after login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit();
    }
}

/**
 * Get redirect URL after login
 */
function getRedirectAfterLogin() {
    $redirect = $_SESSION['redirect_after_login'] ?? 'dashboard.php';
    unset($_SESSION['redirect_after_login']);
    return $redirect;
}

/**
 * Register a new user
 */
function registerUser($data, $profileImagePath = null) {
    try {
        $db = Database::getInstance();

        // Validate required fields
        $required = ['email', 'password', 'name', 'username'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => ucfirst($field) . ' is required.'];
            }
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Please enter a valid email address.'];
        }

        // Check if email already exists
        $existing = $db->selectOne("SELECT id FROM users WHERE email = ?", [$data['email']]);
        if ($existing) {
            return ['success' => false, 'message' => 'Email address is already registered.'];
        }

        // Check if username already exists
        $existing = $db->selectOne("SELECT id FROM users WHERE username = ?", [$data['username']]);
        if ($existing) {
            return ['success' => false, 'message' => 'Username is already taken.'];
        }

        // Validate password strength
        if (!validatePassword($data['password'])) {
            return ['success' => false, 'message' => 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters with uppercase, lowercase, and number.'];
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_ARGON2ID);

        // Set default values
        $role = $data['role'] ?? 'User';
        $department = $data['department'] ?? 'General';
        $permissions = json_encode(['read', 'write']); // Basic permissions

        // Insert new user
        $userId = $db->insert(
            "INSERT INTO users (username, email, password, name, role, department, ref_img, permissions, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)",
            [
                $data['username'],
                $data['email'],
                $hashedPassword,
                $data['name'],
                $role,
                $department,
                $profileImagePath,
                $permissions
            ]
        );

        if ($userId) {
            // Log the registration
            logActivity('REGISTER', "New user: " . $data['email']);

            return ['success' => true, 'message' => 'Registration successful! You can now log in.', 'user_id' => $userId];
        } else {
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }

    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        return ['success' => false, 'message' => 'An error occurred during registration.'];
    }
}