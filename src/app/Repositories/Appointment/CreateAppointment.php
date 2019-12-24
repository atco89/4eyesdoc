<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use App\Models\TblAppointments;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateAppointment
 * @package App\Repositories\Appointment
 */
class CreateAppointment
{

    /** @var int */
    protected $id;
    /** @var Container */
    protected $container;

    /**
     * CreateAppointment constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Connection $connection
     * @param Request $request
     * @param int $duration
     * @throws Exception
     */
    public function run(Connection &$connection, Request $request, int $duration): void
    {
        $tblAppointments = new TblAppointments;
        $startDateTime = new DateTime($request->getParam('startDateTime'));
        $dataSet = [
            'patient_id'             => $request->getParam('patient'),
            'medical_examination_id' => $request->getParam('examination'),
            'doctor_id'              => $request->getParam('doctor'),
            'start_date_time'        => $startDateTime->format('Y-m-d H:i:s'),
            'end_date_time'          => $startDateTime->add(new DateInterval("PT{$duration}M"))->format('Y-m-d H:i:s'),
            'examination_status_id'  => 1,
            'updated_by'             => $_SESSION['id'],
            'created_by'             => $_SESSION['id']
        ];
        $connection->table($tblAppointments->getTable())->insert($dataSet);
        $this->id = intval($connection->getPdo()->lastInsertId());
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

}