<?php

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Patient.php';
require_once __DIR__ . '/../PaymentMethod/PaymentMethod.php';
require_once __DIR__ . '/../PaymentMethod/CreditCard.php';
require_once __DIR__ . '/../PaymentMethod/ACH.php';

use PaymentMethod\ACH;
use PaymentMethod\CreditCard;

// Retrieve patient with id = 5
$patient = Patient::retrieveInformation(5);

// Return the patient details as a string
return $patient->printCustomerDetails();
