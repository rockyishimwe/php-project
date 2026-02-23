<?php
/**
 * DATABASE MIGRATION
 * Add session_data column to sessions table
 */

require_once 'includes/config.php';
require_once 'includes/database.php';

try {
    $db = Database::getInstance();

    // Check if session_data column exists
    $columns = $db->select("PRAGMA table_info(sessions)");
    $hasSessionData = false;

    foreach ($columns as $column) {
        if ($column['name'] === 'session_data') {
            $hasSessionData = true;
            break;
        }
    }

    if (!$hasSessionData) {
        // Add session_data column
        $db->execute("ALTER TABLE sessions ADD COLUMN session_data TEXT");

        echo "✅ Added session_data column to sessions table\n";
    } else {
        echo "✅ session_data column already exists\n";
    }

    echo "✅ Database migration completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
}
?>