<?php
/**
 * DATABASE SETUP SCRIPT
 * Run this once to create the database tables
 */

require_once 'includes/config.php';
require_once 'includes/database.php';

echo "<h2>Setting up database tables...</h2>";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(50) DEFAULT 'User',
        department VARCHAR(50) DEFAULT 'General',
        color VARCHAR(20) DEFAULT '#00d4ff',
        ref_img VARCHAR(255) DEFAULT 'default-avatar.png',
        clearance VARCHAR(20) DEFAULT 'BETA',
        status VARCHAR(20) DEFAULT 'active',
        is_admin BOOLEAN DEFAULT 0,
        permissions TEXT DEFAULT '{\"read\":true,\"write\":true}',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Users table created<br>";
    
    // Create sessions table
    $sql = "CREATE TABLE IF NOT EXISTS sessions (
        id VARCHAR(128) PRIMARY KEY,
        user_id INTEGER,
        ip_address VARCHAR(45),
        user_agent TEXT,
        payload TEXT,
        last_activity INTEGER,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ Sessions table created<br>";
    
    // Create admin user if not exists
    $adminCheck = $pdo->query("SELECT COUNT(*) as count FROM users WHERE email = 'admin@enterpriseos.com'")->fetch();
    
    if ($adminCheck['count'] == 0) {
        $hashedPassword = password_hash('Admin123!', PASSWORD_DEFAULT);
        $permissions = json_encode(['all' => true]);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, name, role, department, clearance, is_admin, permissions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            'admin',
            'admin@enterpriseos.com',
            $hashedPassword,
            'System Administrator',
            'System Architect',
            'Infrastructure',
            'ALPHA',
            1,
            $permissions
        ]);
        echo "✅ Admin user created (email: admin@enterpriseos.com, password: Admin123!)<br>";
    } else {
        echo "ℹ️ Admin user already exists<br>";
    }
    
    echo "<br><strong>Database setup complete!</strong>";
    echo "<br><br><a href='pages/login.php' style='color: #00d4ff;'>Go to Login Page</a>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>