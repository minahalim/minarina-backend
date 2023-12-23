<?php

declare (strict_types = 1);

namespace App\Domain\Invoice;

use PDO;

class InvoiceRepository
{

    /**
     * @var PDO The database connection
     */
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllInvoices(): array
    {
        $sth = $this->connection->prepare("
            select *, DATE(invoice_date) as invoice_date from invoices
          ");
        $sth->execute();

        return $sth->fetchAll();
    }

    public function create($data): string
    {
        $sth = $this->connection->prepare("
            insert into invoices (name, address, invoice_date) values (:name, :address, :invoice_date)
          ");
        $sth->execute([
            "name" => $data['name'],
            "address" => $data['address'],
            "invoice_date" => $data['invoice_date'],

        ]);

        return $this->connection->lastInsertId();
    }

    public function update($data): int
    {
        $sth = $this->connection->prepare("
            update invoices set
            name=:name,
            address=:address,
            invoice_date=:invoice_date
            where id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
            "name" => $data["name"],
            "address" => $data["address"],
            "invoice_date" => $data["invoice_date"],
        ]);

        return $sth->rowCount();
    }

    public function delete($data): int
    {
        $sth = $this->connection->prepare("
            delete from invoices
            where id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
        ]);

        return $sth->rowCount();
    }

    public function getInvoice($data): array
    {
        $sth = $this->connection->prepare("
            select *, DATE(invoice_date) as invoice_date from invoices
            where id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
        ]);

        return $sth->fetchAll();
    }

    public function getInvoiceDetails($data): array
    {
        $sth = $this->connection->prepare("
            select invoice_items.id,service,service_description,price,invoice_id, invoices.name, invoices.address, DATE(invoices.invoice_date) as invoice_date from invoice_items
            left join invoices on invoices.id=invoice_items.invoice_id
            where invoice_id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
        ]);

        return $sth->fetchAll();
    }

    public function getInvoiceItem($data): array
    {
        $sth = $this->connection->prepare("
            select * from invoice_items
            where id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
        ]);

        return $sth->fetchAll();
    }

    public function createInvoiceItem($data): string
    {
        $sth = $this->connection->prepare("
            insert into invoice_items
            (invoice_id, service, service_description, price)
            values (:invoice_id, :service, :service_description, :price)
          ");
        $sth->execute([
            "invoice_id" => $data["invoice_id"],
            "service" => $data["service"],
            "service_description" => $data["service_description"],
            "price" => $data["price"],
        ]);

        return $this->connection->lastInsertId();
    }

    public function updateInvoiceItem($data): int
    {
        $sth = $this->connection->prepare("
            update
              invoice_items
            set
              invoice_id=:invoice_id,
              service=:service,
              service_description=:service_description,
              price=:price
            where
              id=:id
          ");
        $sth->execute([
            "invoice_id" => $data["invoice_id"],
            "service" => $data["service"],
            "service_description" => $data["service_description"],
            "price" => $data["price"],
            "id" => $data["id"],
        ]);

        return $sth->rowCount();
    }

    public function deleteInvoiceItem($data): int
    {
        $sth = $this->connection->prepare("
            delete
            from
              invoice_items
              where
              id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
        ]);

        return $sth->rowCount();
    }
}
