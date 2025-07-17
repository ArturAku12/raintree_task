<?php

use PaymentMethod\PaymentMethod;
use PaymentMethod\ACH;
use PaymentMethod\CreditCard;
use Config\Database;

class Patient
{
    private string $first_name;
    private string $last_name;
    private string $date_of_birth;
    private string $gender;
    private string $address;
    private array $paymentMethods;

    public function __construct(?string $first_name = null, ?string $last_name = null, ?string $date_of_birth = null, ?string $gender = null, ?string $address = null)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->date_of_birth = $date_of_birth;
        $this->gender = $gender;
        $this->address = $address;
    }

    public function addPaymentMethod(PaymentMethod $payment_method): void
    {
        $this->paymentMethods[] = $payment_method;
    }

    private function intoDbArray(): array
    {
        return [
            $this->first_name,
            $this->last_name,
            $this->date_of_birth,
            $this->gender,
            $this->address,
        ];
    }

    public function printCustomerDetails(): string
    {
        $details = "Patient: {$this->first_name} {$this->last_name}, Gender: {$this->gender}, Address: {$this->address}, DOB: {$this->date_of_birth}\n";
        foreach ($this->paymentMethods as $method) {
            $methodArray = $method->toDbArray();
            $status = $methodArray['status'] ? 'Active' : 'Inactive';
            if ($method instanceof CreditCard) {
                $masked = $method->getMaskedCardNumber();
                $details .= "Payment Method: CreditCard | Masked Card Number: {$masked} ({$methodArray['expiry_date']}) | Status: {$status}\n";
            } elseif ($method instanceof ACH) {
                $masked = $method->getMaskedCardNumber();
                $details .= "Payment Method: ACH | Masked Account Number: {$masked} | Status: {$status}\n";
            }
        }
        return $details;
    }

    public function saveInformation(PDO $pdo): void
    {
        $stmt_patient = $pdo->prepare("INSERT INTO patient (first_name, last_name, date_of_birth, gender, address) VALUES (?, ?, ?, ?, ?)");
        $stmt_patient->execute($this->intoDbArray());

        $patientId = $pdo->lastInsertId(); // Ensure this retrieves the correct ID

        foreach ($this->paymentMethods as $method) {
            $paymentMethodArray = $method->toDbArray();
            if ($method instanceof ACH) {
                $stmt_payment = $pdo->prepare("INSERT INTO payment_methods (patient_id, type, account_number, routing_number, account_holder_name, status) VALUES (:patient_id, 'ACH', :account_number, :routing_number, :account_holder_name, :status)");
                $stmt_payment->execute([
                    ':patient_id' => $patientId, // Use the correct patient ID
                    ':account_number' => $paymentMethodArray['account_number'],
                    ':routing_number' => $paymentMethodArray['routing_number'],
                    ':account_holder_name' => $paymentMethodArray['account_holder_name'],
                    ':status' => $paymentMethodArray['status'],
                ]);
            } elseif ($method instanceof CreditCard) {
                $stmt_payment = $pdo->prepare("INSERT INTO payment_methods (patient_id, type, card_number, expiry_date, card_holder_name, status) VALUES (:patient_id, 'CreditCard', :card_number, :expiry_date, :card_holder_name, :status)");
                $stmt_payment->execute([
                    ':patient_id' => $patientId, // Use the correct patient ID
                    ':card_number' => $paymentMethodArray['card_number'],
                    ':expiry_date' => $paymentMethodArray['expiry_date'],
                    ':card_holder_name' => $paymentMethodArray['card_holder_name'],
                    ':status' => $paymentMethodArray['status'],
                ]);
            }
        }
        print "Inserted new patient row.\n";
    }

    static public function retrieveInformation(string|int $id): Patient
    {
        $pdo = Database::getConnection();
        // Logic to retrieve information from a MySQL database using PDO
        $stmt1 = $pdo->prepare("SELECT * FROM patient WHERE patient_id = :id");
        $stmt1->execute([':id' => $id]);
        $patient = $stmt1->fetch(PDO::FETCH_ASSOC);

        $stmt2 = $pdo->prepare("SELECT * FROM payment_methods WHERE patient_id = :id");
        $stmt2->execute([':id' => $id]);
        $paymentMethods = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $newPatient = new Patient(
            $patient['first_name'],
            $patient['last_name'],
            $patient['date_of_birth'],
            $patient['gender'],
            $patient['address'],
        );

        foreach ($paymentMethods as $method) {
            if ($method['type'] === 'ACH') {
                $newPatient->addPaymentMethod(
                    new ACH(
                        $method['account_number'],
                        $method['routing_number'],
                        $method['account_holder_name'],
                        $method['status']
                    )
                );
            } elseif ($method['type'] === 'CreditCard') {
                $newPatient->addPaymentMethod(
                    new CreditCard(
                        $method['card_number'],
                        $method['expiry_date'],
                        $method['card_holder_name'],
                        $method['status']
                    )
                );
            }
        }
        return $newPatient;
    }
}
