<?php
/**
 * SESSION MANAGEMENT TEST
 * Tests the database-backed session system
 */

// Initialize sessions BEFORE any output
require_once 'includes/config.php';
require_once 'includes/session_handler.php';
initDatabaseSessions();

require_once 'includes/auth.php';

echo "🔍 Testing database session management...\n\n";

// Test 2: Login test
echo "Test 2: Testing login with database sessions...\n";
$loginResult = loginUser('admin', 'password');
if ($loginResult) {
    echo "✅ Admin login successful\n";
    echo "   Session ID: " . session_id() . "\n";
    echo "   User: " . getCurrentUser()['name'] . "\n";
    echo "   Is logged in: " . (isLoggedIn() ? 'Yes' : 'No') . "\n";
} else {
    echo "❌ Admin login failed\n";
}

echo "\n";

// Test 3: Check session in database
echo "Test 3: Verifying session stored in database...\n";
$db = Database::getInstance();
$sessionData = $db->selectOne(
    "SELECT id, user_id, ip_address, user_agent, created_at, last_activity FROM sessions WHERE id = ?",
    [session_id()]
);

if ($sessionData) {
    echo "✅ Session found in database:\n";
    echo "   Session ID: {$sessionData['id']}\n";
    echo "   User ID: {$sessionData['user_id']}\n";
    echo "   IP Address: {$sessionData['ip_address']}\n";
    echo "   Created: {$sessionData['created_at']}\n";
    echo "   Last Activity: {$sessionData['last_activity']}\n";
} else {
    echo "❌ Session not found in database\n";
}

echo "\n";

// Test 4: Session validation
echo "Test 4: Testing session validation...\n";
$valid = validateSession();
echo "   Session valid: " . ($valid ? 'Yes' : 'No') . "\n";

echo "\n";

// Test 5: Logout test
echo "Test 5: Testing logout...\n";
logoutUser();
$stillLoggedIn = isLoggedIn();
echo "   Still logged in after logout: " . ($stillLoggedIn ? 'Yes' : 'No') . "\n";

// Check if session was removed from database
$sessionAfterLogout = $db->selectOne(
    "SELECT id FROM sessions WHERE id = ?",
    [$sessionId]
);
echo "   Session still in database: " . ($sessionAfterLogout ? 'Yes' : 'No') . "\n";

echo "\n";

// Test 6: Session cleanup
echo "Test 6: Testing session cleanup...\n";
$cleaned = cleanupExpiredSessions();
echo "   Expired sessions cleaned: $cleaned\n";

echo "\n";

// Test 7: Multiple sessions
echo "Test 7: Testing multiple concurrent sessions...\n";
initDatabaseSessions();
$session2Id = session_id();
loginUser('marius', 'password');

$allSessions = $db->select("SELECT COUNT(*) as count FROM sessions");
echo "   Total sessions in database: {$allSessions[0]['count']}\n";

echo "\n📊 Session Management Tests Complete!\n";

if ($loginResult && $sessionData && $valid && !$stillLoggedIn) {
    echo "🎉 All session management tests passed!\n";
} else {
    echo "⚠️  Some tests failed. Check the implementation.\n";
}
?>