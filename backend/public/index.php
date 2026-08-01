<?php

use Dotenv\Dotenv;
use App\Helpers\Error;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$router = new AltoRouter();


$routesConfig = [
    // PUBLIC
    [
        'prefix' => '',
        'middlewares' => [],
        'routes' => []
    ],
    // AUTH
    [
        'prefix' => '',
        'middlewares' => ['AuthMiddleware'],
        'routes' => [
            ['GET', '/decks', 'AdminController#decks', 'decks'],
            ['POST', '/log-out', 'AuthController#logout', 'logout'],
        ]
    ],
    // ADMIN
    [
        'prefix' => '/admin',
        'middlewares' => ['AuthMiddleware', 'AdminMiddleware'],
        'routes' => [
            ['GET', '/users', 'AdminController#users', 'users'],
        ]
    ]
];

$globalPrefix = '/api/v1';

foreach ($routesConfig as $group) {
    foreach ($group['routes'] as $route) {
        [$method, $url, $target, $name] = $route;

        $fullUrl = rtrim($globalPrefix . $group['prefix'] . $url, '/');
        $fullUrl = $fullUrl === '' ? '/' : $fullUrl;

        $router->map($method, $fullUrl, [
            'target' => $target,
            'middlewares' => $group['middlewares']
        ], $name);
    }
}

$match = $router->match();

if (is_array($match)) {
    $routeData = $match['target'];

    if (isset($routeData['middlewares']) && is_array($routeData['middlewares'])) {
        foreach ($routeData['middlewares'] as $middleware) {
            $middlewareClass = "App\\Middlewares\\$middleware";

            if (class_exists($middlewareClass)) {
                $middlewareInstance = new $middlewareClass();
                $middlewareInstance->handle();
            }
        }
    }

    [$controller, $action] = explode('#', $routeData['target']);
    $controllerName = "App\\Controllers\\$controller";

    if (class_exists($controllerName)) {
        $obj = new $controllerName();

        if (is_callable([$obj, $action])) {
            header('Content-Type: application/json; charset=utf-8');
            call_user_func_array([$obj, $action], $match['params']);
        } else {
            Error::sendJsonError("La méthode $action n'existe pas dans $controllerName", 500);
        }
    } else {
        Error::sendJsonError("Le contrôleur $controllerName n'existe pas", 500);
    }
} else {
    Error::sendJsonError('Endpoint introuvable', 404);
}
