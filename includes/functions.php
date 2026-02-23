<?php
/**
 * UTILITY FUNCTIONS (FIXED)
 */

require_once __DIR__ . '/security.php';

/**
 * Check if user is admin — FIXED: was using === true but SQLite returns int 0/1
 */
function isAdmin($user = null) {
    if ($user === null) {
        $user = getCurrentUser();
    }
    return isset($user['is_admin']) && (bool)$user['is_admin'];
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
 * Sanitize input data (legacy function)
 */
function sanitize($data) {
    return sanitizeInput($data);
}

function showSuccess($message) {
    return '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' . htmlspecialchars($message) . '</div>';
}

function showError($message) {
    return '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($message) . '</div>';
}

function showWarning($message) {
    return '<div class="alert alert-warning"><i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($message) . '</div>';
}

function showInfo($message) {
    return '<div class="alert alert-info"><i class="fas fa-info-circle"></i> ' . htmlspecialchars($message) . '</div>';
}

function redirectWithMessage($url, $message, $type = 'info') {
    $_SESSION['flash_message'] = ['type' => $type, 'message' => $message];
    header("Location: $url");
    exit();
}

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

function formatDate($date, $format = 'M j, Y') {
    return date($format, strtotime($date));
}

function formatDateTime($datetime, $format = 'M j, Y g:i A') {
    return date($format, strtotime($datetime));
}

function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length - strlen($suffix)) . $suffix;
}

function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

function hasPermission($permission) {
    if (!isLoggedIn()) return false;
    $user = getCurrentUser();
    if (!$user) return false;
    if (isAdmin($user)) return true;
    $permissions = is_array($user['permissions']) ? $user['permissions'] : json_decode($user['permissions'], true);
    return in_array($permission, (array)$permissions) || in_array('all', (array)$permissions);
}

function requirePermission($permission) {
    if (!hasPermission($permission)) {
        redirectWithMessage('dashboard.php', 'You do not have permission to access this page.', 'error');
    }
}

function getUserAvatar($user) {
    if (!empty($user['ref_img'])) {
        if (strpos($user['ref_img'], 'assets/') === 0) {
            return '../' . $user['ref_img'];
        }
        return '../assets/images/' . $user['ref_img'];
    }
    return '../assets/images/default-avatar.png';
}

function validateFormData($rules, $data) {
    $errors = [];
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? '';
        if (isset($rule['required']) && $rule['required'] && empty($value)) {
            $errors[$field] = ucfirst($field) . ' is required.';
            continue;
        }
        if (empty($value) && !isset($rule['required'])) continue;
        if (isset($rule['email']) && $rule['email'] && !validateEmail($value))
            $errors[$field] = 'Please enter a valid email address.';
        if (isset($rule['password']) && $rule['password'] && !validatePassword($value))
            $errors[$field] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.';
        if (isset($rule['min_length']) && strlen($value) < $rule['min_length'])
            $errors[$field] = ucfirst($field) . ' must be at least ' . $rule['min_length'] . ' characters.';
        if (isset($rule['max_length']) && strlen($value) > $rule['max_length'])
            $errors[$field] = ucfirst($field) . ' must not exceed ' . $rule['max_length'] . ' characters.';
        if (isset($rule['pattern']) && !preg_match($rule['pattern'], $value))
            $errors[$field] = $rule['pattern_message'] ?? 'Invalid format.';
    }
    return $errors;
}
