<?php
/**
 * DATABASE SESSION HANDLER
 * Custom session handler that stores sessions in database
 */

require_once 'database.php';

class DatabaseSessionHandler implements SessionHandlerInterface {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function open($savePath, $sessionName) {
        // Just return true - database connection is already established
        return true;
    }

    public function close() {
        // Return true - no special cleanup needed
        return true;
    }

    public function read($sessionId) {
        try {
            $session = $this->db->selectOne(
                "SELECT payload FROM sessions WHERE id = ?",
                [$sessionId]
            );

            if ($session) {
                return $session['payload'];
            }

            return ''; // Session not found
        } catch (Exception $e) {
            error_log("Session read error: " . $e->getMessage());
            return '';
        }
    }

    public function write($sessionId, $sessionData) {
        try {
            // Check if session already exists
            $existing = $this->db->selectOne(
                "SELECT id FROM sessions WHERE id = ?",
                [$sessionId]
            );

            if ($existing) {
                // Update existing session
                $this->db->execute(
                    "UPDATE sessions SET payload = ?, last_activity = strftime('%s', 'now') WHERE id = ?",
                    [$sessionData, $sessionId]
                );
            } else {
                // Create new session
                $this->db->insert(
                    "INSERT INTO sessions (id, payload, ip_address, user_agent, last_activity) VALUES (?, ?, ?, ?, strftime('%s', 'now'))",
                    [
                        $sessionId,
                        $sessionData,
                        $_SERVER['REMOTE_ADDR'] ?? '',
                        $_SERVER['HTTP_USER_AGENT'] ?? ''
                    ]
                );
            }

            return true;
        } catch (Exception $e) {
            error_log("Session write error: " . $e->getMessage());
            return false;
        }
    }

    public function destroy($sessionId) {
        try {
            $this->db->execute(
                "DELETE FROM sessions WHERE id = ?",
                [$sessionId]
            );
            return true;
        } catch (Exception $e) {
            error_log("Session destroy error: " . $e->getMessage());
            return false;
        }
    }

    public function gc($maxLifetime) {
        try {
            // Delete sessions older than maxLifetime seconds
            $cutoffTime = time() - $maxLifetime;
            $result = $this->db->execute(
                "DELETE FROM sessions WHERE last_activity < ?",
                [$cutoffTime]
            );
            return $result; // Return number of deleted sessions
        } catch (Exception $e) {
            error_log("Session GC error: " . $e->getMessage());
            return 0;
        }
    }
}

// Function to initialize database sessions
function initDatabaseSessions() {
    // Set session configuration before starting session
    ini_set('session.save_handler', 'user');
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);
    ini_set('session.gc_maxlifetime', SESSION_TIMEOUT ?? 1800);

    $handler = new DatabaseSessionHandler();
    session_set_save_handler($handler, true);
    session_start();
}

// Function to get current session info
function getSessionInfo() {
    return [
        'id' => session_id(),
        'user_id' => $_SESSION['user_id'] ?? null,
        'login_time' => $_SESSION['login_time'] ?? null,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ];
}

// Function to clean up expired sessions
function cleanupExpiredSessions() {
    $handler = new DatabaseSessionHandler();
    return $handler->gc(SESSION_TIMEOUT ?? 1800);
}