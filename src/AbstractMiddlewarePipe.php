<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 15:26
 */

namespace rollun\actionrender;

use Zend\Stratigility\MiddlewarePipe;

class AbstractMiddlewarePipe extends MiddlewarePipe
{
    /**
     * MainPipe constructor.
     * @param $middlewares
     */
    public function __construct(array $middlewares)
    {
        parent::__construct();
        foreach ($middlewares as $middleware) {
            $this->pipe($middleware);
        }
    }
}
