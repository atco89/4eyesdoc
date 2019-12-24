<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use Illuminate\Database\Capsule\Manager as DB;
use Slim\Container;

/**
 * Class AppointmentsRelatedToSchedule
 * @package App\Repositories\Appointment
 */
class AppointmentsRelatedToSchedule
{

    /** @var Container */
    protected $container;

    /**
     * AppointmentsRelatedToSchedule constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @return array
     */
    public function run(int $id): array
    {
        $data = [];
        $connection = DB::connection();
        $appointments = $connection->select('CALL findAppointmentsRelatedToSchedule(?);', [$id]);
        foreach ($appointments as $appointment)
            $data[] = $appointment->id;
        return $data;
    }

}