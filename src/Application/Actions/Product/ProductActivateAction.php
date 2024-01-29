<?php

declare (strict_types = 1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class ProductActivateAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $product = $this->productRepository->activate($data);

        $this->logger->info("Product was activated.");

        return $this->respondWithData($product);
    }
}
