<?php
/**
 * SIMPLE SESSION TEST
 */

// Initialize sessions FIRST
require_once 'includes/config.php';
require_once 'includes/session_handler.php';
initDatabaseSessions();

require_once 'includes/auth.php';

echo "Session ID: " . session_id() . "\n";

// Test login
if (loginUser('admin', 'password')) {
    echo "Login successful!\n";
    echo "Current user: " . getCurrentUser()['name'] . "\n";
    echo "Is logged in: " . (isLoggedIn() ? 'Yes' : 'No') . "\n";
} else {
    echo "Login failed!\n";
}

// Check database
$db = Database::getInstance();
$session = $db->selectOne("SELECT * FROM sessions WHERE id = ?", [session_id()]);
echo "Session in DB: " . ($session ? 'Yes' : 'No') . "\n";

echo "Test complete.\n";
?>