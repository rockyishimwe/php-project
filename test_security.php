<?php
/**
 * SECURITY TEST
 * Tests all security functions
 */

require_once 'includes/config.php';
require_once 'includes/session_handler.php';
require_once 'includes/security.php';
require_once 'includes/functions.php';

// Initialize sessions first (before any output)
initDatabaseSessions();

// Now we can output
echo "🔒 Testing Security Functions...\n\n";

// Test 1: CSRF Token Generation
echo "Test 1: CSRF Token Generation\n";
$token1 = generateCSRFToken();
$token2 = getCSRFToken();
echo "Token 1: " . substr($token1, 0, 20) . "...\n";
echo "Token 2: " . substr($token2, 0, 20) . "...\n";
echo "Tokens match: " . ($token1 === $token2 ? 'Yes' : 'No') . "\n";
echo "Token length: " . strlen($token1) . " (expected: " . CSRF_TOKEN_LENGTH . ")\n\n";

// Test 2: CSRF Token Validation
echo "Test 2: CSRF Token Validation\n";
$valid = validateCSRFToken($token1);
$invalid = validateCSRFToken('fake-token-123');
echo "Valid token: " . ($valid ? 'Accepted' : 'Rejected') . "\n";
echo "Invalid token: " . ($invalid ? 'Accepted' : 'Rejected') . "\n\n";

// Test 3: Input Sanitization
echo "Test 3: Input Sanitization\n";
$malicious = "<script>alert('xss')</script><b>Bold</b> UNION SELECT * FROM users";
$sanitized = sanitizeInput($malicious);
echo "Original: $malicious\n";
echo "Sanitized: $sanitized\n\n";

// Test 4: Email Validation
echo "Test 4: Email Validation\n";
$emails = ['valid@email.com', 'invalid-email', 'test@domain.co.uk', ''];
foreach ($emails as $email) {
    $valid = validateEmail($email);
    echo "'$email': " . ($valid ? 'Valid' : 'Invalid') . "\n";
}
echo "\n";

// Test 5: Password Validation
echo "Test 5: Password Validation\n";
$passwords = ['weak', 'Stronger123', 'weakpassword', 'Weak123'];
foreach ($passwords as $pwd) {
    $valid = validatePassword($pwd);
    echo "'$pwd': " . ($valid ? 'Valid' : 'Invalid') . "\n";
}
echo "\n";

// Test 6: Password Hashing
echo "Test 6: Password Hashing\n";
$password = 'TestPassword123';
$hash = hashPassword($password);
$verify = verifyPassword($password, $hash);
$wrongVerify = verifyPassword('wrongpassword', $hash);
echo "Password hashed successfully: " . (strlen($hash) > 50 ? 'Yes' : 'No') . "\n";
echo "Correct password verification: " . ($verify ? 'Success' : 'Failed') . "\n";
echo "Wrong password verification: " . ($wrongVerify ? 'Success' : 'Failed') . "\n\n";

// Test 7: Rate Limiting
echo "Test 7: Rate Limiting\n";
$action = 'test_action';
$results = [];
for ($i = 1; $i <= 105; $i++) {
    $allowed = checkRateLimit($action, 100, 3600); // 100 requests per hour
    if ($i <= 5 || $i >= 98) { // Show first 5 and last 5 results
        $results[] = "Request $i: " . ($allowed ? 'Allowed' : 'Blocked');
    }
}
echo implode("\n", $results) . "\n\n";

// Test 8: Form Validation
echo "Test 8: Form Validation\n";
$rules = [
    'username' => ['required' => true, 'min_length' => 3, 'max_length' => 50],
    'email' => ['required' => true, 'email' => true],
    'password' => ['required' => true, 'password' => true],
    'age' => ['min_length' => 1, 'max_length' => 3, 'pattern' => '/^\d+$/', 'pattern_message' => 'Age must be a number']
];

$testData = [
    'username' => 'testuser',
    'email' => 'test@example.com',
    'password' => 'StrongPass123',
    'age' => '25'
];

$errors = validateFormData($rules, $testData);
echo "Validation errors: " . (empty($errors) ? 'None' : implode(', ', $errors)) . "\n";

// Test with invalid data
$invalidData = [
    'username' => 'u', // too short
    'email' => 'invalid-email', // invalid email
    'password' => 'weak', // weak password
    'age' => 'not-a-number' // invalid pattern
];

$errors2 = validateFormData($rules, $invalidData);
echo "Invalid data errors: " . (empty($errors2) ? 'None' : implode('; ', $errors2)) . "\n\n";

echo "🎉 Security function tests completed!\n";
?>