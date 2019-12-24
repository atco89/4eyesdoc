<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use Dompdf\Dompdf;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ExaminationReportPDF
 * @package App\Repositories\Examination
 */
class ExaminationReportPDF
{

    /** @var Container */
    protected $container;

    /**
     * ExaminationReportPDF constructor.
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
    public function read(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $examinationReport = new ExaminationReport($this->container);
        $reportData = $examinationReport->loadReportById($id);

        if ($reportData === null)
            return $response->withRedirect($this->container->router->pathFor('error.404'));

        $pdfReportOutput = $this->render($reportData->toArray());
        return $response->withHeader("Content-type", "application/pdf; charset=utf-8")
            ->withHeader("Content-Disposition", "filename=izvestaj-lekara.pdf")
            ->write($pdfReportOutput);
    }

    /**
     * @param array $data
     * @return string
     */
    private function render(array $data): string
    {
        $html = $this->body($data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4');
        $dompdf->render();
        return $dompdf->output();
    }

    /**
     * @param array $reportData
     * @return null|string
     */
    private function body(array $reportData): ?string
    {
        $examinationTemplateRepository = new ExaminationTemplateRepository($this->container);
        $templateId = intval($reportData['template_id']);

        /*** @return array|null */
        $template = call_user_func(function () use ($templateId, $examinationTemplateRepository): ?array {
            $templates = $examinationTemplateRepository->loadActiveTemplates();
            foreach ($templates as $template)
                if ($template['id'] === $templateId)
                    return $template;
            return null;
        });

        return $this->container->view->fetch('pdf-report/pdf-report.html.twig', [
            'template'           => $template['template_name'],
            'headerImage'        => $this->container['settings']['pdfHeaderImage'],
            'footerImage'        => $this->container['settings']['pdfFooterImage'],
            'displayFooterImage' => $this->container['settings']['displayFooterImage'],
            'data'               => $reportData
        ]);
    }

}