<?php
/**
 * REGISTRATION TEST
 * Tests the user registration functionality
 */

require_once 'includes/config.php';
require_once 'includes/session_handler.php';
require_once 'includes/auth.php';
require_once 'includes/security.php';
require_once 'includes/functions.php';

initDatabaseSessions();

echo "🧑‍💻 Testing User Registration...\n\n";

$timestamp = time();
$testData = [
    'username' => 'testuser_' . $timestamp,
    'email' => 'testuser_' . $timestamp . '@example.com',
    'password' => 'TestPass123!',
    'name' => 'Test User',
    'role' => 'Developer',
    'department' => 'Engineering'
];

echo "Test 1: Register new user\n";
$result = registerUser($testData);

if ($result['success']) {
    echo "✅ Registration successful for: " . $testData['email'] . "\n";
    echo "User ID: " . $result['user_id'] . "\n";

    // Test login with the new user
    echo "\nTest 2: Login with new user\n";
    $user = authenticateUser($testData['email'], $testData['password']);

    if ($user) {
        echo "✅ Authentication successful\n";
        echo "User: " . $user['name'] . " (" . $user['email'] . ")\n";

        // Test login function
        $loginResult = loginUser($testData['email']);
        if ($loginResult) {
            echo "✅ Login successful\n";

            // Logout
            logoutUser();
            echo "✅ Logout successful\n";
        } else {
            echo "❌ Login failed\n";
        }
    } else {
        echo "❌ Authentication failed\n";
    }
} else {
    echo "❌ Registration failed: " . $result['message'] . "\n";
}

echo "\nTest 3: Try registering with existing email\n";
$duplicateData = $testData;
$duplicateData['username'] = 'testuser2_' . $timestamp;
$result = registerUser($duplicateData);

if (!$result['success'] && strpos($result['message'], 'already registered') !== false) {
    echo "✅ Correctly rejected duplicate email\n";
} else {
    echo "❌ Should have rejected duplicate email\n";
}

echo "\nTest 4: Try registering with weak password\n";
$weakPasswordData = $testData;
$weakPasswordData['email'] = 'weak_' . $timestamp . '@example.com';
$weakPasswordData['username'] = 'weakuser_' . $timestamp;
$weakPasswordData['password'] = 'weak';
$result = registerUser($weakPasswordData);

if (!$result['success'] && strpos($result['message'], 'Password') !== false) {
    echo "✅ Correctly rejected weak password\n";
} else {
    echo "❌ Should have rejected weak password\n";
}

echo "\n🎉 Registration tests completed!\n";
?>