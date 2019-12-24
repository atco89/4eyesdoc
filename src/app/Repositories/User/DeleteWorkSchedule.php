<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\TblAppointments;
use App\Models\TblUserWorkSchedule;
use App\Repositories\Appointment\AppointmentsRelatedToSchedule;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;

/**
 * Class DeleteWorkSchedule
 * @package App\Repositories\User
 */
class DeleteWorkSchedule
{

    /** @var Container */
    protected $container;

    /**
     * DeleteWorkSchedule constructor.
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
        $tblUserWorkSchedule = new TblUserWorkSchedule;
        $tblAppointments = new TblAppointments;
        $appointmentTriggers = new AppointmentsRelatedToSchedule($this->container);
        $appointments = $appointmentTriggers->run($id);
        $connection = DB::connection();
        try {
            $connection->beginTransaction();

            $connection->table($tblUserWorkSchedule->getTable())->where('id', $id)->update([
                'active' => false
            ]);
            $connection->table($tblAppointments->getTable())->whereIn('id', $appointments)->update([
                'active' => false
            ]);

            $connection->commit();
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $connection->rollBack();
            return false;
        }
        return true;
    }

}