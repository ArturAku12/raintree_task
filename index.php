<?php
require_once __DIR__ . '/Config/Database.php';
require_once __DIR__ . '/Patient.php';
require_once __DIR__ . '/PaymentMethod/PaymentMethod.php';
require_once __DIR__ . '/PaymentMethod/CreditCard.php';
require_once __DIR__ . '/PaymentMethod/ACH.php';

use PaymentMethod\ACH;
use PaymentMethod\CreditCard;

// Include the patient details functionality
echo "<hr>";
echo "<h2>Patient Details</h2>";

try {
    // Retrieve patient with id = 5
    $patient = Patient::retrieveInformation(5);

    if ($patient) {
        echo "<div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<pre style='margin: 0; font-family: monospace;'>";
        echo $patient->printCustomerDetails();
        echo "</pre>";
        echo "</div>";
    } else {
        echo "<p style='color: orange;'>⚠️ No patient found with ID 5</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'> Error retrieving patient details: " . $e->getMessage() . "</p>";
}
