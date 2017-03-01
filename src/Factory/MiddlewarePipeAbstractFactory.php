<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 15:26
 */

namespace rollun\actionrender\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\actionrender\AbstractMiddlewarePipe;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MiddlewarePipeAbstractFactory implements AbstractFactoryInterface
{

    const KEY_AMP = 'MiddlewarePipeAbstract';

    protected $middlewares;

    const KEY_MIDDLEWARES = 'middlewares';

    public function __construct(array $middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $middlewares = $config[static::KEY_AMP][$requestedName][static::KEY_MIDDLEWARES];
        foreach ($middlewares as $key => $middleware) {
            if ($container->has($middleware)) {
                $this->middlewares[$key] = $container->get($middleware);
            } else {
                throw new ServiceNotFoundException("$middleware not found in Container");
            }
        }

        ksort($this->middlewares);
        return new AbstractMiddlewarePipe($this->middlewares);
    }

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
        if (isset($config[static::KEY_AMP][$requestedName][static::KEY_MIDDLEWARES])) {
            return true;
        }
        return false;
    }
}
