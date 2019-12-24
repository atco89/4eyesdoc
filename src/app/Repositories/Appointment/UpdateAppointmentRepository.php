<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UpdateAppointmentRepository
 * @package App\Repositories\Appointment
 */
class UpdateAppointmentRepository
{

    /** @var int */
    protected $id;
    /** @var Container */
    protected $container;

    /**
     * UpdateAppointmentRepository constructor.
     * @param int $id
     * @param Container $container
     */
    public function __construct(int $id, Container $container)
    {
        $this->id = $id;
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function run(Request $request): bool
    {
        $validation = new AppointmentValidation($this->container);
        if ($validation->updateValidation($request)->failed())
            return false;

        $appointmentsRepository = new AppointmentsRepository($this->container);
        $appointment = $appointmentsRepository->loadById($this->id);

        if (EditingRestrictions::statusDoesntAllowsEditing($appointment['examination_status_id'])) {
            $this->container->flash->addMessageNow('error', 'Preglede sa statusima `Otkazan dolazak` i `Pregled obavljen` nije moguće izmeniti.');
            return false;
        }

        if (EditingRestrictions::termHasBeenExpired($request->getParam('startDateTime'))) {
            $this->container->flash->addMessageNow('error', 'Termin pregleda je istekao.');
            return false;
        }

        try {
            $updateAppointment = new UpdateAppointment($this->id, $this->container);
            $connection = DB::connection();
            $updateAppointment->run($connection, $request);
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        $this->container->flash->addMessageNow('success', 'Operacija izvršena. Osnovni podaci vezani za pregled su izmenjeni.');
        return true;
    }

}