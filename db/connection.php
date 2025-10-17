<?php
function getDbConnection() {
    // MySQL connection configuration
    $servername = "lamp-docker-db-1";
    $host = getenv('DB_HOST') ?: 'lamp-docker-db-1';
    $dbname = getenv('DB_NAME') ?: 'hub-spoke';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: 'root_password';

    // Create connection using MySQLi
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        error_log('Database connection error: ' . $conn->connect_error);
        return null;
    }

    // Set charset to utf8mb4
    $conn->set_charset('utf8mb4');

    return $conn;
}
?>
