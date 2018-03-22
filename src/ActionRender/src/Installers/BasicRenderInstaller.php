<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.03.17
 * Time: 11:32
 */

namespace rollun\actionrender\Installers;

use rollun\actionrender\Factory\LazyLoadMiddlewareAbstractFactory;
use rollun\actionrender\Factory\MiddlewarePipeAbstractFactory;
use rollun\actionrender\MiddlewareDeterminator\Factory\AbstractMiddlewareDeterminatorAbstractFactory;
use rollun\actionrender\MiddlewareDeterminator\HeaderSwitch;
use rollun\actionrender\MiddlewareDeterminator\Installers\HeaderSwitchInstaller as HeaderSwitchMiddlewareDeterminatorInstaller;
use rollun\actionrender\MiddlewareDeterminator\Factory\HeaderSwitchAbstractFactory as HeaderSwitchMiddlewareDeterminatorAbstractFactory;
use rollun\actionrender\MiddlewarePluginManager;
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
            AbstractMiddlewareDeterminatorAbstractFactory::KEY => [
                'simpleHtmlJsonRenderer' => [
                    HeaderSwitchMiddlewareDeterminatorAbstractFactory::KEY_CLASS => HeaderSwitch::class,
                    HeaderSwitchMiddlewareDeterminatorAbstractFactory::KEY_NAME=> "Accept",
                    HeaderSwitchMiddlewareDeterminatorAbstractFactory::KEY_MIDDLEWARE_MATCHING => [
                        '/application\/json/' => JsonRendererAction::class,
                        '/text\/html/' => 'htmlReturner'
                    ],
                ],
            ],
            LazyLoadMiddlewareAbstractFactory::KEY => [
                'simpleHtmlJsonRendererLLPipe' => [
                    LazyLoadMiddlewareAbstractFactory::KEY_MIDDLEWARE_PLUGIN_MANAGER => MiddlewarePluginManager::class,
                    LazyLoadMiddlewareAbstractFactory::KEY_MIDDLEWARE_DETERMINATOR => "simpleHtmlJsonRenderer"
                ]
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
                $description = "Позволяет использовать базовый render middleware, который отдает данные на основании ожидаемого ответа.";
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
            HeaderSwitchMiddlewareDeterminatorInstaller::class,
            LazyLoadMiddlewareInstaller::class,
            MiddlewarePipeInstaller::class,
        ];
    }


}
