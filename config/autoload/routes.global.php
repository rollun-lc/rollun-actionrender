<?php

use rollun\actionrender\Example\NamedMiddleware;
use rollun\actionrender\Example\NamedMiddlewareAbstractFactory;

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
    ],

    'routes' => [
        [
            'name' => 'home',
            'path' => '/test[/{name}]',
            'middleware' => 'test-service',
            'allowed_methods' => ['GET'],
        ],
       /* [
            'name' => 'home-page',
            'path' => '/[{name}]',
            'middleware' => 'home-service',
            'allowed_methods' => ['GET'],
        ],*/
    ],
];
