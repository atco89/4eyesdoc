<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\TblUserWorkSchedule;
use App\Validation\Validator;
use DateTime;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Respect\Validation\Validator as v;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateWorkSchedule
 * @package App\Repositories\User
 */
class CreateWorkSchedule
{

    /** @var Container */
    protected $container;

    /**
     * CreateWorkSchedule constructor.
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
        /** @var Validator $validator */
        $validator = $this->container->validator->validate($request, [
            'doctor'       => v::notEmpty()->addRule(v::intVal()->addRule(v::positive()))->setName('Lekar'),
            'scheduleDate' => v::notEmpty()->addRule(v::date('d.m.Y'))->setName('Datum'),
            'startTime'    => v::notEmpty()->addRule(v::date('H:i'))->setName('Početak'),
            'endTime'      => v::notEmpty()->addRule(v::date('H:i'))->setName('Kraj'),
        ]);

        if ($validator->failed())
            return false;

        $userScheduleModel = new TblUserWorkSchedule;
        $workScheduleRepository = new WorkScheduleRepository($this->container);

        $doctorId = (integer)$request->getParam('doctor');
        $scheduleDate = new DateTime($request->getParam('scheduleDate'));
        $startTime = $request->getParam('startTime');
        $endTime = $request->getParam('endTime');

        $startDateTime = new DateTime($scheduleDate->format('Y-m-d') . ' ' . $startTime);
        $endDateTime = new DateTime($scheduleDate->format('Y-m-d') . ' ' . $endTime);

        $isOverlapping = $workScheduleRepository->overlapping($doctorId, $scheduleDate, $startDateTime, $endDateTime);

        // ========== Some of period overlap. Cancel storing. ==========
        if ($isOverlapping) {
            $this->container->flash->addMessageNow('error', 'Lekar je zauzet u izabranom periodu.');
            return false;
        }

        // ========== Create new schedule ===========
        $connection = DB::connection();
        try {
            $dataSet = [
                'user_id'         => $doctorId,
                'start_date_time' => $startDateTime->format('Y-m-d H:i:s'),
                'end_date_time'   => $endDateTime->format('Y-m-d H:i:s'),
                'created_by'      => $_SESSION['id'],
                'updated_by'      => $_SESSION['id']
            ];
            $connection->table($userScheduleModel->getTable())->insert($dataSet);
        } catch (PDOException|Exception $exception) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $exception->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda.');
            return false;
        }
        $this->container->flash->addMessageNow('success', 'Operacija izvršena.');
        return true;
    }

}