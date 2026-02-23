<?php
/**
 * CONFIGURATION FILE
 * Database settings, constants, and configuration
 */

// Database Configuration - USE SQLITE FOR NOW
define('DB_TYPE', 'sqlite'); // Using SQLite to avoid MySQL connection issues
define('DB_HOST', 'localhost');
define('DB_NAME', 'enterprise_os');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_FILE', __DIR__ . '/../data/database.sqlite'); // SQLite database file

// Application Settings
define('APP_NAME', 'Enterprise OS');
define('APP_VERSION', '2.0');
define('APP_URL', 'http://localhost/y1c_program/project');

// Security Settings
define('CSRF_TOKEN_LENGTH', 32);
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('PASSWORD_MIN_LENGTH', 8);

// Session Configuration
define('SESSION_SAVE_HANDLER', 'user');
define('SESSION_GC_PROBABILITY', 1);
define('SESSION_GC_DIVISOR', 100);

// File Upload Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);

// Email Configuration (for future use)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');

// Pagination Settings
define('ITEMS_PER_PAGE', 10);

// API Rate Limiting
define('RATE_LIMIT_REQUESTS', 100);
define('RATE_LIMIT_WINDOW', 3600); // 1 hour

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>