<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use App\Models\TblAppointments;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;

/**
 * Class DeleteAppointmentRepository
 * @package App\Repositories\Appointment
 */
class DeleteAppointmentRepository
{

    /** @var Container */
    protected $container;

    /**
     * DeleteAppointmentRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function run(int $id): bool
    {
        $tblAppointments = new TblAppointments;
        $connection = DB::connection();
        try {
            $connection->table($tblAppointments->getTable())->where('id', $id)->update([
                'active' => false
            ]);
        } catch (PDOException|Exception $exception) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $exception->getMessage());
            return false;
        }
        return true;
    }


}