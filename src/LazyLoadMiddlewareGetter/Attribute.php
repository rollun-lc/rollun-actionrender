<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 16:59
 */

namespace rollun\actionrender\LazyLoadMiddlewareGetter;

use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\Interfaces\LazyLoadMiddlewareGetterInterface;

class Attribute implements LazyLoadMiddlewareGetterInterface
{
    /**
     * @var string
     */
    protected $attributeName;


    public function __construct($attributeName)
    {
        $this->attributeName = $attributeName;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getLazyLoadMiddlewares(Request $request)
    {
        $serviceName = $request->getAttribute($this->attributeName);

        return [$serviceName];
    }
}
