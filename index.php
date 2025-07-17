<?php
echo "<h1>Raintree Task Application</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test database connection
try {
    $config = require 'config/db_config.php';
    $pdo = new PDO(
        "mysql:host={$config['HOST']};dbname={$config['DB']}",
        $config['USER'],
        $config['PASS']
    );
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    echo "<p>Database: {$config['DB']} on {$config['HOST']}</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
