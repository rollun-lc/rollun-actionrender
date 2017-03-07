<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.03.17
 * Time: 17:44
 */

namespace rollun\actionrender\Example;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\MiddlewareInterface;

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
        $responseData = $request->getAttribute('responseData', []);
        $responseData[] = $this->name;

        $response = new JsonResponse($responseData);
        $request = $request->withAttribute('responseData', $responseData)
            ->withAttribute(Response::class, $response)
        ->withAttribute('temp', "Temp[".$this->name . "]");

        if (isset($out)) {
            return $out($request, $response);
        }

        return $response;
    }
}
