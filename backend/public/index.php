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
        'routes' => [
            ['POST', '/register', 'AuthController#register', 'register'],
            ['POST', '/login', 'AuthController#login', 'login'],
            ['POST', '/logout', 'AuthController#logout', 'logout'],

        ]
    ],
    // AUTH DECK
    [
        'prefix' => '/decks',
        'middlewares' => ['AuthMiddleware'],
        'routes' => [
            ['GET', '/', 'DeckController#index', 'get-all-decks'],
            ['GET', '/show/[int:id]', 'DeckController#show', 'get-one-deck'],
            ['POST', '/create', 'DeckController#create', 'create-deck'],
            ['PUT', '/edit/[int:id]', 'DeckController#edit', 'edit-deck'],
            ['DELETE', '/remove/[int:id]', 'DeckController#remove', 'remove-deck'],
        ]
    ],
    // AUTH CARD
    [
        'prefix' => '/cards',
        'middlewares' => ['AuthMiddleware'],
        'routes' => [
            ['GET', '/', 'CardController#index', 'get-all-cards'],
            ['GET', '/deck/[int:id]', 'CardController#index', 'get-cards-by-deck'],
            ['POST', '/create', 'CardController#create', 'create-card'],
            ['PUT', '/edit/[int:id]', 'CardController#edit', 'edit-card'],
            ['DELETE', '/remove/[int:id]', 'CardController#remove', 'remove-card'],
        ]
    ],
    // AUTH REVIEW
    [
        'prefix' => '/reviews',
        'middlewares' => ['AuthMiddleware'],
        'routes' => [
            ['GET', '/today', 'ReviewController#today', 'get-today-reviews'],
            ['POST', '/[int:id]', 'ReviewController#review', 'submit-review'],
        ]
    ],
    // ADMIN
    [
        'prefix' => '/admin',
        'middlewares' => ['AuthMiddleware', 'AdminMiddleware'],
        'routes' => [
            ['GET', '/', 'UserController#index', 'get-all-users'],
            ['GET', '/show[int:id]', 'UserController#show', 'get-one-user'],
            ['PUT', '/edit[int:id]', 'UserController#edit', 'edit-user'],
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
            Error::sendError("La méthode $action n'existe pas dans $controllerName", 500);
        }
    } else {
        Error::sendError("Le contrôleur $controllerName n'existe pas", 500);
    }
} else {
    Error::sendError('Endpoint introuvable', 404);
}
