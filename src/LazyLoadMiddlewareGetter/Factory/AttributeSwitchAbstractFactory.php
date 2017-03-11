<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 17:02
 */

namespace rollun\actionrender\LazyLoadMiddlewareGetter\Factory;

use Interop\Container\ContainerInterface;
use rollun\actionrender\LazyLoadMiddlewareGetter\AttributeSwitch;

class AttributeSwitchAbstractFactory extends AbstractLazyLoadMiddlewareGetterAbstractFactory
{

    const EXTENDER_CLASS = AttributeSwitch::class;

    const DEFAULT_ATTRIBUTE_NAME = 'switchAttribute';

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
        $attributeName = isset($factoryConfig[static::KEY_ATTRIBUTE_NAME]) ? $factoryConfig[static::KEY_ATTRIBUTE_NAME] : static::DEFAULT_ATTRIBUTE_NAME;
        return new $class($factoryConfig[static::KEY_MIDDLEWARE], $attributeName);
    }
}
