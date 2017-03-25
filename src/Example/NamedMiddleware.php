<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.03.17
 * Time: 17:44
 */

namespace rollun\actionrender\Example;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;

class NamedMiddleware implements MiddlewareInterface
{

    protected $name;

    /**
     * NamedMiddleware constructor.
     * @param $name
     */
    public function __construct($name = "test")
    {
        $this->name = $name;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {

    }

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
        $responseData = $request->getAttribute('responseData', []);
        $responseData[] = $this->name;

        $response = new JsonResponse($responseData);
        $request = $request->withAttribute('responseData', $responseData)
            ->withAttribute(Response::class, $response)
            ->withAttribute('temp', "Temp[".$this->name . "]");

        $response = $delegate->process($request);

        return $response;
    }
}
