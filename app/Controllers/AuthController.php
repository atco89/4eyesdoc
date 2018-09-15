<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\User\UserRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthController
 * @package App\Controllers
 */
class AuthController
{

    /** @var Container */
    protected $container;

    /**
     * AuthController constructor.
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
        return $this->container->view->render($response, 'index.html.twig', [
            'title'          => 'Korisnički interfejs',
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
    public function signIn(Request $request, Response $response): Response
    {
        $userRepository = new UserRepository($this->container);
        $userData = $userRepository->loadByUsernameAndPassword(
            $request->getParam('username'),
            $request->getParam('password')
        );

        if ($userData === null)
            return $response->withRedirect($this->container->router->pathFor('login.index'));

        foreach ($userData as $columnName => $value)
            $_SESSION[$columnName] = $value;

        return $response->withRedirect($this->container->router->pathFor('appointment.index'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function signOut(Request $request, Response $response): Response
    {
        session_destroy();
        return $response->withRedirect($this->container->router->pathFor('login.index'));
    }

}