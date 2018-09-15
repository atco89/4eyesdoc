<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use App\Models\TblAppointments;
use DateTime;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UpdateAppointment
 * @package App\Repositories\Appointment
 */
class UpdateAppointment
{

    /** @var int */
    protected $id;
    /** @var Container */
    protected $container;

    /**
     * UpdateAppointment constructor.
     * @param int $id
     * @param Container $container
     */
    public function __construct(int $id, Container $container)
    {
        $this->id = $id;
        $this->container = $container;
    }

    /**
     * @param Connection $connection
     * @param Request $request
     */
    public function run(Connection &$connection, Request $request): void
    {
        $tblAppointments = new TblAppointments;
        $examinationStatus = $request->getParam('examinationStatus');
        $startDateTime = new DateTime($request->getParam('startDateTime'));
        $endDateTime = new DateTime($request->getParam('endDateTime'));
        $dataSet = [
            'medical_examination_id' => $request->getParam('examination'),
            'doctor_id'              => $request->getParam('doctor'),
            'start_date_time'        => $startDateTime->format('Y-m-d H:i:s'),
            'end_date_time'          => $endDateTime->format('Y-m-d H:i:s'),
            'updated_by'             => $_SESSION['id']
        ];

        if (!empty($examinationStatus))
            $dataSet['examination_status_id'] = $examinationStatus;

        $connection->table($tblAppointments->getTable())->where('id', $this->id)->update($dataSet);
    }

}