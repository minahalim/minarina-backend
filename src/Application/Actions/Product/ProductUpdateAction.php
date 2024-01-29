<?php

declare (strict_types = 1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class ProductUpdateAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $product = $this->productRepository->update($data);

        $this->logger->info("Product was updated.");

        return $this->respondWithData($product);
    }
}