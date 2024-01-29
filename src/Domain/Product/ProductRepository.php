<?php

declare (strict_types = 1);

namespace App\Domain\Product;

use PDO;

class ProductRepository
{

    /**
     * @var PDO The database connection
     */
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllProducts(): array
    {
        $sth = $this->connection->prepare("
            select * from products
          ");
        $sth->execute();

        return $sth->fetchAll();
    }

    public function create($data): string
    {
        $sth = $this->connection->prepare("
            insert into products (name, description, weight, price, photo) values (:name, :description, :weight, :price, :photo)
          ");
        $sth->execute([
            "name" => $data['name'],
            "description" => $data['description'],
            "weight" => $data['weight'],
            "price" => $data['price'],
            "photo" => $data['photo'],
        ]);

        return $this->connection->lastInsertId();
    }

    public function update($data): int
    {
        $sth = $this->connection->prepare("
            update products set
            name=:name,
            description=:description,
            weight=:weight,
            price=:price,
            photo=:photo
            where id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
            "name" => $data["name"],
            "description" => $data["description"],
            "weight" => $data['weight'],
            "price" => $data["price"],
            "photo" => $data["photo"],
        ]);

        return $sth->rowCount();
    }

    public function activate($data): int
    {
        $sth = $this->connection->prepare("
            update products set active=:active
            where id=:id
          ");
        $sth->execute([
            "active" => $data["active"],
            "id" => $data["id"],
        ]);

        return $sth->rowCount();
    }

    public function delete($data): int
    {
        $sth = $this->connection->prepare("
            delete from products
            where id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
        ]);

        return $sth->rowCount();
    }

    public function getProduct($data): array
    {
        $sth = $this->connection->prepare("
            select * from products
            where id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
        ]);

        return $sth->fetchAll();
    }
}
