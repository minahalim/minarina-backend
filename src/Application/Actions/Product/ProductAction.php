<?php

declare (strict_types = 1);

namespace App\Application\Actions\Product;

use App\Application\Actions\Action;
use App\Application\Settings\SettingsInterface;
use App\Domain\Product\ProductRepository;
use Psr\Log\LoggerInterface;

abstract class ProductAction extends Action
{
    protected ProductRepository $productRepository;

    protected SettingsInterface $settings;

    public function __construct(LoggerInterface $logger, ProductRepository $productRepository, SettingsInterface $settings)
    {
        parent::__construct($logger);
        $this->productRepository = $productRepository;
        $this->settings = $settings;
    }
}
