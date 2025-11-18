<?php
// One-off script to create database and import SQL using credentials from db.php
require_once __DIR__ . '/db.php'; // this sets $conn

// Try to create database if not exists
$dbName = 'pet_health_tracker';
if ($conn->query("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;") === FALSE) {
    die("Failed to create database: " . $conn->error . "\n");
}

// Select the database
if (!$conn->select_db($dbName)) {
    die("Failed to select database: " . $conn->error . "\n");
}

$sqlFile = __DIR__ . '/database.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found at $sqlFile\n");
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    die("Failed to read SQL file\n");
}

// Split SQL file on semicolons that end statements. This is a simplistic splitter and may fail on
// complex SQL with procedures; this dump looks fine for this purpose.
$statements = array_filter(array_map('trim', explode(";\n", $sql)));

foreach ($statements as $statement) {
    if ($statement === '') continue;
    // Add the semicolon back for safety
    $stmt = $statement . ';';
    if ($conn->query($stmt) === FALSE) {
        echo "Error executing statement: " . $conn->error . "\nStatement: " . substr($stmt, 0, 200) . "...\n";
    }
}

echo "Import complete.\n";
?>