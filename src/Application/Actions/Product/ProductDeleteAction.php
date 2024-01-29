<?php

declare (strict_types = 1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class ProductDeleteAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $product = $this->productRepository->delete($data);

        $this->logger->info("Product was deleted.");

        return $this->respondWithData($product);
    }
}