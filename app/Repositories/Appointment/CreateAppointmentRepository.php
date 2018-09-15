<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateAppointmentRepository
 * @package App\Repositories\Appointment
 */
class CreateAppointmentRepository
{

    /** @var Container */
    protected $container;

    /**
     * CreateAppointmentRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function run(Request $request): bool
    {
        $validation = new AppointmentValidation($this->container);
        if ($validation->createAppointmentValidation($request)->failed())
            return false;

        $createAppointment = new CreateAppointment($this->container);
        $connection = DB::connection();
        try {
            $createAppointment->run($connection, $request, $this->container->settings['examinationDuration']);
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        $this->container->flash->addMessageNow('success', 'Operacija izvršena. Termin pregleda uspešno zakazan.');
        return true;
    }

}