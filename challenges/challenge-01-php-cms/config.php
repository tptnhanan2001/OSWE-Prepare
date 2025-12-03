<?php
// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'mysql');
define('DB_NAME', getenv('DB_NAME') ?: 'cms_db');
define('DB_USER', getenv('DB_USER') ?: 'cms_user');
define('DB_PASS', getenv('DB_PASS') ?: 'cms_pass');

// Application configuration
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('SESSION_LIFETIME', 3600);

// Database connection
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $pdo;
}

// Start session
session_start();
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
    session_destroy();
    session_start();
}
$_SESSION['last_activity'] = time();

