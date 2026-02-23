<?php
/**
 * SESSION HANDLER (SIMPLIFIED)
 * Uses standard PHP file sessions - simple and reliable.
 * DB session handler is available but optional.
 */

require_once __DIR__ . '/database.php';

/**
 * Initialize sessions using standard PHP file sessions.
 * Simple and works without database setup.
 */
function initDatabaseSessions() {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,          // Until browser closes
            'path'     => '/',
            'secure'   => false,       // Set to true in production with HTTPS
            'httponly' => true,        // Prevent JS access
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

/**
 * Get current session info
 */
function getSessionInfo() {
    return [
        'id'         => session_id(),
        'user_id'   => $_SESSION['user_id'] ?? null,
        'login_time' => $_SESSION['login_time'] ?? null,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ];
}

function cleanupExpiredSessions() {
    return 0; // File sessions are cleaned up by PHP automatically
}
