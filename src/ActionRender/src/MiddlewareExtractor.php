<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 11:39
 */

namespace rollun\actionrender;

use Interop\Container\ContainerInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

class MiddlewareExtractor
{

    private $container;

    /**
     * MiddlewareFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Can the factory create an middleware instance for the service?
     *
     * @param  string $requestedName
     * @return bool
     */
    public function canExtract($requestedName)
    {
        if ($this->container->has($requestedName)) {
            return true;
        }
        return false;
    }

    /**
     * Create an middleware
     *
     * @param  string $requestedName
     * @param  null|array $options
     * @return ServerMiddlewareInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function extract($requestedName, array $options = null)
    {
        $service = $this->container->get($requestedName);
        if (is_a($service, ServerMiddlewareInterface::class, true)
            || is_callable($service)
        ) {
            return $service;
        }
        throw new ServiceNotCreatedException("Service $requestedName is not middleware.");
    }

    public function callFactory($factoryClass, $requestedName, $options)
    {
        $factory = new $factoryClass();
        $service = $factory($this->container, $requestedName, $options);
        if (is_a($service, ServerMiddlewareInterface::class, true)) {
            return $service;
        }
        throw new ServiceNotCreatedException("Service $requestedName is not middleware.");
    }
}
