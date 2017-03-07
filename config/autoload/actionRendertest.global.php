<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 17:32
 */

use rollun\actionrender\Example\NamedMiddlewareAbstractFactory;
use rollun\actionrender\Factory\ActionRenderAbstractFactory;
use rollun\actionrender\Factory\LazyLoadPipeAbstractFactory;
use rollun\actionrender\Factory\MiddlewarePipeAbstractFactory;
use rollun\actionrender\LazyLoadMiddlewareGetter\Factory\AbstractLazyLoadMiddlewareGetterAbstractFactory;
use rollun\actionrender\LazyLoadMiddlewareGetter\Factory\AttributeAbstractFactory;
use rollun\actionrender\LazyLoadMiddlewareGetter\Factory\ResponseRendererAbstractFactory;

return [
    'dependencies' => [
        'invokables' => [
            \rollun\actionrender\Example\Api\HelloAction::class => \rollun\actionrender\Example\Api\HelloAction::class,
            \rollun\actionrender\ReturnMiddleware::class => \rollun\actionrender\ReturnMiddleware::class,
        ],
        "factories" => [

        ],
        "abstract_factories" => [
            NamedMiddlewareAbstractFactory::class,
            AttributeAbstractFactory::class
        ],
    ],
    NamedMiddlewareAbstractFactory::KEY => [
        'Simple1[test-service]',
        'Simple2[test-service]',
        'Simple3[test-service]',
        'Simple4[test-service]',

        'Temp[Simple1[test-service]]',
        'Temp[Simple2[test-service]]',

        'Simple1[test2-service]',
        'Temp[Simple1[test2-service]]',

        'Simple1[test3-service]',
        'Temp[Simple1[test3-service]]',
    ],

    /* ActionRenderAbstractFactory::KEY => [
         'home-service' => [
             ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE => \rollun\actionrender\Example\Api\HelloAction::class,
             ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'simpleHtmlJsonRenderer'
         ],
     ],*/

    MiddlewarePipeAbstractFactory::KEY => [
        'test-service' => [
            MiddlewarePipeAbstractFactory::KEY_MIDDLEWARES => [
                'Simple1[test-service]',
                'simpleAttributeLLPipe',
                'Simple2[test-service]',
                'simpleAttributeLLPipe',
                'Simple3[test-service]',
                'Simple4[test-service]',
                'test2-service',
                \rollun\actionrender\ReturnMiddleware::class,
            ]
        ],
        'test2-service' => [
            MiddlewarePipeAbstractFactory::KEY_MIDDLEWARES => [
                'Simple1[test2-service]',
                'simpleAttributeLLPipe2',
                'test3-service',
            ]
        ],
        'test3-service' => [
            MiddlewarePipeAbstractFactory::KEY_MIDDLEWARES => [
                'Simple1[test3-service]',
                'simpleAttributeLLPipe3',
            ]
        ]
    ],

    AbstractLazyLoadMiddlewareGetterAbstractFactory::KEY => [
        'simpleAttribute' => [
            AttributeAbstractFactory::KEY_CLASS => \rollun\actionrender\LazyLoadMiddlewareGetter\Attribute::class,
            AttributeAbstractFactory::KEY_ATTRIBUTE_NAME => 'temp',
        ]
    ],

    LazyLoadPipeAbstractFactory::KEY => [
        'simpleAttributeLLPipe' => 'simpleAttribute',
        'simpleAttributeLLPipe2' => 'simpleAttribute',
        'simpleAttributeLLPipe3' => 'simpleAttribute'
    ],
];