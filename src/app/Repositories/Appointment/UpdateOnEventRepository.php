<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UpdateOnEventRepository
 * @package App\Repositories\Appointment
 */
class UpdateOnEventRepository
{

    /** @var int */
    protected $id;
    /** @var Container */
    protected $container;

    /**
     * UpdateOnEventRepository constructor.
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
        if ($validation->updateOnEventValidation($request)->failed())
            return false;

        $appointmentsRepository = new AppointmentsRepository($this->container);
        $appointment = $appointmentsRepository->loadById($this->id);

        if (EditingRestrictions::statusDoesntAllowsEditing($appointment['examination_status_id']))
            return false;
        if (EditingRestrictions::termHasBeenExpired($request->getParam('startDateTime')))
            return false;

        $updateOnEvent = new UpdateOnEvent($this->id, $this->container);
        $connection = DB::connection();
        try {
            $updateOnEvent->run($connection, $request);
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            return false;
        }
        return true;
    }

}