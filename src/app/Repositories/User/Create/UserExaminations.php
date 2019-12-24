<?php
declare(strict_types=1);

namespace App\Repositories\User\Create;

use App\Models\TblUserMayDoExaminations;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UserExaminations
 * @package App\Repositories\User\Create
 */
class UserExaminations
{

    /** @var TblUserMayDoExaminations */
    protected $table;
    /** @var Container */
    protected $container;

    /**
     * UserExaminations constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->table = new TblUserMayDoExaminations;
    }

    /**
     * @param int $doctorId
     * @param Connection $connection
     * @param Request $request
     */
    public function run(int $doctorId, Connection &$connection, Request $request): void
    {
        $examinations = $request->getParam('examinations');
        if (empty($examinations))
            return;

        foreach ($examinations as $medicalExaminationId)
            $dataSet[] = [
                'doctor_id'              => $doctorId,
                'medical_examination_id' => $medicalExaminationId,
                'created_by'             => $_SESSION['id']
            ];
        $connection->table($this->table->getTable())->insert($dataSet);
    }

}