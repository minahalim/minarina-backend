<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use Psr\Http\Message\ResponseInterface as Response;

class InvoiceCreateAction extends InvoiceAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $invoice = $this->invoiceRepository->create($data);

        $this->logger->info("Invoice was added.");

        return $this->respondWithData($invoice);
    }
}
