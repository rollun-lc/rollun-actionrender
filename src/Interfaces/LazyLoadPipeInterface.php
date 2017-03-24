<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.03.17
 * Time: 12:04
 */
namespace rollun\actionrender\Interfaces;

use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use rollun\actionrender\MiddlewareExtractor;

interface LazyLoadPipeInterface extends ServerMiddlewareInterface
{
    /**
     * @param LazyLoadMiddlewareGetterInterface $middlewareDeterminator
     */
    public function setLazyLoadMiddlewareGetter($middlewareDeterminator);

    /**
     * @param MiddlewareExtractor $middlewareFactory
     */
    public function setMiddlewareExtractor($middlewareFactory);
}