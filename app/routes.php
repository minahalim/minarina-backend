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
use App\Application\Actions\Invoice\SendEmailAction;
use App\Application\Actions\Product\GetProductAction;
use App\Application\Actions\Product\ProductActivateAction;
use App\Application\Actions\Product\ProductCreateAction;
use App\Application\Actions\Product\ProductDeleteAction;
use App\Application\Actions\Product\ProductsAction;
use App\Application\Actions\Product\ProductUpdateAction;
use App\Application\Actions\Product\ProductUploadAction;
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
        $group->post('/email', SendEmailAction::class);
    });

    $app->group('/products', function (Group $group) {
        $group->post('/', ProductsAction::class);
        $group->post('/get-product', GetProductAction::class);
        $group->post('/create', ProductCreateAction::class);
        $group->post('/update', ProductUpdateAction::class);
        $group->post('/delete', ProductDeleteAction::class);
        $group->post('/activate', ProductActivateAction::class);
        $group->post('/upload', ProductUploadAction::class);
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
