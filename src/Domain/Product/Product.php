<?php

declare (strict_types = 1);

namespace App\Domain\Product;

class Product
{
    public int $id;

    public string $name;

    public string $description;

    public string $weight;

    public string $price;

    public string $photo;

    public string $active;

    public string $createdAt;

    public string $updatedAt;

    public function __set($name, $value)
    {
        if ($name === "created_at") {
            $this->createdAt = (int) $value;
        }

        if ($name === "updated_at") {
            $this->updatedAt = (int) $value;
        }
    }
}
