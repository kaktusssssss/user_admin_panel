<?php
/**
 * Database configuration
 * Adjust these settings according to your environment
 */

// Database connection parameters
define('DB_HOST', 'localhost');
define('DB_NAME', 'user_admin_db');
define('DB_USER', 'root');      // Change this
define('DB_PASS', '');          // Change this (empty for default XAMPP/WAMP)

// Application settings
define('SITE_NAME', 'User Admin Panel');
define('ITEMS_PER_PAGE', 5);    // Pagination limit

// Start session for authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('UTC');

/**
 * Get database connection
 * @return PDO
 * @throws PDOException
 */
function getDBConnection() {
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>