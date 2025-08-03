<?php

use Model\Patient;

// Retrieve patient with a random id between 1 and 9
$patient = Patient::retrieveInformation(rand(1, 9));

// Return the patient details as a string
return $patient->printCustomerDetails();
