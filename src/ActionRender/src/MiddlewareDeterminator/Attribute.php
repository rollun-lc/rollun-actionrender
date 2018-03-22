<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 16:59
 */

namespace rollun\actionrender\MiddlewareDeterminator;

use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\actionrender\MiddlewareDeterminator\Interfaces\MiddlewareDeterminatorInterface;

class Attribute implements MiddlewareDeterminatorInterface
{
    /**
     * @var string
     */
    protected $attributeName;

    /**
     * Attribute constructor.
     * @param string $attributeName
     */
    public function __construct($attributeName)
    {
        $this->attributeName = $attributeName;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getMiddlewareServiceName(Request $request)
    {
        $serviceName = $request->getAttribute($this->attributeName);
        if(is_null($serviceName)) {
            throw new MiddlewareDeterminatorException("Middleware service name for attribute {$this->attributeName} not determinate.");
        }
        return $serviceName;
    }
}
