<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use DateTime;
use Illuminate\Database\Capsule\Manager as DB;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class AppointmentSearch
 * @package App\Repositories\Appointment
 */
class AppointmentSearch
{

    /** @var Container */
    protected $container;

    /**
     * AppointmentSearch constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function run(Request $request): array
    {
        $startDate = $request->getParam('startDate');
        $endDate = $request->getParam('endDate');
        $doctorID = $request->getParam('doctor');
        $diagnosis = $request->getParam('diagnosis');
        $connection = DB::connection();

        return $connection->select('CALL `searchAppointments`(?, ?, ?, ?);', [
            $startDate === '' ? null : (new DateTime($startDate))->format('Y-m-d'),
            $endDate === '' ? null : (new DateTime($endDate))->format('Y-m-d'),
            $doctorID === '' ? null : intval($doctorID),
            $diagnosis === '' ? null : $diagnosis
        ]);
    }

}