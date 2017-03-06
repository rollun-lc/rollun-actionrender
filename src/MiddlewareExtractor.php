<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 11:39
 */

namespace rollun\actionrender;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Http\Middleware\MiddlewareInterface;
use rollun\actionrender\RuntimeException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

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
        if($this->container->has($requestedName)) {
            return true;
        }
        return false;
    }

    /**
     * Create an middleware
     *
     * @param  string $requestedName
     * @param  null|array $options
     * @return \Zend\Stratigility\MiddlewareInterface|\Interop\Http\Middleware\MiddlewareInterface
     * @throws RuntimeException if any other error occurs
     */
    public function extract($requestedName, array $options = null)
    {
        $service = $this->container->get($requestedName);
        if(is_a($service, \Zend\Stratigility\MiddlewareInterface::class, true)
            || is_a($service, \Interop\Http\Middleware\MiddlewareInterface::class, true)) {
            return $service;
        }
        throw new RuntimeException("Service $requestedName is not middleware.");
    }
}
