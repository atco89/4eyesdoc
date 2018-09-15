<?php
declare(strict_types=1);

namespace App\Repositories\Patient;

use App\Repositories\Patient\Create\CreateRecommendation;
use App\Repositories\Patient\Update\UpdatePatient;
use App\Repositories\Patient\Validation\PatientValidation;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Flash\Messages;
use Slim\Http\Request;

/**
 * Class UpdatePatientProfile
 * @package App\Repositories\Patient
 */
class UpdatePatientProfile
{

    /** @var int */
    public $id;
    /** @var Messages */
    protected $flash;
    /** @var Container */
    protected $container;

    /**
     * UpdatePatientProfile constructor.
     * @param int $id
     * @param Container $container
     */
    public function __construct(int $id, Container $container)
    {
        $this->id = $id;
        $this->container = $container;
        $this->flash = $this->container->flash;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function run(Request $request): bool
    {
        $validation = new PatientValidation($this->container);
        if ($validation->createPatientValidation($request)->failed())
            return false;

        $patientRepository = new PatientRepository($this->container);
        if (!$patientRepository->uniqueConstraint($this->id, $request)) {
            $this->flash->addMessageNow('error', 'Pacijent ima otvoren karton. Pretražite kartoteku po broju telefona pacijenta.');
            return false;
        }

        $connection = DB::connection();
        $updatePatient = new UpdatePatient($this->id, $this->container);
        $createRecommendation = new CreateRecommendation($this->id, $this->container);
        try {
            $connection->beginTransaction();

            $updatePatient->run($connection, $request);
            $createRecommendation->run($connection, $request);

            $connection->commit();
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $connection->rollBack();
            $this->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        $this->flash->addMessageNow('success', 'Operacija izvršena. Kartona pacijenta je izmenjen.');
        return true;
    }

}