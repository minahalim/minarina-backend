<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use Psr\Http\Message\ResponseInterface as Response;

class AdminLoginAction extends AdminAction
{
  /**
   * {@inheritdoc}
   */
  protected function action(): Response
  {
    $adminData = $this->getFormData();
    $username = $adminData['username'];
    $password = $adminData['password'];
    $admin = $this->adminRepository->login($username, $password);

    $this->logger->info("Admin of username $username was loggedin.");

    return $this->respondWithData($admin);
  }
}
