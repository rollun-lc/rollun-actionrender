<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.02.17
 * Time: 14:08
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

class LazyLoadSwitchAbstractFactory extends AbstractLazyLoadAbstractFactory
{
    const KEY_MIDDLEWARES_SERVICE = 'middlewares';

    const KEY_ATTRIBUTE_NAME = 'attributeName';

    const DEFAULT_ATTRIBUTE_NAME = 'switchArray';

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
        $factoryConfig = $config[static::KEY_LAZY_LOAD_SWITCH][$requestedName];
        $lazyLoadFactory =
            function (Request $request, Response $response, callable $out = null) use ($factoryConfig, $container, $requestedName) {
                $isFound = false;
                $attributeValues = $request->getAttribute($factoryConfig[static::KEY_ATTRIBUTE_NAME]);
                $attributeValues = $attributeValues ?: $request->getAttribute(static::DEFAULT_ATTRIBUTE_NAME);
                $middlewarePipe = new MiddlewarePipe();
                if (is_null($attributeValues) || !is_array($attributeValues)) {
                    throw new ServiceNotCreatedException("Attribute '" . $factoryConfig[static::KEY_ATTRIBUTE_NAME] . "' values not valid.");
                }
                foreach ($factoryConfig[static::KEY_MIDDLEWARES_SERVICE] as $expectedAttributeValue => $middlewareService) {
                    if (in_array($expectedAttributeValue, $attributeValues)) {
                        if ($container->has($middlewareService)) {
                            $middleware = $container->get($middlewareService);
                            $middlewarePipe->pipe($middleware);
                        } else {
                            throw new ServiceNotFoundException("Not found $middlewareService for $expectedAttributeValue expectedAttributeValue.");
                        }
                    }
                }
                if ($isFound) {
                    return $middlewarePipe($request, $response, $out);
                }
                throw new ServiceNotCreatedException("Not found middleware for request.");
            };
        return $lazyLoadFactory;
    }
}
