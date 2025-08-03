<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/config.php';

// // Include and run database setup
// include __DIR__ . '/../scripts/create_tables.php';

// Include the patient details functionality
echo "<hr>";
echo "<h2>Patient Details</h2>";

try {
    // Get patient details from test_script
    $patient_details = include __DIR__ . '/../scripts/test_script.php';

    echo "<div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<pre style='margin: 0; font-family: monospace;'>";
    echo htmlspecialchars($patient_details, ENT_QUOTES, 'UTF-8');
    echo "</pre>";
    echo "</div>";
} catch (Exception $e) {
    echo "<p> Error retrieving patient details: " . $e->getMessage() . "</p>";
}
