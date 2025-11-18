<?php
/*
 * Database bootstrap for Pet Health Tracker
 * - Reads DB credentials from environment variables (safer for deployments)
 * - Sets connection charset to utf8mb4
 * - Enables mysqli exceptions for easier error handling
 *
 * Expected environment variables (set these in your webserver or .env):
 *  - DB_HOST (default: localhost)
 *  - DB_PORT (default: 3306)
 *  - DB_NAME (default: pet_health_tracker)
 *  - DB_USER
 *  - DB_PASS
 */

// Session hardening
ini_set('session.cookie_lifetime', 3600); // 1 hour
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
// If running on HTTPS, enable secure cookie
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', 1);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Environment-driven credentials with sensible defaults
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'pet_health_tracker';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '11391139Starr7';

// Build mysqli and enable exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, (int)$dbPort);
    // Ensure correct character set
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // In production, prefer logging errors to a file and show a generic message
    error_log('Database connection error: ' . $e->getMessage());
    // Friendly error for development; change for production
    if (php_sapi_name() === 'cli') {
        throw $e;
    }
    http_response_code(500);
    exit('Database connection could not be established.');
}

// Small helper to get the connection (for compatibility)
function db_get_conn()
{
    global $conn;
    return $conn;
}

?>