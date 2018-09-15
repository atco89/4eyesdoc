<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use App\Models\TblAppointments;
use DateTime;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UpdateOnEvent
 * @package App\Repositories\Appointment
 */
class UpdateOnEvent
{

    /** @var int */
    protected $id;
    /** @var Container */
    protected $container;

    /**
     * UpdateOnEvent constructor.
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
    public function run(Connection $connection, Request $request): void
    {
        $tblAppointments = new TblAppointments;
        $dataSet = [
            'start_date_time' => (new DateTime($request->getParam('startDateTime')))->format('Y-m-d H:i:s'),
            'end_date_time'   => (new DateTime($request->getParam('endDateTime')))->format('Y-m-d H:i:s'),
            'updated_by'      => $_SESSION['id']
        ];

        $connection->table($tblAppointments->getTable())->where('id', $this->id)->update($dataSet);
    }

}