<?php

namespace Config;

require_once __DIR__ . '/../config/Database.php';

$pdo = Database::getConnection();

$schema = file_get_contents(__DIR__ . '/../sql/schema.sql');

try {
    $pdo->exec($schema);
} catch (\PDOException $e) {
    if (str_contains($e->getMessage(), 'already exists')) {
        echo "Table already exists. Skipping creation.\n";
    } else {
        echo "Error executing schema: " . $e->getMessage() . "\n";
    }
}
