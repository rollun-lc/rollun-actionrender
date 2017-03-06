<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 12:04
 */
namespace rollun\actionrender\Interfaces;

use Interop\Http\Middleware\MiddlewareInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use rollun\actionrender\Factory\MiddlewareExtractor;

interface LazyLoadPipeInterface extends ServerMiddlewareInterface, MiddlewareInterface
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