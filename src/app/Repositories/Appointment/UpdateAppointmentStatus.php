<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use App\Models\TblAppointments;
use Illuminate\Database\Connection;
use Slim\Container;

/**
 * Class UpdateAppointmentStatus
 * @package App\Repositories\Appointment
 */
class UpdateAppointmentStatus
{

    /** @var int */
    protected $id;
    /** @var int */
    protected $statusId;
    /** @var Container */
    protected $container;

    /**
     * UpdateAppointmentStatus constructor.
     * @param int $id
     * @param int $statusId
     * @param Container $container
     */
    public function __construct(int $id, int $statusId, Container $container)
    {
        $this->id = $id;
        $this->statusId = $statusId;
        $this->container = $container;
    }

    /**
     * @param Connection $connection
     */
    public function run(Connection &$connection): void
    {
        $tblAppointments = new TblAppointments;
        $dataSet = [
            'examination_status_id' => $this->statusId,
            'updated_by'            => $_SESSION['id']
        ];
        $connection->table($tblAppointments->getTable())->where('id', $this->id)->update($dataSet);
    }

}