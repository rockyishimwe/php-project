<?php
/**
 * AUTHENTICATION TEST
 * Tests the database-backed authentication functions
 */

require_once 'includes/config.php';
require_once 'includes/auth.php';

echo "🔍 Testing authentication functions...\n\n";

// Test 1: Authenticate valid user
echo "Test 1: Authenticating admin user...\n";
$user = authenticateUser('admin', 'admin123');
if ($user) {
    echo "✅ Admin authentication successful\n";
    echo "   Name: {$user['name']}\n";
    echo "   Role: {$user['role']}\n";
    echo "   Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n";
    echo "   Permissions: " . implode(', ', $user['permissions']) . "\n";
} else {
    echo "❌ Admin authentication failed\n";
}

echo "\n";

// Test 2: Authenticate invalid user
echo "Test 2: Testing invalid credentials...\n";
$invalidUser = authenticateUser('admin', 'wrongpassword');
if (!$invalidUser) {
    echo "✅ Invalid password correctly rejected\n";
} else {
    echo "❌ Invalid password was accepted (security issue!)\n";
}

echo "\n";

// Test 3: Get user by ID
echo "Test 3: Getting user by ID...\n";
$userById = getUserById(1); // Assuming admin is ID 1
if ($userById) {
    echo "✅ User retrieval by ID successful\n";
    echo "   Username: {$userById['username']}\n";
    echo "   Department: {$userById['department']}\n";
} else {
    echo "❌ User retrieval by ID failed\n";
}

echo "\n";

// Test 4: Get all users
echo "Test 4: Getting all users...\n";
$allUsers = getAllUsers();
if (count($allUsers) > 0) {
    echo "✅ Retrieved " . count($allUsers) . " users:\n";
    foreach ($allUsers as $u) {
        echo "   - {$u['username']}: {$u['name']} ({$u['role']})\n";
    }
} else {
    echo "❌ No users retrieved\n";
}

echo "\n";

// Test 5: Test all sample users
echo "Test 5: Testing all sample user logins...\n";
$testUsers = [
    ['username' => 'admin', 'password' => 'admin123'],
    ['username' => 'marius', 'password' => 'marius123'],
    ['username' => 'forever', 'password' => 'forever123'],
    ['username' => 'albert', 'password' => 'albert123']
];

$successCount = 0;
foreach ($testUsers as $testUser) {
    $result = authenticateUser($testUser['username'], $testUser['password']);
    if ($result) {
        echo "✅ {$testUser['username']} login successful\n";
        $successCount++;
    } else {
        echo "❌ {$testUser['username']} login failed\n";
    }
}

echo "\n📊 Test Results: $successCount/" . count($testUsers) . " users authenticated successfully\n";

if ($successCount === count($testUsers)) {
    echo "\n🎉 All authentication tests passed! Ready for Step 4: Session Management\n";
} else {
    echo "\n⚠️  Some authentication tests failed. Please check the database setup.\n";
}
?>