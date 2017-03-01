<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 18:07
 */

namespace rollun\test\actionrender;

use Interop\Container\ContainerInterface;
use rollun\actionrender\Factory\ActionRenderAbstractFactory;

class ActionRenderAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ActionRenderAbstractFactory */
    protected $object;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $container;

    public function setUp()
    {
        $this->container = $this->getMock(ContainerInterface::class);
        $this->object = new ActionRenderAbstractFactory();
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testServiceNotCreatedException()
    {
        $config = [
            ActionRenderAbstractFactory::KEY_AR => [
                'home-service' => [
                    ActionRenderAbstractFactory::KEY_AR_MIDDLEWARE => [
                        ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE => 'Action',
                        ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'Renderer'
                    ]
                ],
            ],
        ];
        $this->container->method('get')->will($this->returnValue($config));
        $this->container->method('has')->will($this->returnValue(false));
        $this->object->__invoke($this->container, 'home-service');
    }
}
