CREATE TABLE patient (
    patient_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender VARCHAR(20),
    address TEXT
);

CREATE TABLE payment_methods (
    method_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED NOT NULL,
    type VARCHAR(50) NOT NULL,
    account_number VARCHAR(50),
    routing_number VARCHAR(50),
    card_number VARCHAR(16),
    expiry_date VARCHAR(5) COMMENT 'Format: mm/yy',
    account_holder_name VARCHAR(100),
    card_holder_name VARCHAR(100),
    status BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id)
);