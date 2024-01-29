<?php

declare (strict_types = 1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class GetProductAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $product = $this->productRepository->getProduct($data);

        $this->logger->info("Products were viewed.");

        return $this->respondWithData($product);
    }
}