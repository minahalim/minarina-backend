<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use Psr\Http\Message\ResponseInterface as Response;

class GetInvoiceAction extends InvoiceAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $invoice = $this->invoiceRepository->getInvoice($data);

        $this->logger->info("Invoices were viewed.");

        return $this->respondWithData($invoice);
    }
}
