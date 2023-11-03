<?php declare(strict_types= 1);

use DI\Container;
use DI\ContainerBuilder;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\PhpRenderer;

require_once __DIR__ . "/../vendor/autoload.php";
// start session
session_start();
// new up container builder
$containerBuilder = new ContainerBuilder();

// add dependencies to the Dependency Injection Container
$containerBuilder->addDefinitions([
	'db.settings' => [
		'path'   => "/home/ashoe/Projects/slim-demo/db.sqlite",
		'driver'   => 'pdo_sqlite',
	],
	Connection::class => function (Container $container) {
		$config = new Configuration();
		$connectionParams = $container->get('db.settings');
		return DriverManager::getConnection($connectionParams, $config);	
	},
	PhpRenderer::class => function () {
	return new PhpRenderer(dirname(__DIR__). '/src/pages');
	},
	Guard::class => fn (ResponseFactory $factory) => new Guard($factory),
]);

$container = $containerBuilder->build();
// Use Slim Bridge to stream line the Slim App and reduce boilerplate with the Container
$app = DI\Bridge\Slim\Bridge::create($container);

// Add Application Middleware
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(Guard::class);
$app->add(function ($request, $handler) {
	$response = $handler->handle($request);
	$csp = "script-src 'self' 'unsafe-inline' https://unpkg.com https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline'; connect-src 'self';"; // Modify as necessary
	return $response->withHeader('Content-Security-Policy', $csp);
});

$routes = require_once dirname(__DIR__).'/src/routes.php';
$routes($app);

// Fire it up
$app->run();
