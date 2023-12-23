<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use Psr\Http\Message\ResponseInterface as Response;

class InvoiceDetailsAction extends InvoiceAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $invoice = $this->invoiceRepository->getInvoiceDetails($data);

        $this->logger->info("Invoices were viewed.");

        return $this->respondWithData($invoice);
    }
}
