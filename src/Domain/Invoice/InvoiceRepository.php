<?php

declare (strict_types = 1);

namespace App\Domain\Invoice;

use DateTime;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;

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
            insert into invoices (name, address, email, invoice_date) values (:name, :address, :email, :invoice_date)
          ");
        $sth->execute([
            "name" => $data['name'],
            "address" => $data['address'],
            "email" => $data['email'],
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
            email=:email,
            invoice_date=:invoice_date
            where id=:id
          ");
        $sth->execute([
            "id" => $data["id"],
            "name" => $data["name"],
            "address" => $data["address"],
            "email" => $data['email'],
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
            select
                invoice_items.id,
                service,
                service_description,
                price,
                invoice_id,
                invoices.name,
                invoices.address,
                invoices.email,
                DATE(invoices.invoice_date) as invoice_date
            from invoice_items
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

    public function sendEmail($data): array
    {
        $invoice_data = $this->getInvoiceDetails(["id" => $data["invoiceId"]]);
        $invoice_id = $invoice_data[0]['invoice_id'];
        $address = $invoice_data[0]['address'];
        $name = $invoice_data[0]['name'];
        $invoice_date = $invoice_data[0]['invoice_date'];

        $mail_to = $invoice_data[0]['email'];

        $date = DateTime::createFromFormat("Y-m-d", $invoice_date);
        $year = $date->format("Y");

        $invoice_item_body = "";
        $subtotal = 0;

        foreach ($invoice_data as $invoice_item) {
            $service = $invoice_item['service'];
            $service_description = $invoice_item['service_description'];
            $price = $invoice_item['price'];

            $invoice_item_body .= "
                <tr key={invoice.id}>
                    <td style='border: 1px solid black;' className='p-2 border'>$service</td>
                    <td style='border: 1px solid black; white-space: pre-wrap;' className='p-2 border'>$service_description</td>
                    <td style='border: 1px solid black;' className='p-2 border'>$price</td>
                </tr>
            ";

            $subtotal += $price;
        }

        $subtotal = number_format((float) $subtotal, 2, '.', '');

        $hst = number_format((float) $subtotal * 0.13, 2, '.', '');

        $total = $subtotal + $hst;

        $mail_body = <<<EOD
        <html>
            <body>
                <div className='mt-4'>
                    <div className='flex justify-between'>
                        <img src="https://api.minarina.com/logo-construction.png" alt='logo' style="width:200px" />
                    </div>
                <div className='mb-4'>Minarina CO</div>
                <br />
                <div  style="display:flex; width:100%; justify-content:space-between;">
                    <table  style="width:50%">
                        <tbody className='border'>
                            <tr className='border'>
                                <td style="border: 1px solid black;" className='p-2 border w-20'>Address</td>
                                <td style="border: 1px solid black;" className='p-2 border'>44 Gurr Cres, Ajax, ON, L1T 2P2</td>
                            </tr>
                            <tr className='border'>
                                <td style="border: 1px solid black;" className='p-2 border'>Phone</td>
                                <td style="border: 1px solid black;" className='p-2 border'>289-987-1643</td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                    <table  style="width:50%">
                        <tbody className='border'>
                            <tr className='border'>
                                <td style="border: 1px solid black;" className='p-2 border w-20'>Invoice #</td>
                                <td style="border: 1px solid black;" className='p-2 border'>{$year}000{$invoice_id}</td>
                            </tr>
                            <tr className='border'>
                                <td style="border: 1px solid black;" className='p-2 border'>Date</td>
                                <td style="border: 1px solid black;" className='p-2 border'>$invoice_date</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black;">HST #</td>
                                <td style="border: 1px solid black;">
                                745302885 <br /> RT0001
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                </div>
                <br />
                <table  style="width:100%">
                    <tbody className='border'>
                        <tr className='border'>
                            <td style="border: 1px solid black;" className='p-2 border w-20'>To</td>
                            <td style="border: 1px solid black;" className='p-2 border'>$name</td>
                        </tr>
                        <tr className='border'>
                            <td style="border: 1px solid black;" className='p-2 border'>Address</td>
                            <td style="border: 1px solid black;" className='p-2 border'>$address</td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <table style="width:100%">
                    <thead>
                        <tr className='bg-gray-100 text-left'>
                            <th className='p-2 border'>Service</th>
                            <th className='p-2 border'>Description</th>
                            <th className='p-2 border'>price</th>
                        </tr>
                    </thead>
                    <tbody>
                        $invoice_item_body
                        <tr>
                            <td style="border: 1px solid black;" colSpan="2" className='p-2 text-right'>
                                Subtotal
                            </td>
                            <td style="border: 1px solid black;" className='border p-2'>$subtotal</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black;" colSpan="2" className='p-2 text-right'>
                                HST (ON) @ 13%
                            </td>
                            <td style="border: 1px solid black;" className='border p-2'>$hst</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black;" colSpan="2" className='p-2 text-right'>
                                Total
                            </td>
                            <td style="border: 1px solid black;" className='border p-2'>$$total</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black;" colSpan="2" className='p-2 text-right'>
                                Payment
                            </td>
                            <td style="border: 1px solid black;" className='border p-2'>$$total</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </body>
            </html>
        EOD;

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.titan.email';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'info@minarina.com';
        $mail->Password = 'mino20032!';
        $mail->setFrom('info@minarina.com', 'Minarina Co');
        $mail->addAddress($mail_to, $name);
        $mail->addBCC('mn_halim@yahoo.com');
        $mail->Subject = 'Your invoice from Minarina CO';

        $mail->msgHTML($mail_body);
        if (!$mail->send()) {
            return ["msg:" => $mail->ErrorInfo];
        } else {
            return ["msg:" => "the email was sent"];
        }
    }
}
