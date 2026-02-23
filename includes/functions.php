<?php
/**
 * UTILITY FUNCTIONS
 * Common functions used throughout the application
 */

require_once 'security.php';

/**
 * Check if user is admin
 */
function isAdmin($user = null) {
    if ($user === null) {
        $user = getCurrentUser();
    }
    return isset($user['is_admin']) && $user['is_admin'] === true;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['active_user']) && isset($_SESSION['user_id']);
}

/**
 * Get current logged-in user
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return $_SESSION['active_user'] ?? null;
}

/**
 * Redirect to URL
 */
function redirect($url, $message = null, $type = 'info') {
    if ($message) {
        $_SESSION['flash_message'] = ['type' => $type, 'message' => $message];
    }
    header("Location: $url");
    exit();
}

/**
 * Sanitize input data (legacy function - now uses security.php)
 */
function sanitize($data) {
    return sanitizeInput($data);
}

/**
 * Display success message
 */
function showSuccess($message) {
    return '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}

/**
 * Display error message
 */
function showError($message) {
    return '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
}

/**
 * Display warning message
 */
function showWarning($message) {
    return '<div class="alert alert-warning">' . htmlspecialchars($message) . '</div>';
}

/**
 * Display info message
 */
function showInfo($message) {
    return '<div class="alert alert-info">' . htmlspecialchars($message) . '</div>';
}

/**
 * Redirect with message
 */
function redirectWithMessage($url, $message, $type = 'info') {
    $_SESSION['flash_message'] = ['type' => $type, 'message' => $message];
    header("Location: $url");
    exit();
}

/**
 * Display flash message
 */
function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);

        $function = 'show' . ucfirst($message['type']);
        if (function_exists($function)) {
            echo $function($message['message']);
        }
    }
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'M j, Y') {
    return date($format, strtotime($date));
}

/**
 * Format date and time for display
 */
function formatDateTime($datetime, $format = 'M j, Y g:i A') {
    return date($format, strtotime($datetime));
}

/**
 * Truncate text to specified length
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length - strlen($suffix)) . $suffix;
}

/**
 * Generate random string
 */
function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Check if user has permission
 */
function hasPermission($permission) {
    if (!isLoggedIn()) {
        return false;
    }

    $user = getCurrentUser();
    if (!$user) {
        return false;
    }

    // Admin has all permissions
    if (isAdmin($user)) {
        return true;
    }

    // Check specific permissions
    $permissions = is_array($user['permissions']) ? $user['permissions'] : json_decode($user['permissions'], true);
    return in_array($permission, $permissions) || in_array('all', $permissions);
}

/**
 * Require specific permission
 */
function requirePermission($permission) {
    if (!hasPermission($permission)) {
        redirectWithMessage('dashboard.php', 'You do not have permission to access this page.', 'error');
    }
}

/**
 * Get user avatar URL
 */
function getUserAvatar($user) {
    if (!empty($user['ref_img'])) {
        if (strpos($user['ref_img'], 'assets/') === 0) {
            return '../' . $user['ref_img'];
        }
        return '../assets/images/' . $user['ref_img'];
    }
    return '../assets/images/default-avatar.png';
}

/**
 * Validate form data
 */
function validateFormData($rules, $data) {
    $errors = [];

    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? '';

        // Required check
        if (isset($rule['required']) && $rule['required'] && empty($value)) {
            $errors[$field] = ucfirst($field) . ' is required.';
            continue;
        }

        // Skip further validation if empty and not required
        if (empty($value) && !isset($rule['required'])) {
            continue;
        }

        // Email validation
        if (isset($rule['email']) && $rule['email'] && !validateEmail($value)) {
            $errors[$field] = 'Please enter a valid email address.';
        }

        // Password validation
        if (isset($rule['password']) && $rule['password'] && !validatePassword($value)) {
            $errors[$field] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters with uppercase, lowercase, and number.';
        }

        // Min length
        if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
            $errors[$field] = ucfirst($field) . ' must be at least ' . $rule['min_length'] . ' characters.';
        }

        // Max length
        if (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
            $errors[$field] = ucfirst($field) . ' must not exceed ' . $rule['max_length'] . ' characters.';
        }

        // Custom pattern
        if (isset($rule['pattern']) && !preg_match($rule['pattern'], $value)) {
            $errors[$field] = isset($rule['pattern_message']) ? $rule['pattern_message'] : 'Invalid format.';
        }
    }

    return $errors;
}