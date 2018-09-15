<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\Profession\CreateProfessionRepository;
use App\Repositories\Profession\ProfessionRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ProfessionController
 * @package App\Controllers
 */
class ProfessionController
{

    /** @var Container */
    protected $container;

    /**
     * ProfessionController constructor.
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
        $createProfessionRepository = new CreateProfessionRepository($this->container);

        if ($request->isPost())
            $createProfessionRepository->run($request);

        return $this->container->view->render($response, 'profession/profession-modal.html.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function select(Request $request, Response $response): Response
    {
        $professionRepository = new ProfessionRepository($this->container);

        return $this->container->view->render($response, 'profession/profession-select.html.twig', [
            'professions' => $professionRepository->loadActive()
        ]);
    }

}