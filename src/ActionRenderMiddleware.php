<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 11:58
 */

namespace rollun\actionrender;

use Zend\Stratigility\MiddlewareInterface;
use Zend\Stratigility\MiddlewarePipe;

class ActionRenderMiddleware extends MiddlewarePipe
{
    /**
     * MainPipe constructor.
     * @param $action (MiddlewareInterface|callable)
     * @param $renderer (MiddlewareInterface|callable)
     * @internal param $middlewares
     */
    public function __construct(callable $action, callable $renderer)
    {
        parent::__construct();
        $this->pipe($action);
        $this->pipe($renderer);
    }
}
