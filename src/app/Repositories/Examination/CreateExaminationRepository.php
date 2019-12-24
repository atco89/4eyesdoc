<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Repositories\Appointment\UpdateAppointmentStatus;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateExaminationRepository
 * @package App\Repositories\Examination
 */
class CreateExaminationRepository
{

    /** @var Container */
    protected $container;

    /**
     * CreateExaminationRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $appointmentId
     * @param int $templateId
     * @param Request $request
     * @return bool
     */
    public function run(int $appointmentId, int $templateId, Request $request): bool
    {
        $validation = new ExaminationValidation($this->container);
        if ($validation->createReportValidation($request)->failed())
            return false;

        $connection = DB::connection();
        $createExaminationReport = new CreateExaminationReport($appointmentId, $templateId, $this->container);
        try {
            $connection->beginTransaction();

            $createExaminationReport->run($connection, $request);
            $createExaminationICD10 = new CreateExaminationICD10($createExaminationReport->getId(), $this->container);
            $createExaminationICD10->run($connection, $request);
            $updateAppointmentStatus = new UpdateAppointmentStatus($appointmentId, 6, $this->container);
            $updateAppointmentStatus->run($connection);

            $connection->commit();
        } catch (PDOException|Exception $e) {
            $connection->rollBack();
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        $this->container->flash->addMessageNow('success', 'Operacija izvršena, izveštaj uspešno kreiran.');
        return true;
    }

}