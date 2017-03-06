<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 11:20
 */

namespace rollun\actionrender\Interfaces;

use Psr\Http\Message\ServerRequestInterface as Request;

interface LazyLoadMiddlewareGetterInterface
{
    /**
     * 
     * @param Request $request
     * @return array
     */
    public function getLazyLoadMiddlewares(Request $request);
}
