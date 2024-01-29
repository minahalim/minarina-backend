<?php

declare (strict_types = 1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class ProductsAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $product = $this->productRepository->getAllProducts();

        $this->logger->info("Products were viewed.");

        return $this->respondWithData($product);
    }
}