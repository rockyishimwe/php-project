<?php
/**
 * LOGIN TEST
 * Tests login functionality with security enhancements
 */

require_once 'includes/config.php';
require_once 'includes/session_handler.php';
require_once 'includes/database.php';
require_once 'includes/auth.php';
require_once 'includes/security.php';
require_once 'includes/functions.php';

// Initialize sessions first
initDatabaseSessions();

// Test login with valid credentials
echo "🔐 Testing Login Functionality...\n\n";

$testEmail = 'admin@example.com';
$testPassword = 'admin123';

echo "Test 1: Login with valid credentials\n";
$user = authenticateUser($testEmail);

if ($user) {
    // Now login the user
    $loginResult = loginUser($testEmail);
    if ($loginResult) {
        echo "✅ Login successful for user: " . $user['username'] . "\n";
        echo "User ID: " . $user['id'] . "\n";
        echo "Name: " . $user['name'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n";

        // Test session validation
        echo "\nTest 2: Session validation\n";
        $sessionValid = validateSession();
        echo "Session valid: " . ($sessionValid ? 'Yes' : 'No') . "\n";

        // Test permissions
        echo "\nTest 3: Permission checking\n";
        echo "Has admin permission: " . (hasPermission('admin') ? 'Yes' : 'No') . "\n";
        echo "Has read permission: " . (hasPermission('read') ? 'Yes' : 'No') . "\n";

        // Logout
        logoutUser();
        echo "\nTest 4: Logout\n";
        echo "Logged out successfully\n";
    } else {
        echo " Login failed\n";
    }
} else {
    echo " Authentication failed: User not found\n";
}

echo "\nTest 5: Login with invalid credentials\n";
$user = authenticateUser('invalid@example.com');

if (!$user) {
    echo " Correctly rejected invalid credentials\n";
} else {
    echo " Should have rejected invalid credentials\n";
}

echo "\n Login tests completed!\n";
?>