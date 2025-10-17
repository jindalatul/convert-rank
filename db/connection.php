<?php
function getDbConnection() 
{
    static $conn = null;
    if ($conn) return $conn;

    // Path to env file at project root
    $envPath = dirname(__DIR__) . '/env/db_env.php';

    if (!file_exists($envPath)) {
        error_log('DB env file missing: ' . $envPath);
        return null;
    }

    $env = require $envPath;

    // Extract vars safely
    $host     = $env['DB_HOST'] ?? 'localhost';
    $dbname   = $env['DB_NAME'] ?? 'test';
    $username = $env['DB_USER'] ?? 'root';
    $password = $env['DB_PASSWORD'] ?? '';

    // Create MySQLi connection
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        error_log('Database connection error: ' . $conn->connect_error);
        return null;
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
// For testing connection
//$conn = getDbConnection();
//var_dump($conn);
?>