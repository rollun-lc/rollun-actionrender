<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 12:03
 */

namespace rollun\actionrender\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\actionrender\ActionRenderMiddleware;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class ActionRenderAbstractFactory implements AbstractFactoryInterface
{
    const KEY_AR_SERVICE = 'ActionRenderService';

    const KEY_AR_MIDDLEWARE = 'ARMiddleware';

    const KEY_ACTION_MIDDLEWARE_SERVICE = 'ActionMiddlewareService';

    const KEY_RENDER_MIDDLEWARE_SERVICE = 'RenderMiddlewareService';


    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');
        if (isset($config[static::KEY_AR_SERVICE][$requestedName][static::KEY_AR_MIDDLEWARE])) {
            $middleware = $config[static::KEY_AR_SERVICE][$requestedName][static::KEY_AR_MIDDLEWARE];
            return (
                is_array($middleware) &&
                isset($middleware[static::KEY_ACTION_MIDDLEWARE_SERVICE]) &&
                isset($middleware[static::KEY_RENDER_MIDDLEWARE_SERVICE]));
        }
        return false;
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $middleware = $config[static::KEY_AR_SERVICE][$requestedName][static::KEY_AR_MIDDLEWARE];
        $action = $middleware[static::KEY_ACTION_MIDDLEWARE_SERVICE];
        $render = $middleware[static::KEY_RENDER_MIDDLEWARE_SERVICE];

        if ($container->has($action) && $container->has($render)) {
            return new ActionRenderMiddleware($container->get($action), $container->get($render));
        }
        $errorStr = "Not found ";
        $errorStr .= !$container->has($action) ? $action . " " : "";
        $errorStr .= !$container->has($render) ? $render . " " : "";
        throw new ServiceNotCreatedException($errorStr . "for service");
    }
}
