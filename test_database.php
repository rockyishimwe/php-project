<?php
/**
 * DATABASE CONNECTION TEST
 * Tests the database connection and basic queries
 */

require_once 'includes/config.php';
require_once 'includes/database.php';

try {
    echo "🔍 Testing database connection...\n";

    // Test connection
    $db = Database::getInstance();
    echo "✅ Database connection successful!\n";

    // Test basic query - count users
    $userCount = $db->selectOne("SELECT COUNT(*) as count FROM users");
    echo "✅ Found {$userCount['count']} users in database\n";

    // Test user retrieval
    $users = $db->select("SELECT username, name, role FROM users LIMIT 5");
    echo "✅ Sample users:\n";
    foreach ($users as $user) {
        echo "   - {$user['username']}: {$user['name']} ({$user['role']})\n";
    }

    // Test session table
    $sessionCount = $db->selectOne("SELECT COUNT(*) as count FROM sessions");
    echo "✅ Sessions table accessible (current sessions: {$sessionCount['count']})\n";

    echo "\n🎉 All database tests passed! Ready for Step 3: User Migration\n";

} catch (Exception $e) {
    echo "❌ Database test failed: " . $e->getMessage() . "\n";
}
?>