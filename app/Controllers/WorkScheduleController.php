<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\User\CreateWorkSchedule;
use App\Repositories\User\DeleteWorkSchedule;
use App\Repositories\User\UserRepository;
use App\Repositories\User\WorkScheduleRepository;
use DateTime;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class WorkScheduleController
 * @package App\Controllers
 */
class WorkScheduleController
{

    /** @var Container */
    protected $container;

    /**
     * WorkScheduleController constructor.
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
        $createWorkSchedule = new CreateWorkSchedule($this->container);
        $workSchedulesRepository = new WorkScheduleRepository($this->container);

        if ($request->isPost())
            $createWorkSchedule->run($request);

        return $this->container->view->render($response, 'admin/schedule.html.twig', [
            'title'     => 'Raspored rada lekara',
            'doctors'   => $userRepository->loadAssignedDoctors(),
            'schedules' => $workSchedulesRepository->loadScheduleReport()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function search(Request $request, Response $response): Response
    {
        $schedules = null;
        $userRepository = new UserRepository($this->container);
        $workScheduleRepository = new WorkScheduleRepository($this->container);

        $doctorId = (integer)$request->getParam('reportDoctor');
        $startDateTime = new DateTime($request->getParam('periodStartDate'));
        $endDateTime = new DateTime($request->getParam('periodEndDate'));

        if ($request->isPost())
            $schedules = $workScheduleRepository->findByUserAndPeriod($doctorId, $startDateTime, $endDateTime);

        return $this->container->view->render($response, 'admin/schedule.html.twig', [
            'title'     => 'Raspored rada lekara',
            'doctors'   => $userRepository->loadAssignedDoctors(),
            'schedules' => $schedules
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function availableDoctors(Request $request, Response $response, array $args): Response
    {
        $workScheduleRepository = new WorkScheduleRepository($this->container);
        $examinationId = intval($request->getParam('examination'));
        $startDateTime = new DateTime($request->getParam('startDateTime'));

        return $this->container->view->render($response, 'appointment/appointment-available-doctors.html.twig', [
            'doctors' => $workScheduleRepository->loadAvailableDoctors($examinationId, $startDateTime)
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteScheduleConfirmation(Request $request, Response $response, array $args): Response
    {
        return $this->container->view->render($response, 'admin/delete-schedule-confirmation.html.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $deleteWorkSchedule = new DeleteWorkSchedule($this->container);
        $workScheduleRepository = new WorkScheduleRepository($this->container);
        $scheduleExists = $workScheduleRepository->findById($id);

        if ($scheduleExists === null)
            return $response->withRedirect($this->container->router->pathFor("error.404"));

        $deleteWorkSchedule->run($id);
        return $response->withRedirect($this->container->router->pathFor('schedule.index'));
    }

}