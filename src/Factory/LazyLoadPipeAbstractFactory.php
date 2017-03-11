<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 16:27
 */

namespace rollun\actionrender\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\actionrender\LazyLoadPipe;
use rollun\actionrender\MiddlewareExtractor;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class LazyLoadPipeAbstractFactory implements AbstractFactoryInterface
{
    const KEY = 'LazyLoadPipe';

    const KEY_LAZY_LOAD_MIDDLEWARE_GETTER_SERVICE = 'lazyLoadMiddlewareGetter';

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
        return isset($config[static::KEY][$requestedName]);
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
        if(is_string($config[static::KEY][$requestedName])) {
            if($container->has($config[static::KEY][$requestedName])) {
                $middlewareGetter = $container->get($config[static::KEY][$requestedName]);
                $middlewareExtractor = new MiddlewareExtractor($container);
            } else {
                throw new ServiceNotFoundException($config[static::KEY][$requestedName] . " service not found.");
            }
        } else {
           //TODO: for more options...
            $middlewareGetter = '';
            $middlewareExtractor = '';
        }
        $lazyLoadPipe = new LazyLoadPipe($middlewareGetter, $middlewareExtractor, $requestedName);
        return $lazyLoadPipe;
    }
}
