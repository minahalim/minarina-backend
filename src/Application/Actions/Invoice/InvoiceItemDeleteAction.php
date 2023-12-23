<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use Psr\Http\Message\ResponseInterface as Response;

class InvoiceItemDeleteAction extends InvoiceAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $invoice = $this->invoiceRepository->deleteInvoiceItem($data);

        $this->logger->info("Invoice item was deleted.");

        return $this->respondWithData($invoice);
    }
}
