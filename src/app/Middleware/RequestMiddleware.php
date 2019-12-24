<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Repositories\Examination\ExaminationParams;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class RequestMiddleware
 * @package App\Middleware
 */
class RequestMiddleware extends Middleware
{

    /**
     * RequestMiddleware constructor.
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
        $this->container->view->getEnvironment()->addGlobal('request', ExaminationParams::map($request));
        return $next($request, $response);
    }

}