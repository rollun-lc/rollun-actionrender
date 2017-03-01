<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 24.02.17
 * Time: 2:07 PM
 */

namespace rollun\test\actionrender;


use Interop\Container\ContainerInterface;
use rollun\actionrender\Factory\LazyLoadSwitchAbstractFactory;
use Zend\Stratigility\MiddlewareInterface;
use Zend\Stratigility\MiddlewarePipe;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TestMiddleware implements MiddlewareInterface
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {

    }
}


class LazyLoadSwitchAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  LazyLoadSwitchAbstractFactory */
    protected $object;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $container;

    public function setUp()
    {
        $this->container = $this->getMock(ContainerInterface::class);
        $this->object = new LazyLoadSwitchAbstractFactory();
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testLazyLoadSwitchAbstractFactory()
    {
        $config = [
            LazyLoadSwitchAbstractFactory::LAZY_LOAD_SWITCH => [
                'testSwitch' => [
                    LazyLoadSwitchAbstractFactory::KEY_ATTRIBUTE_NAME => "testArg",
                    LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                        'test1' => 'middlewareTest1',
                        'test2' => 'middlewareTest2',
                        'test3' => 'middlewareTest3',
                    ]
                ]
            ]
        ];
        $middlewares = [
            'middlewareTest1' => new TestMiddleware('middlewareTest1'),
            'middlewareTest2' => new TestMiddleware('middlewareTest2'),
            'middlewareTest3' => new TestMiddleware('middlewareTest3'),
        ];

        $containerConfig = [
            ['config', $config],
            ['middlewareTest1', $middlewares['middlewareTest1']],
            ['middlewareTest2', $middlewares['middlewareTest2']],
            ['middlewareTest3', $middlewares['middlewareTest3']],
        ];

        $this->container->method('get')->will($this->returnValueMap($containerConfig));
        $this->container->method('has')->will($this->returnValueMap($containerConfig));
        /** @var MiddlewarePipe $switchMiddleware */
        $switchMiddleware = $this->object->__invoke($this->container, 'testSwitch');

        $request = $this->getMock(Request::class);
        $response =$this->getMock(Response::class);

        $request->method('getAttribute')->will($this->returnValueMap([
            ['testArg', null, ['test1', 'test3']]
        ]));

        $expectedPipe = new MiddlewarePipe();
        $expectedPipe->pipe($middlewares['middlewareTest1']);
        $expectedPipe->pipe($middlewares['middlewareTest3']);

        $actualPipe = $switchMiddleware->__invoke($request, $response);
        $this->assertEquals($actualPipe, $expectedPipe);
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testServiceNotCreatedExceptionInvalidAttribute()
    {
        $config = [
            LazyLoadSwitchAbstractFactory::LAZY_LOAD_SWITCH => [
                'testSwitch' => [
                    LazyLoadSwitchAbstractFactory::KEY_ATTRIBUTE_NAME => "testArg",
                    LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                        'test1' => 'middlewareTest1',
                        'test2' => 'middlewareTest2',
                        'test3' => 'middlewareTest3',
                    ]
                ]
            ]
        ];

        $containerConfig = [
            ['config', $config],
        ];

        $this->container->method('get')->will($this->returnValueMap($containerConfig));
        $this->container->method('has')->will($this->returnValueMap($containerConfig));
        /** @var MiddlewarePipe $switchMiddleware */
        $switchMiddleware = $this->object->__invoke($this->container, 'testSwitch');

        $request = $this->getMock(Request::class);
        $response =$this->getMock(Response::class);

        $request->method('getAttribute')->will($this->returnValueMap([
            ['testArg', 'test3']
        ]));

        $switchMiddleware->__invoke($request, $response);
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testServiceNotCreatedExceptionNotSelected()
    {
        $config = [
            LazyLoadSwitchAbstractFactory::LAZY_LOAD_SWITCH => [
                'testSwitch' => [
                    LazyLoadSwitchAbstractFactory::KEY_ATTRIBUTE_NAME => "testArg",
                    LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                        'test1' => 'middlewareTest1',
                        'test2' => 'middlewareTest2',
                        'test3' => 'middlewareTest3',
                    ]
                ]
            ]
        ];
        $middlewares = [
            'middlewareTest1' => new TestMiddleware('middlewareTest1'),
            'middlewareTest2' => new TestMiddleware('middlewareTest2'),
            'middlewareTest3' => new TestMiddleware('middlewareTest3'),
        ];

        $containerConfig = [
            ['config', $config],
            ['middlewareTest1', $middlewares['middlewareTest1']],
            ['middlewareTest2', $middlewares['middlewareTest2']],
            ['middlewareTest3', $middlewares['middlewareTest3']],
        ];

        $this->container->method('get')->will($this->returnValueMap($containerConfig));
        $this->container->method('has')->will($this->returnValueMap($containerConfig));
        /** @var MiddlewarePipe $switchMiddleware */
        $switchMiddleware = $this->object->__invoke($this->container, 'testSwitch');

        $request = $this->getMock(Request::class);
        $response =$this->getMock(Response::class);

        $request->method('getAttribute')->will($this->returnValueMap([
            ['testArg', null, ['test1', 'test3']]
        ]));

        $switchMiddleware->__invoke($request, $response);
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testServiceNotFoundExceptionNotFoundMiddleware()
    {
        $config = [
            LazyLoadSwitchAbstractFactory::LAZY_LOAD_SWITCH => [
                'testSwitch' => [
                    LazyLoadSwitchAbstractFactory::KEY_ATTRIBUTE_NAME => "testArg",
                    LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                        'test1' => 'middlewareTest1',
                        'test2' => 'middlewareTest2',
                        'test3' => 'middlewareTest3',
                    ]
                ]
            ]
        ];
        $middlewares = [
            'middlewareTest1' => new TestMiddleware('middlewareTest1'),
            'middlewareTest2' => new TestMiddleware('middlewareTest2'),
            'middlewareTest3' => new TestMiddleware('middlewareTest3'),
        ];

        $containerConfig = [
            ['config', $config],
            ['middlewareTest1', $middlewares['middlewareTest1']],
            ['middlewareTest2', $middlewares['middlewareTest2']],
        ];

        $this->container->method('get')->will($this->returnValueMap($containerConfig));
        $this->container->method('has')->will($this->returnValueMap($containerConfig));
        /** @var MiddlewarePipe $switchMiddleware */
        $switchMiddleware = $this->object->__invoke($this->container, 'testSwitch');

        $request = $this->getMock(Request::class);
        $response =$this->getMock(Response::class);

        $request->method('getAttribute')->will($this->returnValueMap([
            ['testArg', null, ['test1', 'test3']]
        ]));


        $switchMiddleware->__invoke($request, $response);
    }

}
