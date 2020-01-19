<?php
/** @noinspection DuplicatedCode */
declare(strict_types=1);

namespace App\Controllers;

use App\Reports\Reports;
use DateTime;
use Exception;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ReportsController
 * @package App\Controllers
 */
final class ReportsController
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * ReportsController constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     *
     * @throws Exception
     */
    public function incomeReportByExaminationType(Request $request, Response $response): Response
    {
        $reports = new Reports($this->container);
        $incomeReportByExaminationType = $reports->incomeReportByExaminationType();
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->container->view->render($response, 'reports/income-report.html.twig', [
            'title'       => 'Izveštaj o prihodima po vrsti pregledu',
            'filter_uri'  => $this->container->router->pathFor('income.report.examination.filter'),
            'chart_uri'   => $this->container->router->pathFor('income.report.examination.chart', [
                'start_date' => 'null',
                'end_date'   => 'null',
            ]),
            'report_data' => $incomeReportByExaminationType,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return string
     *
     * @throws Exception
     */
    public function incomeReportByExaminationTypeReportTable(Request $request, Response $response): string
    {
        $startDate = $request->getParam('start-date');
        $endDate = $request->getParam('end-date');

        $startDateTime = in_array($startDate, ['null', null]) ? null : new DateTime($startDate);
        $endDateTime = in_array($endDate, ['null', null]) ? null : new DateTime($endDate);

        $reports = new Reports($this->container);
        $incomeReportByExaminationType = $reports->incomeReportByExaminationType($startDateTime, $endDateTime);
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->container->view->fetch('reports/partial/report-table.html.twig', [
            'chart_uri'   => $this->container->router->pathFor('income.report.examination.chart', [
                'start_date' => empty($startDate) ? 'null' : $startDate,
                'end_date'   => empty($endDate) ? 'null' : $endDate,
            ]),
            'report_data' => $incomeReportByExaminationType,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     *
     * @throws Exception
     */
    public function incomeReportByExaminationTypeChart(Request $request, Response $response, array $args): Response
    {
        $startDate = isset($args['start_date']) ? $args['start_date'] : null;
        $endDate = isset($args['end_date']) ? $args['end_date'] : null;

        $startDateTime = in_array($startDate, ['null', null]) ? null : new DateTime($startDate);
        $endDateTime = in_array($endDate, ['null', null]) ? null : new DateTime($endDate);

        $reports = new Reports($this->container);
        $incomeReportByExaminationType = $reports->incomeReportByExaminationType($startDateTime, $endDateTime);
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->container->view->render($response, 'reports/income-chart.html.twig', [
            'title'          => 'Grafički prikaz izveštaja o prihodima',
            'showLoader'     => false,
            'showNavigation' => false,
            'showPageTitle'  => false,
            'chart_data'     => $incomeReportByExaminationType
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     *
     * @throws Exception
     */
    public function incomeReportByPatient(Request $request, Response $response): Response
    {
        $reports = new Reports($this->container);
        $incomeReportByPatient = $reports->incomeReportByPatient();
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->container->view->render($response, 'reports/income-report.html.twig', [
            'title'       => 'Izveštaj o prihodima po pacijentu',
            'filter_uri'  => $this->container->router->pathFor('income.report.patient.filter'),
            'chart_uri'   => $this->container->router->pathFor('income.report.patient.chart', [
                'start_date' => 'null',
                'end_date'   => 'null',
            ]),
            'report_data' => $incomeReportByPatient,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return string
     *
     * @throws Exception
     */
    public function incomeReportByPatientReportTable(Request $request, Response $response): string
    {
        $startDate = $request->getParam('start-date');
        $endDate = $request->getParam('end-date');

        $startDateTime = in_array($startDate, ['null', null]) ? null : new DateTime($startDate);
        $endDateTime = in_array($endDate, ['null', null]) ? null : new DateTime($endDate);

        $reports = new Reports($this->container);
        $incomeReportByPatient = $reports->incomeReportByPatient($startDateTime, $endDateTime);
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->container->view->fetch('reports/partial/report-table.html.twig', [
            'chart_uri'   => $this->container->router->pathFor('income.report.patient.chart', [
                'start_date' => empty($startDate) ? 'null' : $startDate,
                'end_date'   => empty($endDate) ? 'null' : $endDate,
            ]),
            'report_data' => $incomeReportByPatient,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     *
     * @throws Exception
     */
    public function incomeReportByPatientChart(Request $request, Response $response, array $args): Response
    {
        $startDate = isset($args['start_date']) ? $args['start_date'] : null;
        $endDate = isset($args['end_date']) ? $args['end_date'] : null;

        $startDateTime = in_array($startDate, ['null', null]) ? null : new DateTime($startDate);
        $endDateTime = in_array($endDate, ['null', null]) ? null : new DateTime($endDate);

        $reports = new Reports($this->container);
        $incomeReportByPatient = $reports->incomeReportByPatient($startDateTime, $endDateTime);
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->container->view->render($response, 'reports/income-chart.html.twig', [
            'title'          => 'Grafički prikaz izveštaja o prihodima',
            'showLoader'     => false,
            'showNavigation' => false,
            'showPageTitle'  => false,
            'chart_data'     => $incomeReportByPatient
        ]);
    }
}
