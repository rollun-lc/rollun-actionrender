<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 11:14
 */

namespace rollun\actionrender;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\MiddlewareExtractor;
use rollun\actionrender\Interfaces\LazyLoadPipeInterface;
use rollun\actionrender\Interfaces\LazyLoadMiddlewareGetterInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Stratigility\MiddlewarePipe;

class LazyLoadPipe extends MiddlewarePipe implements LazyLoadPipeInterface
{
    /** @var string */
    protected $name;

    /** @var LazyLoadMiddlewareGetterInterface */
    protected $lazyLoadMiddlewareGetter;

    /** @var MiddlewareExtractor */
    protected $middlewareExtractor;

    protected $middlewaresService;

    /**
     * DynamicPipe constructor.
     * @param LazyLoadMiddlewareGetterInterface $lazyLoadMiddlewareGetter
     * @param MiddlewareExtractor $middlewareFactory
     */
    public function __construct(LazyLoadMiddlewareGetterInterface $lazyLoadMiddlewareGetter, MiddlewareExtractor $middlewareFactory, $name = null)
    {
        $this->setResponsePrototype(new EmptyResponse());
        $this->setLazyLoadMiddlewareGetter($lazyLoadMiddlewareGetter);
        $this->setMiddlewareExtractor($middlewareFactory);
        $this->name = $name;
        parent::__construct();
    }

    public function __invoke(Request $request, Response $response, $delegate)
    {
        $this->initPipe($request);
        return parent::__invoke($request, $response, $delegate);
    }


    /**
     * @param LazyLoadMiddlewareGetterInterface $lazyLoadMiddlewareGetter
     */
    public function setLazyLoadMiddlewareGetter($lazyLoadMiddlewareGetter)
    {
        $this->lazyLoadMiddlewareGetter = $lazyLoadMiddlewareGetter;
    }

    /**
     * @param MiddlewareExtractor $middlewareExtractor
     */
    public function setMiddlewareExtractor($middlewareExtractor)
    {
        $this->middlewareExtractor = $middlewareExtractor;
    }



    /**
     * Initialize pipe by determined middleware
     * @param Request $request
     * @return void
     * @throws RuntimeException
     */
    protected function initPipe(Request $request)
    {
        if(!isset($this->middlewaresService)) {
            $this->middlewaresService = $this->lazyLoadMiddlewareGetter->getLazyLoadMiddlewares($request);
        }
        foreach ($this->middlewaresService as $key => $middlewareService) {
            if (is_array($middlewareService)) {
                $this->pipe(
                    $this->middlewareExtractor->callFactory(
                        $middlewareService[LazyLoadMiddlewareGetterInterface::KEY_FACTORY_CLASS],
                        $middlewareService[LazyLoadMiddlewareGetterInterface::KEY_REQUEST_NAME],
                        $middlewareService[LazyLoadMiddlewareGetterInterface::KEY_OPTIONS]));
            } else {
                if ($this->middlewareExtractor->canExtract($middlewareService)) {
                    $this->pipe($this->middlewareExtractor->extract($middlewareService));
                } else {
                    throw new RuntimeException("$middlewareService cannot created by middleware factory.");
                }
            }
            unset($this->middlewaresService[$key]);
        }
    }

    public function process(Request $request, DelegateInterface $delegate)
    {
        $this->initPipe($request);
        return parent::process($request, $delegate);
    }

}
