<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\Associates\AssociatesRepository;
use App\Repositories\ContactPerson\ContactPersonRepository;
use App\Repositories\DialNumbers\DialNumberRepository;
use App\Repositories\Examination\ExaminationReport;
use App\Repositories\Patient\CreatePatientProfile;
use App\Repositories\Patient\PatientRepository;
use App\Repositories\Patient\UpdatePatientProfile;
use App\Repositories\Profession\ProfessionRepository;
use App\Repositories\Recommendation\RecommendationTypeRepository;
use App\Repositories\Recommendation\WebRecommendationRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class PatientController
 * @package App\Controllers
 */
class PatientController
{

    /** @var Container */
    protected $container;

    /**
     * PatientController constructor.
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
        $patientRepository = new PatientRepository($this->container);
        return $this->container->view->render($response, 'patient/patients-register.html.twig', [
            'title'    => 'Registar pacijenata',
            'patients' => $patientRepository->loadActiveRegister()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {
        $createPatientProfile = new CreatePatientProfile($this->container);
        $dialNumberRepository = new DialNumberRepository($this->container);
        $contactPersonRepository = new ContactPersonRepository($this->container);
        $professionRepository = new ProfessionRepository($this->container);
        $recommendationTypeRepository = new RecommendationTypeRepository($this->container);
        $associatesRepository = new AssociatesRepository($this->container);
        $webRecommendationRepository = new WebRecommendationRepository($this->container);
        $patientRepository = new PatientRepository($this->container);

        if ($request->isPost())
            $createPatientProfile->run($request);

        return $this->container->view->render($response, 'patient/open-cardboard.html.twig', [
            'title'               => 'Otvaranje kartona pacijenta',
            'dialNumbers'         => $dialNumberRepository->loadActive(),
            'contactPersons'      => $contactPersonRepository->loadActive(),
            'professions'         => $professionRepository->loadActive(),
            'recommendationTypes' => $recommendationTypeRepository->loadActive(),
            'associates'          => $associatesRepository->loadActive(),
            'webRecommendations'  => $webRecommendationRepository->loadActive(),
            'patients'            => $patientRepository->loadActiveRegister()
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
        $id = intval($args['id']);
        $patientRepository = new PatientRepository($this->container);
        $patientData = $patientRepository->loadById($id);
        $updatePatientProfile = new UpdatePatientProfile($id, $this->container);
        $dialNumberRepository = new DialNumberRepository($this->container);
        $contactPersonRepository = new ContactPersonRepository($this->container);
        $professionRepository = new ProfessionRepository($this->container);
        $recommendationTypeRepository = new RecommendationTypeRepository($this->container);
        $associatesRepository = new AssociatesRepository($this->container);
        $webRecommendationRepository = new WebRecommendationRepository($this->container);
        $examinationReport = new ExaminationReport($this->container);

        if (empty($patientData))
            return $response->withRedirect($this->container->router->pathFor('error.404'));

        if ($request->isPost()) {
            $updatePatientProfile->run($request);
            $patientData = $patientRepository->loadById($id);
        }

        return $this->container->view->render($response, 'patient/edit-cardboard.html.twig', [
            'title'               => 'Karton pacijenta',
            'dialNumbers'         => $dialNumberRepository->loadActive(),
            'contactPersons'      => $contactPersonRepository->loadActive(),
            'professions'         => $professionRepository->loadActive(),
            'recommendationTypes' => $recommendationTypeRepository->loadActive(),
            'associates'          => $associatesRepository->loadActive(),
            'webRecommendations'  => $webRecommendationRepository->loadActive(),
            'patients'            => $patientRepository->loadActiveRegister(),
            'cardboard'           => $patientData->toArray(),
            'examinations'        => $examinationReport->loadCardboardById($id)
        ]);
    }

}