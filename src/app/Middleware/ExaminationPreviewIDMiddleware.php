<?php
declare(strict_types=1);

namespace App\Middleware;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ExaminationPreviewIDMiddleware
 * @package App\Middleware
 */
class ExaminationPreviewIDMiddleware extends Middleware
{

    /**
     * ExaminationPreviewIDMiddleware constructor.
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
        if (isset($_SESSION['examinationId'])) {
            $this->container->view->getEnvironment()->addGlobal('examination_id', $_SESSION['examinationId']);
            unset($_SESSION['examinationId']);
        }
        return $next($request, $response);
    }

}