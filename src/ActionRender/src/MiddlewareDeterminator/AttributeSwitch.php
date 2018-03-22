<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 13:15
 */

namespace rollun\actionrender\MiddlewareDeterminator;

use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface;

class AttributeSwitch implements MiddlewareDeterminatorInterface
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
    public function getMiddlewareServiceName(Request $request)
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
