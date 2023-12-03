<?php

declare(strict_types=1);

namespace App\Domain\Admin;

use App\Domain\DomainException\DomainRecordNotFoundException;

class AdminNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The admin you requested does not exist.';
}
