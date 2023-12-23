<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use App\Application\Actions\Action;
use App\Domain\Invoice\InvoiceRepository;
use Psr\Log\LoggerInterface;

abstract class InvoiceAction extends Action
{
    protected InvoiceRepository $invoiceRepository;

    public function __construct(LoggerInterface $logger, InvoiceRepository $invoiceRepository)
    {
        parent::__construct($logger);
        $this->invoiceRepository = $invoiceRepository;
    }
}
