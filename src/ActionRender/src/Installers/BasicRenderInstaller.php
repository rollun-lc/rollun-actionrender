<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.03.17
 * Time: 11:32
 */

namespace rollun\actionrender\Installers;

use rollun\actionrender\Factory\ActionRenderAbstractFactory;
use rollun\actionrender\Factory\LazyLoadPipeAbstractFactory;
use rollun\actionrender\Factory\MiddlewarePipeAbstractFactory;
use rollun\actionrender\LazyLoadMiddlewareGetter\Factory\AbstractLazyLoadMiddlewareGetterAbstractFactory;
use rollun\actionrender\LazyLoadMiddlewareGetter\Factory\ResponseRendererAbstractFactory;
use rollun\actionrender\LazyLoadMiddlewareGetter\ResponseRenderer;
use rollun\actionrender\Renderer\Html\HtmlParamResolver;
use rollun\actionrender\Renderer\Html\HtmlRendererAction;
use rollun\actionrender\Renderer\Html\HtmlRendererFactory;
use rollun\actionrender\Renderer\Json\JsonRendererAction;
use rollun\actionrender\ReturnMiddleware;
use rollun\installer\Install\InstallerAbstract;

class BasicRenderInstaller extends InstallerAbstract
{

    /**
     * install
     * @return array
     */
    public function install()
    {
        $dependencyConfig =  [
            'dependencies' => [
                'abstract_factories' => [
                    ResponseRendererAbstractFactory::class,
                ],
                'invokables' => [
                    HtmlParamResolver::class => HtmlParamResolver::class,
                    JsonRendererAction::class => JsonRendererAction::class,
                    ReturnMiddleware::class => ReturnMiddleware::class
                ],
                'factories' => [
                    HtmlRendererAction::class => HtmlRendererFactory::class
                ],
            ],
        ];
        $renderConfig = [
            MiddlewarePipeAbstractFactory::KEY => [
                'htmlReturner' => [
                    MiddlewarePipeAbstractFactory::KEY_MIDDLEWARES => [
                        HtmlParamResolver::class,
                        HtmlRendererAction::class
                    ]
                ]
            ],
            AbstractLazyLoadMiddlewareGetterAbstractFactory::KEY => [
                'simpleHtmlJsonRenderer' => [
                    ResponseRendererAbstractFactory::KEY_MIDDLEWARE => [
                        '/application\/json/' => JsonRendererAction::class,
                        '/text\/html/' => 'htmlReturner'
                    ],
                    ResponseRendererAbstractFactory::KEY_CLASS => ResponseRenderer::class,
                ],
            ],

            LazyLoadPipeAbstractFactory::KEY => [
                'simpleHtmlJsonRendererLLPipe' => 'simpleHtmlJsonRenderer'
            ],
        ];
        $config =  array_merge($dependencyConfig, $renderConfig);
        return $config;
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {

    }

    /**
     * Return string with description of installable functional.
     * @param string $lang ; set select language for description getted.
     * @return string
     */
    public function getDescription($lang = "en")
    {
        switch ($lang) {
            case "ru":
                $description = "Позволяет использовать базовый рендер.";
                break;
            default:
                $description = "Does not exist.";
        }
        return $description;
    }

    public function isInstall()
    {
        $config = $this->container->get('config');
        return (
            isset($config['dependencies']['abstract_factories']) &&
            in_array(ResponseRendererAbstractFactory::class, $config['dependencies']['abstract_factories']) &&
            isset($config['dependencies']['invokables'][HtmlParamResolver::class]) &&
            isset($config['dependencies']['invokables'][JsonRendererAction::class]) &&
            isset($config['dependencies']['invokables'][ReturnMiddleware::class]) &&
            isset($config['dependencies']['invokables']) &&
            $config['dependencies']['invokables'][HtmlParamResolver::class] === HtmlParamResolver::class &&
            $config['dependencies']['invokables'][JsonRendererAction::class] === JsonRendererAction::class &&
            $config['dependencies']['invokables'][ReturnMiddleware::class] === ReturnMiddleware::class &&
            isset($config['dependencies']['factories'][HtmlRendererAction::class]) &&
            $config['dependencies']['factories'][HtmlRendererAction::class] === HtmlRendererFactory::class
        );
    }

    public function getDependencyInstallers()
    {
        return [
            LazyLoadPipeInstaller::class,
            MiddlewarePipeInstaller::class,
        ];
    }


}
