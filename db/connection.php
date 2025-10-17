<?php
function getDbConnection() {
    // Load environment variables
    static $conn = null;
    if ($conn) return $conn;

    $envPath = __DIR__ . '/db_env.php';
    if (!file_exists($envPath)) {
        error_log('Missing DB env file: ' . $envPath);
        return null;
    }

    $env = require $envPath;

    // Read config
    $host     = $env['DB_HOST'] ?? 'localhost';
    $dbname   = $env['DB_NAME'] ?? 'test';
    $username = $env['DB_USER'] ?? 'root';
    $password = $env['DB_PASSWORD'] ?? '';

    // Create connection
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        error_log('Database connection error: ' . $conn->connect_error);
        return null;
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
