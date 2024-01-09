<?php

declare (strict_types = 1);

namespace App\Domain\Invoice;

class Invoice
{
    public int $id;

    public string $name;

    public string $address;

    public string $email;

    public string $invoiceDate;

    public string $createdAt;

    public string $updatedAt;

    public function __set($name, $value)
    {
        if ($name === "invoice_date") {
            $this->invoiceDate = (int) $value;
        }

        if ($name === "created_at") {
            $this->createdAt = (int) $value;
        }

        if ($name === "updated_at") {
            $this->updatedAt = (int) $value;
        }
    }
}
