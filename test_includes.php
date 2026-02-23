<?php
// Test script to check if dashboard includes work
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing dashboard includes...<br>";

try {
    require_once 'includes/config.php';
    echo "✓ Config loaded<br>";

    require_once 'includes/session_handler.php';
    echo "✓ Session handler loaded<br>";

    require_once 'includes/auth.php';
    echo "✓ Auth loaded<br>";

    require_once 'includes/security.php';
    echo "✓ Security loaded<br>";

    require_once 'includes/functions.php';
    echo "✓ Functions loaded<br>";

    echo "<br>All includes loaded successfully!<br>";

    // Test database connection
    $db = Database::getInstance();
    echo "✓ Database connection successful<br>";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}
?>