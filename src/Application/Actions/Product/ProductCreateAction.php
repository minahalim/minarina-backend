<?php

declare (strict_types = 1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class ProductCreateAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        $product = $this->productRepository->create($data);

        $this->logger->info("Product was added.");

        return $this->respondWithData($product);
    }
}