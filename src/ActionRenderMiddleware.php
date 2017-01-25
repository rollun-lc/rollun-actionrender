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
     * @param callable $action (MiddlewareInterface|callable)
     * @param callable $renderer (MiddlewareInterface|callable)
     * @param callable $returner
     * @internal param $middlewares
     */
    public function __construct(callable $action, callable $renderer, callable $returner)
    {
        parent::__construct();
        $this->pipe($action);
        $this->pipe($renderer);
        $this->pipe($returner);
    }
}
