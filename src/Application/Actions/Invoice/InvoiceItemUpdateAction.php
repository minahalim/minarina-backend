<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use Psr\Http\Message\ResponseInterface as Response;

class InvoiceItemUpdateAction extends InvoiceAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $invoice = $this->invoiceRepository->updateInvoiceItem($data);

        $this->logger->info("Invoice item was updated.");

        return $this->respondWithData($invoice);
    }
}
