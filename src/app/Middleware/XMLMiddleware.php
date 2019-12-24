<?php
declare(strict_types=1);

namespace App\Middleware;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class XMLMiddleware
 * @package App\Middleware
 */
class XMLMiddleware extends Middleware
{

    /**
     * XMLMiddleware constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $response = $response
            ->withHeader("Content-type", "application/xml; charset=utf-8")
            ->withHeader("Content-Disposition", "attachment; filename=eksport-svih-rezultata.xml");
        return $next($request, $response);
    }
}
