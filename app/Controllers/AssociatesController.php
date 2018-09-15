<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\Associates\AssociatesRepository;
use App\Repositories\Associates\CreateAssociateRepository;
use App\Repositories\DialNumbers\DialNumberRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AssociatesController
 * @package App\Controllers
 */
class AssociatesController
{

    /** @var Container */
    protected $container;

    /**
     * AssociatesController constructor.
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
    public function index(Request $request, Response $response): Response
    {
        $createAssociateRepository = new CreateAssociateRepository($this->container);
        $dialNumberRepository = new DialNumberRepository($this->container);

        if ($request->isPost())
            $createAssociateRepository->run($request);

        return $this->container->view->render($response, 'associates/associate-modal.html.twig', [
            'dialNumbers' => $dialNumberRepository->loadActive()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function select(Request $request, Response $response): Response
    {
        $associatesRepository = new AssociatesRepository($this->container);
        return $this->container->view->render($response, 'associates/associates.html.twig', [
            'associates' => $associatesRepository->loadActive()
        ]);
    }

}