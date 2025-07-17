<?php

namespace PaymentMethod;

use PaymentMethod\PaymentMethod;

class ACH extends PaymentMethod
{
    private $accountNumber;
    private $routingNumber;
    private $accountHolderName;

    public function __construct(string $accountNumber, string $routingNumber, string $accountHolderName, bool $status = true)
    {
        parent::__construct($status);
        $this->accountNumber = $accountNumber;
        $this->routingNumber = $routingNumber;
        $this->accountHolderName = $accountHolderName;
    }

    public function toDbArray(): array
    {
        return [
            'account_number' => $this->accountNumber,
            'routing_number' => $this->routingNumber,
            'account_holder_name' => $this->accountHolderName,
            'status' => $this->status ? 1 : 0
        ];
    }

    public function getMaskedCardNumber(): string
    {
        $length = strlen($this->accountNumber);
        $lastFourDigits = substr($this->accountNumber, -4);
        $maskedPortion = str_repeat('*', $length - 4);
        return $maskedPortion . $lastFourDigits;
    }
}
