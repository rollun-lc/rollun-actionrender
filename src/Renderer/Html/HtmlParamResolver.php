<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 18:11
 */

namespace rollun\actionrender\Renderer\Html;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Router\RouteResult;

class HtmlParamResolver implements MiddlewareInterface
{
    const KEY_ATTRIBUTE_TEMPLATE_NAME = 'templateName';

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param Request $request
     * @param DelegateInterface $delegate
     *
     * @return Response
     */
    public function process(Request $request, DelegateInterface $delegate)
    {
        $routeResult = $request->getAttribute(RouteResult::class);

        if($request->getAttribute(static::KEY_ATTRIBUTE_TEMPLATE_NAME) === null){
            $routeName = 'app::';
            $routeName .= isset($routeResult) ? $routeResult->getMatchedRouteName() : "default-page";
            $request = $request->withAttribute(static::KEY_ATTRIBUTE_TEMPLATE_NAME, $routeName);
        };

        $response = $delegate->process($request);
        return $response;
    }
}
