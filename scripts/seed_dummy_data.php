<?php

use Config\Database;
use PaymentMethod\ACH;
use PaymentMethod\CreditCard;
use Model\Patient;

$pdo = Database::getConnection();

// Check if data already exists
$stmt = $pdo->query("SELECT COUNT(*) FROM patient");
$single_seed = true; // Change to false if multiple seeds are wanted. 
if ($stmt->fetchColumn() > 0 && $single_seed) {
    return; // Use return instead of exit to allow parent script to continue
}

// Random dummy data sources
$first_names = ['John', 'Jane', 'Alice', 'Bob', 'Eve', 'Charlie', 'Grace', 'Mallory'];
$last_names = ['Doe', 'Smith', 'Brown', 'Johnson', 'Williams', 'Jones', 'Miller', 'Davis'];
$streets = ['Main St', 'Oak Ave', 'Pine Rd', 'Elm St', 'Maple Dr', 'Cedar Ln', 'Birch Blvd', 'Spruce Ct'];
$genders = ['M', 'F'];
$num_patients = 5 + rand(0, 5);


// Generating random dummy patients
$patients = [];
for ($i = 0; $i < $num_patients; $i++) {
    $first = $first_names[array_rand($first_names)];
    $last = $last_names[array_rand($last_names)];
    $dob = date('Y-m-d', strtotime('-' . rand(18, 80) . ' years -' . rand(0, 364) . ' days'));
    $gender = $genders[array_rand($genders)];
    $address = rand(1, 100) . ' ' . $streets[array_rand($streets)];
    $dummy_patient = new Patient($first, $last, $dob, $gender, $address);

    // Adding ACH/Card payment methods
    $num_methods = rand(1, 3); // adds 1-3 payment methods per patient
    for ($j = 0; $j < $num_methods; $j++) {

        $holder_name = "$first $last";
        $status = true;
        if (rand(0, 1)) {
            // Random ACH
            $account_number = str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $routing_number = str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $dummy_patient->addPaymentMethod(new ACH($account_number, $routing_number, $holder_name, $status));
        } else {
            // Random Credit Card
            $card_number = implode('', array_map(fn() => rand(1000, 9999), range(1, 4)));
            $exp_month = rand(1, 12);
            $exp_year = rand(18, 29);
            $expiry_date = sprintf('%02d/%d', $exp_month, $exp_year);
            $status = $expiry_date > date('m/y');
            $dummy_patient->addPaymentMethod(new CreditCard($card_number, $expiry_date, $holder_name, $status));
        }
    }

    $dummy_patient->saveInformation($pdo);
}
