<?php
/**
 * DATABASE MIGRATION
 * Add email column to users table and update existing users
 */

require_once 'includes/config.php';
require_once 'includes/database.php';

try {
    $db = Database::getInstance();

    // Check if email column exists
    $columns = $db->select("PRAGMA table_info(users)");
    $hasEmail = false;

    foreach ($columns as $column) {
        if ($column['name'] === 'email') {
            $hasEmail = true;
            break;
        }
    }

    if (!$hasEmail) {
        // SQLite doesn't allow adding UNIQUE columns directly, so we need to recreate the table
        $db->execute("PRAGMA foreign_keys=off");

        // Create new table with email column
        $db->execute("
            CREATE TABLE users_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE,
                password VARCHAR(255) NOT NULL,
                name VARCHAR(100) NOT NULL,
                role VARCHAR(100) NOT NULL,
                department VARCHAR(100) NOT NULL,
                color VARCHAR(20) DEFAULT '#007bff',
                ref_img VARCHAR(255),
                clearance TEXT DEFAULT 'BETA',
                status TEXT DEFAULT 'active',
                is_admin BOOLEAN DEFAULT 0,
                permissions TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Copy data from old table to new table with email addresses
        $db->execute("
            INSERT INTO users_new (id, username, email, password, name, role, department, color, ref_img, clearance, status, is_admin, permissions, created_at, updated_at)
            SELECT id, username,
                   CASE username
                       WHEN 'admin' THEN 'admin@example.com'
                       WHEN 'marius' THEN 'marius@example.com'
                       WHEN 'forever' THEN 'forever@example.com'
                       WHEN 'albert' THEN 'albert@example.com'
                       ELSE username || '@example.com'
                   END as email,
                   password, name, role, department, color, ref_img, clearance, status, is_admin, permissions, created_at, updated_at
            FROM users
        ");

        // Drop old table and rename new table
        $db->execute("DROP TABLE users");
        $db->execute("ALTER TABLE users_new RENAME TO users");

        $db->execute("PRAGMA foreign_keys=on");

        echo " Added email column to users table and updated existing users\n";
    } else {
        echo " Email column already exists\n";
    }

    echo " Database migration completed successfully!\n";

} catch (Exception $e) {
    echo " Migration failed: " . $e->getMessage() . "\n";
}
?>