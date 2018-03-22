<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 15:31
 */

namespace rollun\actionrender\MiddlewareDeterminator\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\actionrender\MiddlewareDeterminator\ResponseRenderer;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class ResponseRendererAbstractFactory extends AbstractLazyLoadMiddlewareGetterAbstractFactory
{
    const EXTENDER_CLASS = ResponseRenderer::class;

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $factoryConfig = $config[static::KEY][$requestedName];
        $class = $factoryConfig[static::KEY_CLASS];
        return new $class($factoryConfig[static::KEY_MIDDLEWARE]);
    }
}
