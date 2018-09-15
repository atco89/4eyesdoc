<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\GroupRole\GroupRoleRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class RoleController
 * @package App\Controllers
 */
class RoleController
{

    /** @var Container */
    protected $container;

    /**
     * RoleController constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function roleByGroup(Request $request, Response $response, array $args): Response
    {
        $groupRoleRepository = new GroupRoleRepository($this->container);

        return $this->container->view->render($response, 'partial/roles.html.twig', [
            'roles' => $groupRoleRepository->loadRoleByGroup(intval($args['id']))
        ]);
    }

}