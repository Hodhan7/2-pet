<?php
require 'db.php';

header('Content-Type: text/plain');

echo "Testing DB connection...\n";
if ($conn->connect_error) {
    echo "Connection error: " . $conn->connect_error . "\n";
    exit(1);
}

echo "Connected. MySQL server info: " . $conn->server_info . "\n";

// Check database selected
$db_selected = $conn->query("SELECT DATABASE() AS db");
if ($db_selected) {
    $row = $db_selected->fetch_assoc();
    echo "Current database: " . ($row['db'] ?? 'none') . "\n";
} else {
    echo "Could not determine current database: " . $conn->error . "\n";
}

// Check if users table exists
$res = $conn->query("SHOW TABLES LIKE 'users'");
if ($res && $res->num_rows > 0) {
    echo "'users' table exists. Row count: ";
    $r = $conn->query("SELECT COUNT(*) AS c FROM users");
    if ($r) {
        $c = $r->fetch_assoc();
        echo $c['c'] . "\n";
    } else {
        echo "could not count users: " . $conn->error . "\n";
    }
} else {
    echo "'users' table does NOT exist.\n";
}

// Verify insert permissions by attempting a safe INSERT INTO a test table
$ok = $conn->query("CREATE TABLE IF NOT EXISTS diag_test (id INT PRIMARY KEY AUTO_INCREMENT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
if ($ok) {
    if ($conn->query("INSERT INTO diag_test () VALUES ()") === TRUE) {
        echo "Insert test succeeded.\n";
    } else {
        echo "Insert test failed: " . $conn->error . "\n";
    }
} else {
    echo "Could not create diag_test table: " . $conn->error . "\n";
}

?>