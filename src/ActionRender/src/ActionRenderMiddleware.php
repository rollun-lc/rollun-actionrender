<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 11:58
 */

namespace rollun\actionrender;

use Zend\Diactoros\Response\EmptyResponse;
use Zend\Stratigility\MiddlewareInterface;
use Zend\Stratigility\MiddlewarePipe;

class ActionRenderMiddleware extends MiddlewarePipe
{
    /**
     * MainPipe constructor.
     * @param MiddlewareInterface $action
     * @param MiddlewareInterface $renderer
     * @param MiddlewareInterface $returner
     * @throws RuntimeException
     * @internal param $middlewares
     */
    public function __construct($action, $renderer, $returner)
    {
        $this->setResponsePrototype(new EmptyResponse());
        parent::__construct();
        $this->pipe($action);
        $this->pipe($renderer);
        $this->pipe($returner);
    }
}