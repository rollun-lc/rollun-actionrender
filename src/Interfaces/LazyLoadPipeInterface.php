<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 12:04
 */
namespace rollun\actionrender\Interfaces;

use Interop\Http\Middleware\ServerMiddlewareInterface;
use rollun\actionrender\MiddlewareExtractor;
use Zend\Stratigility\MiddlewareInterface;

interface LazyLoadPipeInterface extends MiddlewareInterface, ServerMiddlewareInterface
{
    /**
     * @param LazyLoadMiddlewareGetterInterface $middlewareDeterminator
     */
    public function setMiddlewareDeterminator($middlewareDeterminator);

    /**
     * @param MiddlewareExtractor $middlewareFactory
     */
    public function setMiddlewareFactory($middlewareFactory);
}