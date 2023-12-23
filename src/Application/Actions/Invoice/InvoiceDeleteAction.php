<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use Psr\Http\Message\ResponseInterface as Response;

class InvoiceDeleteAction extends InvoiceAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $invoice = $this->invoiceRepository->delete($data);

        $this->logger->info("Invoice was deleted.");

        return $this->respondWithData($invoice);
    }
}
