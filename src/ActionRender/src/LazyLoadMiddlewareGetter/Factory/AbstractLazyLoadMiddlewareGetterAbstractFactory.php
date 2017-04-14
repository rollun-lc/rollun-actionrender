<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 17:04
 */

namespace rollun\actionrender\LazyLoadMiddlewareGetter\Factory;

use Interop\Container\ContainerInterface;
use rollun\actionrender\Interfaces\LazyLoadMiddlewareGetterInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

abstract class AbstractLazyLoadMiddlewareGetterAbstractFactory implements AbstractFactoryInterface
{
    const KEY = 'LazyLoadMiddlewareGetter';

    const KEY_CLASS = 'class';

    const KEY_ATTRIBUTE_NAME = 'attributeName';

    const KEY_MIDDLEWARE = 'middleware';

    const EXTENDER_CLASS = LazyLoadMiddlewareGetterInterface::class;

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
        return isset($config[static::KEY][$requestedName]) && is_a($config[static::KEY][$requestedName][static::KEY_CLASS], static::EXTENDER_CLASS, true);
    }
}
