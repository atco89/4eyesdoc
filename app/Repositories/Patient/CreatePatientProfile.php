<?php
declare(strict_types=1);

namespace App\Repositories\Patient;

use App\Repositories\Patient\Create\CreatePatient;
use App\Repositories\Patient\Create\CreateRecommendation;
use App\Repositories\Patient\Validation\PatientValidation;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Flash\Messages;
use Slim\Http\Request;

/**
 * Class CreatePatientProfile
 * @package App\Repositories\Patient
 */
class CreatePatientProfile
{

    /** @var Messages */
    protected $flash;
    /** @var Container */
    protected $container;

    /**
     * CreatePatientProfile constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->flash = $this->container->flash;
    }

    /**
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function run(Request $request): bool
    {
        $validation = new PatientValidation($this->container);
        if ($validation->createPatientValidation($request)->failed())
            return false;

        $patientRepository = new PatientRepository($this->container);
        if (!$patientRepository->uniqueConstraint(null, $request)) {
            $this->flash->addMessageNow('error', 'Pacijent ima otvoren karton. Pretražite kartoteku po broju telefona pacijenta.');
            return false;
        }

        $connection = DB::connection();
        $createPatient = new CreatePatient($this->container);
        try {
            $connection->beginTransaction();

            $createPatient->run($connection, $request);
            $createRecommendation = new CreateRecommendation($createPatient->id, $this->container);
            $createRecommendation->run($connection, $request);

            $connection->commit();
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $connection->rollBack();
            $this->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        $this->flash->addMessageNow('success', 'Operacija izvršena. Kartona pacijenta je otvoren.');
        return true;
    }

}