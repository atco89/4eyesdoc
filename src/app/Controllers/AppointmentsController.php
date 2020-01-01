<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\Appointment\AppointmentSearch;
use App\Repositories\Appointment\AppointmentsRepository;
use App\Repositories\Appointment\CreateAppointmentRepository;
use App\Repositories\Appointment\DeleteAppointmentRepository;
use App\Repositories\Appointment\UpdateAppointmentRepository;
use App\Repositories\Appointment\UpdateOnEventRepository;
use App\Repositories\Examination\ExaminationPriceRepository;
use App\Repositories\Examination\ExaminationStatusRepository;
use App\Repositories\Examination\ExaminationTemplateRepository;
use App\Repositories\Patient\PatientRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\WorkScheduleRepository;
use DateTime;
use Exception;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AppointmentsController
 * @package App\Controllers
 */
class AppointmentsController
{

    /** @var Container */
    protected $container;

    /**
     * AppointmentsController constructor.
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
        $examStatusRepository = new ExaminationStatusRepository($this->container);

        return $this->container->view->render($response, 'appointment/appointment.html.twig', [
            'title'             => 'Zakazivanje pregleda',
            'showPageTitle'     => false,
            'examinationStatus' => $examStatusRepository->loadActive()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function register(Request $request, Response $response): Response
    {
        $examinationEditPeriod = $this->container->settings['examinationEditPeriod'];
        $userRepository = new UserRepository($this->container);
        $examinationTemplateRepository = new ExaminationTemplateRepository($this->container);
        $appointmentsRepository = new AppointmentsRepository($this->container);

        return $this->container->view->render($response, 'appointment/appointment-register.html.twig', [
            'title'           => 'Registar pregleda',
            'allowEditPeriod' => $examinationEditPeriod,
            'doctors'         => $userRepository->loadAssignedDoctors(),
            'templates'       => $examinationTemplateRepository->loadActiveTemplates(),
            'appointments'    => $appointmentsRepository->loadAppointments($examinationEditPeriod)
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function search(Request $request, Response $response): Response
    {
        $appointments = [];
        $examinationEditPeriod = $this->container->settings['examinationEditPeriod'];
        $userRepository = new UserRepository($this->container);
        $examinationTemplateRepository = new ExaminationTemplateRepository($this->container);
        $appointmentsRepository = new AppointmentsRepository($this->container);
        $appointmentSearch = new AppointmentSearch($this->container);
        $foundedReports = $appointmentSearch->run($request);

        foreach ($foundedReports as $report) {
            $appointments[] = $report->id;
        }

        $_SESSION['appointments'] = $appointmentsRepository->searchAppointments($appointments, $examinationEditPeriod);

        /** @noinspection PhpUndefinedFieldInspection */
        return $this->container->view->render($response, 'appointment/appointment-register.html.twig', [
            'title'           => 'Registar pregleda',
            'allowEditPeriod' => $examinationEditPeriod,
            'doctors'         => $userRepository->loadAssignedDoctors(),
            'templates'       => $examinationTemplateRepository->loadActiveTemplates(),
            'appointments'    => $_SESSION['appointments']
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     */
    public function schedule(Request $request, Response $response): Response
    {
        $doctors = [];
        $createAppointmentRepository = new CreateAppointmentRepository($this->container);
        $patientRepository = new PatientRepository($this->container);
        $examinationPriceRepository = new ExaminationPriceRepository($this->container);
        $workScheduleRepository = new WorkScheduleRepository($this->container);

        if ($request->isPost()) {
            $createAppointmentRepository->run($request);
            $examinationID = intval($request->getParam('examination'));
            $startDateTime = new DateTime($request->getParam('startDateTime'));
            $doctors = $workScheduleRepository->loadAvailableDoctors($examinationID, $startDateTime);
        }

        return $this->container->view->render($response, 'appointment/appointment-schedule.html.twig', [
            'patients'     => $patientRepository->loadActiveRegister(),
            'examinations' => $examinationPriceRepository->loadActiveExaminations(),
            'doctors'      => $doctors
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function updateSchedule(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $updateAppointmentRepository = new UpdateAppointmentRepository($id, $this->container);
        $appointmentsRepository = new AppointmentsRepository($this->container);
        $patientRepository = new PatientRepository($this->container);
        $examinationPriceRepository = new ExaminationPriceRepository($this->container);
        $workScheduleRepository = new WorkScheduleRepository($this->container);
        $examinationStatusRepository = new ExaminationStatusRepository($this->container);

        if ($request->isPost())
            $updateAppointmentRepository->run($request);

        $appointmentData = $appointmentsRepository->loadById($id)->toArray();
        $doctors = $workScheduleRepository->loadAvailableDoctors($appointmentData['medical_examination_id'], new DateTime($appointmentData['start_date_time']));

        return $this->container->view->render($response, 'appointment/appointment-schedule-edit.html.twig', [
            'patients'            => $patientRepository->loadActiveRegister(),
            'examinations'        => $examinationPriceRepository->loadActiveExaminations(),
            'doctors'             => $doctors,
            'examinationStatuses' => $examinationStatusRepository->loadActive(),
            'appointmentData'     => $appointmentData
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function updateOnEvent(Request $request, Response $response): Response
    {
        $id = intval($request->getParam('id'));
        $updateOnEventRepository = new UpdateOnEventRepository($id, $this->container);

        if ($request->isPost())
            $updateOnEventRepository->run($request);

        return $response;
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
        $deleteAppointmentRepository = new DeleteAppointmentRepository($this->container);
        $deleteAppointmentRepository->run($id);
        return $response->withRedirect($this->container->router->pathFor('appointment.index'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function all(Request $request, Response $response): Response
    {
        $appointmentsRepository = new AppointmentsRepository($this->container);
        $events = $appointmentsRepository->fullCalendarAppointments();
        return $response->withJson(array_values($events));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function expired(Request $request, Response $response): Response
    {
        return $this->container->view->render($response, 'appointment/appointment-expired.html.twig');
    }

}