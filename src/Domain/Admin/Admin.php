<?php

declare (strict_types = 1);

namespace App\Domain\Admin;

class Admin
{
    public int $adminId;

    public string $username;

    public string $password;

    public string $active;

    public int $canEdit;

    public int $canDelete;

    public string $createdAt;

    public string $updatedAt;

    public function __set($name, $value)
    {
        if ($name === "admin_id") {
            $this->adminId = (int) $value;
        }

        if ($name === "can_edit") {
            $this->canEdit = (int) $value;
        }

        if ($name === "can_delete") {
            $this->canDelete = (int) $value;
        }
    }
}