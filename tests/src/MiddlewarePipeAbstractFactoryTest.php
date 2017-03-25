<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 17:50
 */

namespace rollun\test\actionrender;

use Interop\Container\ContainerInterface;
use rollun\actionrender\Factory\MiddlewarePipeAbstractFactory;

class MiddlewarePipeAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{

    /** @var  MiddlewarePipeAbstractFactory */
    protected $object;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $container;

    public function setUp()
    {
        $this->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $this->object = new MiddlewarePipeAbstractFactory();
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testServiceNotFoundException()
    {
        $config = [
            MiddlewarePipeAbstractFactory::KEY => [
                'htmlReturner' => [
                    MiddlewarePipeAbstractFactory::KEY_MIDDLEWARES => [
                        'HtmlParamResolver',
                        'HtmlRendererAction'
                    ]
                ]
            ],
        ];
        $this->container->method('get')->will($this->returnValue($config));
        $this->container->method('has')->will($this->returnValue(false));
        $this->object->__invoke($this->container, 'htmlReturner');
    }
}
