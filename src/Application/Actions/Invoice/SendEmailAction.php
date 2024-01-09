<?php

declare (strict_types = 1);

namespace App\Application\Actions\Invoice;

use Psr\Http\Message\ResponseInterface as Response;

class SendEmailAction extends InvoiceAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $invoice = $this->invoiceRepository->sendEmail($data);

        $this->logger->info("Invoice was emailed.");

        return $this->respondWithData($invoice);
    }
}