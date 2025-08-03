<?php

namespace PaymentMethod;

use \DateTime;

class CreditCard extends PaymentMethod
{
    private $cardNumber;
    private $expiryDate;
    private $cardholderName;

    public function __construct(string $cardNumber, string $expiryDate, string $cardholderName, bool $status = true)
    {
        parent::__construct($status);
        $this->cardNumber = $cardNumber;
        $date = DateTime::createFromFormat('m/y', $expiryDate);
        $this->expiryDate = $date;
        $this->cardholderName = $cardholderName;
    }

    public function toDbArray(): array
    {
        return [
            'card_number' => $this->cardNumber,
            'expiry_date' => $this->expiryDate->format('m/y'),
            'card_holder_name' => $this->cardholderName,
            'status' => $this->status ? 1 : 0
        ];
    }

    public function isActive(): bool
    {
        $currentDate = new DateTime();
        $this->expiryDate->modify('last day of this month')->setTime(23, 59, 59);
        $expired = $this->expiryDate < $currentDate;
        if ($expired) {
            $this->status = false;
        }
        return $this->status;
    }

    public function getMaskedCardNumber(): string
    {
        $lastFourDigits = substr($this->cardNumber, -4);
        return "****-****-****-{$lastFourDigits}";
    }
}
