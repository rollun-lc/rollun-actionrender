<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 18:13
 */

namespace rollun\test\actionrender;

use Interop\Container\ContainerInterface;
use rollun\actionrender\Factory\LazyLoadResponseRendererAbstractFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ResponseRendererAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  LazyLoadResponseRendererAbstractFactory */
    protected $object;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $container;

    public function setUp()
    {
        $this->container = $this->getMock(ContainerInterface::class);
        $this->object = new LazyLoadResponseRendererAbstractFactory();
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testServiceNotFoundException()
    {
        $config = [
            LazyLoadResponseRendererAbstractFactory::KEY => [
                'simpleHtmlJsonRenderer' => [
                    LazyLoadResponseRendererAbstractFactory::KEY_ACCEPT_TYPE_PATTERN => [
                        //pattern => middleware-Service-Name
                        '/application\/json/' => 'JsonRenderer',
                        '/text\/html/' => 'htmlReturner'
                    ]
                ]
            ],
        ];
        $this->container->method('get')->will($this->returnValue($config));
        $this->container->method('has')->will($this->returnValue(false));
        /** @var callable $lazyLoad */
        $lazyLoad = $this->object->__invoke($this->container, 'simpleHtmlJsonRenderer');

        $requset = $this->getMock(Request::class);
        $requset->method('getHeaderLine')->will($this->returnValue('application/json'));
        $response = $this->getMock(Response::class);

        $lazyLoad($requset, $response);
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testServiceNotCreatedException()
    {
        $config = [
            LazyLoadResponseRendererAbstractFactory::KEY => [
                'simpleHtmlJsonRenderer' => [
                    LazyLoadResponseRendererAbstractFactory::KEY_ACCEPT_TYPE_PATTERN => [
                        //pattern => middleware-Service-Name
                        '/application\/json/' => 'JsonRenderer',
                        '/text\/html/' => 'htmlReturner'
                    ]
                ]
            ],
        ];
        $this->container->method('get')->will($this->returnValue($config));
        $this->container->method('has')->will($this->returnValue(false));
        /** @var callable $lazyLoad */
        $lazyLoad = $this->object->__invoke($this->container, 'simpleHtmlJsonRenderer');

        $requset = $this->getMock(Request::class);
        $requset->method('getHeaderLine')->will($this->returnValue('application/kson'));
        $response = $this->getMock(Response::class);

        $lazyLoad($requset, $response);
    }
}
