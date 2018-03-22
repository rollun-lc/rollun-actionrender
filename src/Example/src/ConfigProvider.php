<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.04.17
 * Time: 16:59
 */

namespace rollun\example\actionrender;


/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'templates' => $this->getTemplates()
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates()
    {
        return [
            'paths' => [
                'ar-app'    => [__DIR__ . '/../templates/app'],
                'ar-error'  => [__DIR__ . '/../templates/error'],
                'ar-layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
