<?php

declare (strict_types = 1);

namespace App\Domain\Admin;

use PDO;

class AdminRepository
{

    /**
     * @var PDO The database connection
     */
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $username
     * @param string $password
     * @return Admin
     * @throws AdminNotFoundException
     */
    public function login(string $username, string $password): Admin
    {
        $sth = $this->connection->prepare("SELECT admin_id, username, active, can_edit, can_delete FROM admins where username=:username and password=:password");
        $sth->execute([
            'username' => $username,
            'password' => $password,
        ]);
        $data = $sth->fetchAll(PDO::FETCH_CLASS, Admin::class);

        if (!isset($data[0])) {
            throw new AdminNotFoundException();
        }

        return $data[0];
    }
}