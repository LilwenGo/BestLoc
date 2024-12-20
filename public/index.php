<?php
require_once '../vendor/autoload.php';

use BestLoc\Controller\BillingController;
use BestLoc\Controller\UserController;
use BestLoc\Middleware\JSONBodyParserMiddleware;
use BestLoc\Middleware\JWTMiddleware;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(new JSONBodyParserMiddleware());
// Initialisations
$billingController = new BillingController();
$userController = new UserController();
// Routes
$app->post('/register', [$userController, 'register']);
$app->post('/login', [$userController, 'login']);

$app->group('/billings', function ($group) use ($billingController): void {
    $group->get('', [$billingController, 'getAll']);
    $group->get('/{id}', [$billingController, 'find']);
    $group->get('/contract/{id}', [$billingController, 'getByContract']);
    $group->post('', [$billingController, 'create']);
    $group->put('/{id}', [$billingController, 'update']);
    $group->delete('/{id}', [$billingController, 'delete']);
})->add(JWTMiddleware::getInstance());
$app->run();