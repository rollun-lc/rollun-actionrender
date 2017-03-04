<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 17:52
 */

namespace rollun\actionrender\Factory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class LazyLoadResponseRendererAbstractFactory implements AbstractFactoryInterface
{
    const KEY_ATTRIBUTE_RESPONSE_DATA = 'responseData';

    const KEY_ACCEPT_TYPE_PATTERN = 'accept_types_pattern';

    const KEY = 'lazy_load_response_render';
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
        $config = $container->get('config')[static::KEY][$requestedName];

        $dynamicResponseReturner =
            function (Request $request, Response $response, callable $next = null) use ($container, $config) {
                $accept = $request->getHeaderLine('Accept');

                foreach ($config[static::KEY_ACCEPT_TYPE_PATTERN] as $acceptTypePattern => $responseMiddleware) {
                    if (preg_match($acceptTypePattern, $accept)) {
                        if (!$container->has($responseMiddleware)) {
                            throw new ServiceNotFoundException("$responseMiddleware not found!");
                        }
                        $responseMiddleware = $container->get($responseMiddleware);
                        return $responseMiddleware($request, $response, $next);
                    }
                }

                throw new ServiceNotCreatedException("ResponseRenderer for '$accept' not set!");
            };

        return $dynamicResponseReturner;
    }
}
