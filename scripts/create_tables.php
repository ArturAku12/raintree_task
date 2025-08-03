<?php

namespace Config;

$pdo = Database::getConnection();

$schema = file_get_contents(__DIR__ . '/../sql/schema.sql');

try {
    $pdo->exec($schema);
} catch (\PDOException $e) {
    if (str_contains($e->getMessage(), 'already exists')) {
    } else {
        echo "Error executing schema: " . $e->getMessage() . "\n";
    }
}
