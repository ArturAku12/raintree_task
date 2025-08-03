<?php

namespace Config;

$pdo = Database::getConnection();

$sql = file_get_contents(__DIR__ . '/../sql/update.sql');

try {
    $pdo->exec($sql);
} catch (\PDOException $e) {
    echo "Error executing update SQL: " . $e->getMessage() . "\n";
}
