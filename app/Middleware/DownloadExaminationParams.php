<?php
declare(strict_types=1);

namespace App\Middleware;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class DownloadExaminationParams
 * @package App\Middleware
 */
class DownloadExaminationParams extends Middleware
{

    /**
     * DownloadExaminationParams constructor.
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
        if (isset($_SESSION['downloadedData'])) {
            $this->container->view->getEnvironment()->addGlobal('request', $_SESSION['downloadedData']);
            unset($_SESSION['downloadedData']);
        }
        return $next($request, $response);
    }

}