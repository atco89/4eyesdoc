<?php
declare(strict_types=1);

namespace App\Controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ErrorPagesController
 * @package App\Controllers
 */
class ErrorPagesController
{

    /** @var Container */
    protected $container;

    /**
     * ErrorPagesController constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function page404(Request $request, Response $response): Response
    {
        return $this->container->view->render($response, 'error/error-404.html.twig', [
            'title'          => 'Greška 404',
            'showLoader'     => false,
            'showNavigation' => false,
            'showPageTitle'  => false
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function page405(Request $request, Response $response): Response
    {
        return $this->container->view->render($response, 'error/error-405.html.twig', [
            'title'          => 'Greška 405',
            'showLoader'     => false,
            'showNavigation' => false,
            'showPageTitle'  => false
        ]);
    }

}