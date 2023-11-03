<?php declare(strict_types= 1);

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Views\PhpRenderer;

return function(App $app) {
$app->get("/", function (ResponseInterface $response, Connection $db, ServerRequestInterface $request, PhpRenderer $view, Guard $csrf): ResponseInterface
{
	$qb = $db->createQueryBuilder();

	$qb->select('*')->from('users')->where('name = ?')->setParameter(0, 'Andrew');
	$stmt = $qb->executeQuery();
	$user = $stmt->fetchAssociative();
	$nameKey = $csrf->getTokenNameKey();
	$valueKey = $csrf->getTokenValueKey();
	$name = $request->getAttribute($nameKey);
	$value = $request->getAttribute($valueKey);
	$username = $user['name'] ?? 'Guest';
	$view->setLayout('layout.php');

	 return $view->render($response, "name.php", [
		'username' => $username,
		'nameKey'  => $nameKey,
		'valueKey' => $valueKey,
		'name'     => $name,
		'value'   => $value
	]);
});

$app->get('/about', function (ResponseInterface $response, PhpRenderer $view): ResponseInterface {
	$view->setLayout('layout.php');
	return $view->render($response, "about.php");
});

$app->get('/testme', function (ResponseInterface $response): ResponseInterface {
	$script = <<<JS
	" onmouseover="alert(/bad stuff!/)	
	JS;
	$badStuff = html($script);

	$html = <<<HTML
		<h2>Thanks For Clicking</h2>
		<span title='$badStuff'>Does it work?</span>
	HTML;
	$response->getBody()->write($html);
	return $response;
});

$app->post('/checkpost', function(ResponseInterface $response) {
    header('location: /');  
    return $response
  ->withHeader('Location', '/')
  ->withStatus(302);
});

};