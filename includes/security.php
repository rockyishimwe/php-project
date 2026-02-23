<?php
/**
 * SECURITY FUNCTIONS
 * CSRF protection, input validation, and security utilities
 */

require_once 'config.php';

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(CSRF_TOKEN_LENGTH / 2));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF token for forms
 */
function getCSRFToken() {
    return generateCSRFToken();
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }

    // Remove HTML tags and encode special characters
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    // Remove potential SQL injection attempts
    $data = str_replace(['\\', '\0', '\n', '\r', '\x1a'], '', $data);

    return trim($data);
}

/**
 * Validate email address
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{' . PASSWORD_MIN_LENGTH . ',}$/';
    return preg_match($pattern, $password);
}

/**
 * Hash password securely
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_ARGON2ID, [
        'memory_cost' => 65536, // 64MB
        'time_cost' => 4,
        'threads' => 3
    ]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Rate limiting function
 */
function checkRateLimit($action, $limit = RATE_LIMIT_REQUESTS, $window = RATE_LIMIT_WINDOW) {
    $key = $action . '_' . ($_SERVER['REMOTE_ADDR'] ?? 'cli');

    if (!isset($_SESSION['rate_limits'])) {
        $_SESSION['rate_limits'] = [];
    }

    $now = time();

    // Clean old entries and count valid requests
    $validRequests = 0;
    if (isset($_SESSION['rate_limits'][$key])) {
        foreach ($_SESSION['rate_limits'][$key] as $timestamp) {
            if (($now - $timestamp) < $window) {
                $validRequests++;
            }
        }
        // Remove old entries
        $_SESSION['rate_limits'][$key] = array_filter($_SESSION['rate_limits'][$key], function($timestamp) use ($now, $window) {
            return ($now - $timestamp) < $window;
        });
    }

    if ($validRequests >= $limit) {
        return false; // Rate limit exceeded
    }

    // Add current request
    if (!isset($_SESSION['rate_limits'][$key])) {
        $_SESSION['rate_limits'][$key] = [];
    }
    $_SESSION['rate_limits'][$key][] = $now;

    return true;
}

/**
 * Set security headers
 */
function setSecurityHeaders() {
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');

    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');

    // Enable XSS protection
    header('X-XSS-Protection: 1; mode=block');

    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Content Security Policy (basic)
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self'");

    // HSTS (HTTP Strict Transport Security) - only if HTTPS
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

/**
 * Validate file upload
 */
function validateFileUpload($file, $allowedTypes = ALLOWED_EXTENSIONS, $maxSize = MAX_FILE_SIZE) {
    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'File upload failed'];
    }

    // Check file size
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'error' => 'File too large'];
    }

    // Check file type
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedTypes)) {
        return ['valid' => false, 'error' => 'File type not allowed'];
    }

    // Check if file is actually an image (for image uploads)
    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            return ['valid' => false, 'error' => 'Invalid image file'];
        }
    }

    return ['valid' => true];
}

/**
 * Log security events
 */
function logSecurityEvent($event, $details = '') {
    $logEntry = sprintf(
        "[%s] SECURITY: %s - IP: %s - User: %s - Details: %s\n",
        date('Y-m-d H:i:s'),
        $event,
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $_SESSION['user_id'] ?? 'guest',
        $details
    );

    error_log($logEntry);
}

/**
 * Check for suspicious activity
 */
function detectSuspiciousActivity() {
    $suspicious = false;
    $reasons = [];

    // Check for SQL injection attempts in GET/POST data
    $inputData = array_merge($_GET, $_POST);
    foreach ($inputData as $key => $value) {
        if (is_string($value)) {
            // Common SQL injection patterns
            $sqlPatterns = [
                '/\b(union|select|insert|update|delete|drop|create|alter)\b/i',
                '/(\b(or|and)\b.*(=|<|>))/i',
                '/(--|#|\/\*)/',
                '/(script|javascript|vbscript|onload|onerror)/i'
            ];

            foreach ($sqlPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    $suspicious = true;
                    $reasons[] = "Suspicious pattern in $key: " . substr($value, 0, 50);
                    break;
                }
            }
        }
    }

    if ($suspicious) {
        logSecurityEvent('SUSPICIOUS_ACTIVITY', implode('; ', $reasons));
    }

    return $suspicious;
}
?>