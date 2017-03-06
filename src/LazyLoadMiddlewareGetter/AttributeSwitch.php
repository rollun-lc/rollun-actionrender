<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 13:15
 */

namespace rollun\actionrender\LazyLoadMiddlewareGetter;

use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\Interfaces\LazyLoadMiddlewareGetterInterface;
use rollun\actionrender\RuntimeException;
use Zend\Stratigility\MiddlewarePipe;

class AttributeSwitch implements LazyLoadMiddlewareGetterInterface
{
    /**
     * [
     *  $key //-> pattern
     *      => $value, //-> middlewareServiceName
     * ]
     * @var array
     */
    protected $middlewares;

    protected $attributeName;

    /**
     * SwitchGetterLazyLoad constructor.
     * @param array $middlewares
     * @param $attributeName
     */
    public function __construct(array $middlewares, $attributeName = "switchArray")
    {
        $this->middlewares= $middlewares;
        $this->attributeName = $attributeName;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getLazyLoadMiddlewares(Request $request)
    {
        $middlewares = [];
        $attributeValue = $request->getAttribute($this->attributeName);
        if (is_null($attributeValue)) {
            throw new RuntimeException("Attribute '" . $this->attributeName . "' values not valid.");
        }
        foreach ($this->middlewares as $pattern => $middlewareService) {
            if (preg_match($pattern, $attributeValue)) {
                $middlewares[] = $middlewareService;
            }
        }
        return $middlewares;
    }
}
