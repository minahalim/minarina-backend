<?php

declare (strict_types = 1);

namespace App\Domain\Invoice;

use App\Domain\DomainException\DomainRecordNotFoundException;

class InvoiceNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The invoice you requested does not exist.';
}
