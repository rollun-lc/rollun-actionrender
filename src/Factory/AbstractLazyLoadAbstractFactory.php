<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.03.17
 * Time: 18:42
 */

namespace rollun\actionrender\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\Stratigility\MiddlewarePipe;

abstract class AbstractLazyLoadAbstractFactory implements AbstractFactoryInterface
{
    const KEY = 'lazyLoad';

    /** LazyLoadFactory Type */
    const KEY_TYPE = 'type';

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
        return isset($config[static::KEY][$requestedName]) &&
            strcmp($config[static::KEY][static::KEY_TYPE], static::class) === 0;
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $factoryConfig = $config[static::KEY][$requestedName];

        $lazyLoadMiddleware =
            function (Request $request, Response $response, callable $out = null)
            use ($container, $requestedName, $factoryConfig) {
                return $this->lazyLoadMiddleware($request, $response, $container, $requestedName, $factoryConfig);
            };
        return $lazyLoadMiddleware;
    }

    abstract protected function lazyLoadMiddleware(Request $request, Response $response, ContainerInterface $container, $requestedName, array $factoryConfig);
}
