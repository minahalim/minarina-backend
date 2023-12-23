<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use Psr\Http\Message\ResponseInterface as Response;

class InvoicesAction extends InvoiceAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $invoice = $this->invoiceRepository->getAllInvoices();

        $this->logger->info("Invoices were viewed.");

        return $this->respondWithData($invoice);
    }
}