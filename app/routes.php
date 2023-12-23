<?php

declare (strict_types = 1);

use App\Application\Actions\Admin\AdminLoginAction;
use App\Application\Actions\Invoice\GetInvoiceAction;
use App\Application\Actions\Invoice\GetInvoiceItemAction;
use App\Application\Actions\Invoice\InvoiceCreateAction;
use App\Application\Actions\Invoice\InvoiceDeleteAction;
use App\Application\Actions\Invoice\InvoiceDetailsAction;
use App\Application\Actions\Invoice\InvoiceItemCreateAction;
use App\Application\Actions\Invoice\InvoiceItemDeleteAction;
use App\Application\Actions\Invoice\InvoiceItemUpdateAction;
use App\Application\Actions\Invoice\InvoicesAction;
use App\Application\Actions\Invoice\InvoiceUpdateAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(date("Y-m-d h:m:s"));
        return $response;
    });

    $app->group('/admin', function (Group $group) {
        $group->post('/login', AdminLoginAction::class);
    });

    $app->group('/invoices', function (Group $group) {
        $group->post('/', InvoicesAction::class);
        $group->post('/get-invoice', GetInvoiceAction::class);
        $group->post('/details', InvoiceDetailsAction::class);
        $group->post('/create', InvoiceCreateAction::class);
        $group->post('/update', InvoiceUpdateAction::class);
        $group->post('/delete', InvoiceDeleteAction::class);
        $group->post('/item', GetInvoiceItemAction::class);
        $group->post('/item-create', InvoiceItemCreateAction::class);
        $group->post('/item-update', InvoiceItemUpdateAction::class);
        $group->post('/item-delete', InvoiceItemDeleteAction::class);
    });

    $app->get('/db-test', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);
        $sth = $db->prepare("SELECT admin_id FROM admins");
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });
};
