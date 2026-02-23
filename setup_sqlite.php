<?php
/**
 * SQLITE DATABASE SETUP SCRIPT (FIXED)
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

    $pdo = new PDO("sqlite:" . DB_FILE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected to SQLite database successfully!\n";

    // Users table — FIXED: added 'email' column
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        username   TEXT    UNIQUE NOT NULL,
        email      TEXT    UNIQUE NOT NULL,
        password   TEXT    NOT NULL,
        name       TEXT    NOT NULL,
        role       TEXT    NOT NULL,
        department TEXT    NOT NULL,
        color      TEXT    DEFAULT '#007bff',
        ref_img    TEXT,
        clearance  TEXT    DEFAULT 'BETA' CHECK (clearance IN ('ALPHA','BETA','GAMMA')),
        status     TEXT    DEFAULT 'active' CHECK (status IN ('active','inactive','suspended')),
        is_admin   INTEGER DEFAULT 0,
        permissions TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Users table created!\n";

    // Sessions table — FIXED: added 'payload' column
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS sessions (
        id            TEXT PRIMARY KEY,
        user_id       INTEGER,
        payload       TEXT    DEFAULT '',
        ip_address    TEXT,
        user_agent    TEXT,
        created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
        last_activity INTEGER DEFAULT 0,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "✅ Sessions table created!\n";

    // Sample users (with email added)
    $users = [
        ['admin',   'admin@enterprise.os',   'Admin123!',   'Rocky Gen',          'System Architect',        'Infrastructure', '#00d4ff', 'assets/images/slack.ico',                'ALPHA', 1, '["all"]'],
        ['marius',  'marius@enterprise.os',  'Marius123!',  'Marius Strategist',  'Chief Financial Officer', 'Finance',        '#00ff88', 'assets/images/Beach Scene 2.png',        'BETA',  0, '["tasks","analytics"]'],
        ['forever', 'forever@enterprise.os', 'Forever123!', 'Forever Smart',      'Lead Developer',          'Engineering',    '#ffa500', 'assets/images/smooth_skin_portrait.jpg', 'BETA',  0, '["tasks","analytics"]'],
        ['albert',  'albert@enterprise.os',  'Albert123!',  'Albert Einstein',    'R&D Director',            'Research',       '#ff006e', 'assets/images/albert.jpg',               'BETA',  0, '["tasks","analytics"]'],
    ];

    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO users
            (username, email, password, name, role, department, color, ref_img, clearance, status, is_admin, permissions)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?)
    ");

    foreach ($users as $u) {
        $stmt->execute([
            $u[0], $u[1],
            password_hash($u[2], PASSWORD_DEFAULT),
            $u[3], $u[4], $u[5], $u[6], $u[7], $u[8], $u[9], $u[10]
        ]);
    }
    echo "✅ Sample users inserted!\n";
    echo "\n";
    echo "🔐 Login Credentials:\n";
    echo "   Admin:   admin@enterprise.os   / Admin123!\n";
    echo "   Marius:  marius@enterprise.os  / Marius123!\n";
    echo "   Forever: forever@enterprise.os / Forever123!\n";
    echo "   Albert:  albert@enterprise.os  / Albert123!\n";


    // Contact messages table
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS contact_messages (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name TEXT NOT NULL,
        last_name  TEXT NOT NULL,
        email      TEXT NOT NULL,
        company    TEXT,
        subject    TEXT,
        message    TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Contact messages table created!\n";

    // Indexes
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_users_username ON users(username)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_sessions_user_id ON sessions(user_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_sessions_last_activity ON sessions(last_activity)");
    echo "\n✅ Indexes created!\n";
    echo "\n🎉 Setup complete! Database: " . DB_FILE . "\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
