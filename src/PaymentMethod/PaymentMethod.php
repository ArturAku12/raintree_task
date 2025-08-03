<?php

namespace PaymentMethod;


interface PaymentMethodInterface
{
    public function getMaskedCardNumber(): string;
    public function isActive(): bool;
    public function setActive(bool $status): void;
}


abstract class PaymentMethod implements PaymentMethodInterface
{
    protected bool $status;

    public function __construct(bool $status = true)
    {
        $this->status = $status;
    }

    abstract public function getMaskedCardNumber(): string;

    abstract public function toDbArray(): array;

    /**
     * The credit card will overwrite this method, but ACH will not. Is that logical?
     */
    public function isActive(): bool
    {
        return $this->status;
    }

    /**
     * Is this logical? The name of the setter suggest it only turns the status on,
     * but there should be functionality to turn it off as well.
     * 
     * There should be rules to this tho.
     */
    public function setActive(bool $status): void
    {
        $this->status = $status;
    }
}
