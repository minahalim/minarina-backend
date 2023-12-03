<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use App\Application\Actions\Action;
use App\Domain\Admin\AdminRepository;
use Psr\Log\LoggerInterface;

abstract class AdminAction extends Action
{
    protected AdminRepository $adminRepository;

    public function __construct(LoggerInterface $logger, AdminRepository $adminRepository)
    {
        parent::__construct($logger);
        $this->adminRepository = $adminRepository;
    }
}
