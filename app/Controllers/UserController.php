<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\DialNumbers\DialNumberRepository;
use App\Repositories\Examination\ExaminationPriceRepository;
use App\Repositories\Group\GroupRepository;
use App\Repositories\GroupRole\GroupRoleRepository;
use App\Repositories\User\Create\CreateUserRepository;
use App\Repositories\User\ManagePassword;
use App\Repositories\User\ManageStatus;
use App\Repositories\User\Update\UpdateUserProfile;
use App\Repositories\User\UserRepository;
use Exception;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class UserController
 * @package App\Controllers
 */
class UserController
{

    /** @var Container */
    protected $container;

    /**
     * UserController constructor.
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
        $userRepository = new UserRepository($this->container);

        return $this->container->view->render($response, 'admin/user-register.html.twig', [
            'title' => 'Registar korisnika',
            'users' => $userRepository->loadAllUsers()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     */
    public function create(Request $request, Response $response): Response
    {
        $groupId = null;
        $createUserProfile = new CreateUserRepository($this->container);
        $groupRepository = new GroupRepository($this->container);
        $groupRoleRepository = new GroupRoleRepository($this->container);
        $dialNumberRepository = new DialNumberRepository($this->container);
        $userRepository = new UserRepository($this->container);
        $examinationPriceRepository = new ExaminationPriceRepository($this->container);

        if ($request->isPost()) {
            $groupId = intval($request->getParam('groupId'));
            $createUserProfile->run($request);
        }

        return $this->container->view->render($response, 'admin/create-user-profile.html.twig', [
            'title'        => 'Kreiranje profila korisnika',
            'groups'       => $groupRepository->loadActive(),
            'roles'        => $groupRoleRepository->loadRoleByGroup($groupId),
            'dialNumbers'  => $dialNumberRepository->loadActive(),
            'doctors'      => $userRepository->loadActiveDoctors(),
            'examinations' => $examinationPriceRepository->loadActiveExaminations()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function edit(Request $request, Response $response, array $args = []): Response
    {
        $id = intval($args['id']);
        $groupId = null;
        $userRepository = new UserRepository($this->container);
        $updateUserProfile = new UpdateUserProfile($this->container);
        $groupRepository = new GroupRepository($this->container);
        $groupRoleRepository = new GroupRoleRepository($this->container);
        $dialNumberRepository = new DialNumberRepository($this->container);
        $examinationPriceRepository = new ExaminationPriceRepository($this->container);

        $userData = $userRepository->loadById($id);
        if ($userData === null)
            return $response->withRedirect($this->container->router->pathFor('error.404'));

        $groupId = $userData['groupId'];

        if ($request->isPost()) {
            $userData = $request->getParams();
            $updateUserProfile->run($id, $request);
            $groupId = intval($request->getParam('groupId'));
        }

        return $this->container->view->render($response, 'admin/edit-user-profile.html.twig', [
            'title'        => 'Izmena profila korisnika',
            'groups'       => $groupRepository->loadActive(),
            'roles'        => $groupRoleRepository->loadRoleByGroup($groupId),
            'dialNumbers'  => $dialNumberRepository->loadActive(),
            'doctors'      => $userRepository->loadActiveDoctors(),
            'examinations' => $examinationPriceRepository->loadActiveExaminations(),
            'request'      => $userData
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function manageStatus(Request $request, Response $response, array $args = []): Response
    {
        $id = intval($args['id']);
        $status = boolval($args['status']);
        $manageStatus = new ManageStatus($this->container);
        $userRepository = new UserRepository($this->container);
        $userData = $userRepository->findById($id);

        if ($userData === null)
            return $response->withRedirect($this->container->router->pathFor("error.404"));

        $manageStatus->run($id, $status);
        return $response->withRedirect($this->container->router->pathFor("user.index"));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function managePassword(Request $request, Response $response): Response
    {
        $userRepository = new UserRepository($this->container);
        $managePassword = new ManagePassword($this->container);
        $userData = $userRepository->findById($_SESSION['id']);

        if ($userData === null)
            return $response->withRedirect($this->container->router->pathFor("error.404"));

        if ($request->isPost())
            $managePassword->run($request);

        return $this->container->view->render($response, 'admin/password.html.twig', [
            'title' => 'Administracija loznike'
        ]);
    }

}