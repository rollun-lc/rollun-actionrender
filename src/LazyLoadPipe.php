<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 11:14
 */

namespace rollun\actionrender;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\MiddlewareExtractor;
use rollun\actionrender\Interfaces\LazyLoadPipeInterface;
use rollun\actionrender\Interfaces\LazyLoadMiddlewareGetterInterface;
use Zend\Stratigility\MiddlewarePipe;

class LazyLoadPipe extends MiddlewarePipe implements LazyLoadPipeInterface
{

    /** @var LazyLoadMiddlewareGetterInterface */
    protected $middlewareDeterminator;

    /** @var MiddlewareExtractor  */
    protected $middlewareFactory;

    /**
     * DynamicPipe constructor.
     * @param LazyLoadMiddlewareGetterInterface $middlewareDeterminator
     * @param MiddlewareExtractor $middlewareFactory
     */
    public function __construct(LazyLoadMiddlewareGetterInterface $middlewareDeterminator, MiddlewareExtractor $middlewareFactory)
    {
        $this->setMiddlewareDeterminator($middlewareDeterminator);
        $this->setMiddlewareFactory($middlewareFactory);
        parent::__construct();
    }

    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $this->initPipe($request);
        return parent::__invoke($request, $response, $out);
    }

    public function process(Request $request, DelegateInterface $delegate)
    {
        $this->initPipe($request);
        return parent::process($request, $delegate);
    }

    /**
     * Initialize pipe by determined middleware
     * @param Request $request
     * @return void
     */
    protected function initPipe(Request $request)
    {
        $middlewaresService = $this->middlewareDeterminator->getLazyLoadMiddlewares($request);
        foreach ($middlewaresService as $middlewareService) {
            if($this->middlewareFactory->canExtract($middlewareService)) {
                $this->pipe($this->middlewareFactory->extract($middlewareService));
            } else {
                throw new RuntimeException("$middlewareService cannot created by middleware factory.");
            }
        }
    }


    /**
     * @param LazyLoadMiddlewareGetterInterface $middlewareDeterminator
     */
    public function setMiddlewareDeterminator($middlewareDeterminator)
    {
        $this->middlewareDeterminator = $middlewareDeterminator;
    }


    /**
     * @param MiddlewareExtractor $middlewareFactory
     */
    public function setMiddlewareFactory($middlewareFactory)
    {
        $this->middlewareFactory = $middlewareFactory;
    }

}
