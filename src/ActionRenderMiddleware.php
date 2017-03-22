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
     * @param callable $returner (MiddlewareInterface|callable)
     * @throws RuntimeException
     * @internal param $middlewares
     */
    public function __construct($action, $renderer, $returner)
    {
        if (!is_callable($action) || !is_callable($action) || !is_callable($action)) {
            throw new RuntimeException("Send object not callable.");
        }
        parent::__construct();
        $this->pipe($action);
        $this->pipe($renderer);
        $this->pipe($returner);
    }
}
