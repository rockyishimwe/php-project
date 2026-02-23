<?php
/**
 * SQLITE DATABASE SETUP SCRIPT
 * Creates the database and tables for Enterprise OS using SQLite
 */

require_once 'includes/config.php';

try {
    // Create data directory if it doesn't exist
    $dataDir = dirname(DB_FILE);
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
        echo "✅ Created data directory!\n";
    }

    // Connect to SQLite database (creates file if it doesn't exist)
    $pdo = new PDO("sqlite:" . DB_FILE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connected to SQLite database successfully!\n";

    // Create users table
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        name TEXT NOT NULL,
        role TEXT NOT NULL,
        department TEXT NOT NULL,
        color TEXT DEFAULT '#007bff',
        ref_img TEXT,
        clearance TEXT DEFAULT 'BETA' CHECK (clearance IN ('ALPHA', 'BETA', 'GAMMA')),
        status TEXT DEFAULT 'active' CHECK (status IN ('active', 'inactive', 'suspended')),
        is_admin INTEGER DEFAULT 0,
        permissions TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Users table created!\n";

    // Create sessions table
    $sql = "
    CREATE TABLE IF NOT EXISTS sessions (
        id TEXT PRIMARY KEY,
        user_id INTEGER,
        ip_address TEXT,
        user_agent TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        last_activity DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✅ Sessions table created!\n";

    // Insert sample users
    $users = [
        [
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'name' => 'Rocky Gen',
            'role' => 'System Architect',
            'department' => 'Infrastructure',
            'color' => '#00d4ff',
            'ref_img' => 'assets/images/slack.ico',
            'clearance' => 'ALPHA',
            'is_admin' => 1,
            'permissions' => '["all"]'
        ],
        [
            'username' => 'marius',
            'password' => password_hash('marius123', PASSWORD_DEFAULT),
            'name' => 'Marius Strategist',
            'role' => 'Chief Financial Officer',
            'department' => 'Finance',
            'color' => '#00ff88',
            'ref_img' => 'assets/images/Beach Scene 2.png',
            'clearance' => 'BETA',
            'is_admin' => 0,
            'permissions' => '["tasks","analytics"]'
        ],
        [
            'username' => 'forever',
            'password' => password_hash('forever123', PASSWORD_DEFAULT),
            'name' => 'Forever Smart',
            'role' => 'Lead Developer',
            'department' => 'Engineering',
            'color' => '#ffa500',
            'ref_img' => 'assets/images/smooth_skin_portrait.jpg',
            'clearance' => 'BETA',
            'is_admin' => 0,
            'permissions' => '["tasks","analytics"]'
        ],
        [
            'username' => 'albert',
            'password' => password_hash('albert123', PASSWORD_DEFAULT),
            'name' => 'Albert Einstein',
            'role' => 'R&D Director',
            'department' => 'Research',
            'color' => '#ff006e',
            'ref_img' => 'assets/images/albert.jpg',
            'clearance' => 'BETA',
            'is_admin' => 0,
            'permissions' => '["tasks","analytics"]'
        ]
    ];

    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO users
        (username, password, name, role, department, color, ref_img, clearance, status, is_admin, permissions)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?)
    ");

    foreach ($users as $user) {
        $stmt->execute([
            $user['username'],
            $user['password'],
            $user['name'],
            $user['role'],
            $user['department'],
            $user['color'],
            $user['ref_img'],
            $user['clearance'],
            $user['is_admin'],
            $user['permissions']
        ]);
    }
    echo "✅ Sample users inserted/updated!\n";

    // Create indexes for better performance
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_username ON users(username)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_status ON users(status)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_sessions_user_id ON sessions(user_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_sessions_last_activity ON sessions(last_activity)");
    echo "✅ Indexes created!\n";

    echo "\n🎉 SQLite database setup completed successfully!\n";
    echo "Database file: " . DB_FILE . "\n";
    echo "You can now proceed to Step 2: Database Connection\n";

} catch (PDOException $e) {
    echo "❌ Database setup failed: " . $e->getMessage() . "\n";
}
?>