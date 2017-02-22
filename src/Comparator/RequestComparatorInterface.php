<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.02.17
 * Time: 16:16
 */

namespace rollun\actionrender\Comparator;
use Psr\Http\Message\ServerRequestInterface as Request;

interface RequestComparatorInterface
{
    /**
     * Analazi request. if pattern and request satisfies demands return true, else return false.
     * @param Request $request
     * @param $pattern
     * @return boolean
     */
    public function __invoke(Request $request, $pattern);

}
