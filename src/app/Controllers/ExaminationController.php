<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\Appointment\AppointmentsRepository;
use App\Repositories\Examination\CreateExaminationRepository;
use App\Repositories\Examination\ExaminationReport;
use App\Repositories\Examination\ExaminationTemplateRepository;
use App\Repositories\EyeDisease\EyeDiseaseRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ExaminationController
 * @package App\Controllers
 */
class ExaminationController
{

    /** @var Container */
    protected $container;

    /**
     * ExaminationController constructor.
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
    public function index(Request $request, Response $response, array $args): Response
    {
        if (!$_SESSION['may_do_examinations'])
            return $response->withRedirect($this->container->router->pathFor('error.405'));

        $id = intval($args['id']);
        $templateId = intval($args['templateId']);
        $eyeDiseaseRepository = new EyeDiseaseRepository($this->container);
        $appointmentsRepository = new AppointmentsRepository($this->container);
        $createExaminationReport = new CreateExaminationRepository($this->container);
        $examinationTemplateRepository = new ExaminationTemplateRepository($this->container);

        /*** @return array|null */
        $template = call_user_func(function () use ($templateId, $examinationTemplateRepository): ?array {
            $templates = $examinationTemplateRepository->loadActiveTemplates();
            foreach ($templates as $template)
                if ($template['id'] === $templateId)
                    return $template;
            return null;
        });

        $patientId = $appointmentsRepository->findPatientId($id);

        if (!isset($template['template_name']) || !isset($template['twig_file_name']) || empty($patientId))
            return $response->withRedirect($this->container->router->pathFor('error.404'));

        if ($request->isPost())
            $createExaminationReport->run($id, $templateId, $request);

        return $this->container->view->render($response, "examination/{$template['twig_file_name']}", [
            'title'     => $template['template_name'],
            'patientId' => $patientId->patient->id,
            'icdList'   => $eyeDiseaseRepository->loadActive()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function edit(Request $request, Response $response, array $args): Response
    {
        if (!$_SESSION['may_do_examinations'])
            return $response->withRedirect($this->container->router->pathFor('error.405'));

        $appointmentsRepository = new AppointmentsRepository($this->container);
        $createExaminationRepository = new CreateExaminationRepository($this->container);
        $examinationReport = new ExaminationReport($this->container);
        $eyeDiseaseRepository = new EyeDiseaseRepository($this->container);
        $examinationTemplateRepository = new ExaminationTemplateRepository($this->container);

        $id = intval($args['id']);
        $data = $examinationReport->loadReportById($id)->toArray();
        $templateId = intval($data['template_id']);

        /*** @return array|null */
        $template = call_user_func(function () use ($templateId, $examinationTemplateRepository): ?array {
            $templates = $examinationTemplateRepository->loadActiveTemplates();
            foreach ($templates as $template)
                if ($template['id'] === $templateId)
                    return $template;
            return null;
        });

        $patientId = $appointmentsRepository->findPatientId($id);

        if (!isset($template['template_name']) || !isset($template['twig_file_name']) || empty($patientId))
            return $response->withRedirect($this->container->router->pathFor('error.404'));

        if ($request->isPost())
            $createExaminationRepository->run($id, $templateId, $request);

        return $this->container->view->render($response, "examination/{$template['twig_file_name']}", [
            'title'     => $template['template_name'],
            'patientId' => $patientId->patient->id,
            'icdList'   => $eyeDiseaseRepository->loadActive(),
            'request'   => $data
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function downloadExamination(Request $request, Response $response): Response
    {
        $id = intval($request->getQueryParam('id'));
        $examinationReport = new ExaminationReport($this->container);
        $_SESSION['downloadedData'] = $examinationReport->loadReportById($id)->toArray();
        return $response->withRedirect(urldecode($request->getQueryParam('url')));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function saveExaminationForPreview(Request $request, Response $response): Response
    {
        $_SESSION['examinationId'] = $request->getParam('id');
        return $response;
    }

}