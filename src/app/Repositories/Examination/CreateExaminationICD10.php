<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Models\TblExaminationReportsICDModel;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateExaminationICD10
 * @package App\Repositories\Examination
 */
class CreateExaminationICD10
{

    /** @var int */
    protected $id;
    /** @var Container */
    protected $container;

    /**
     * CreateExaminationICD10 constructor.
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
        $icd10 = $request->getParam('icd-10');
        if (empty($icd10))
            return;

        $table = new TblExaminationReportsICDModel;
        foreach ($icd10 as $eyeDiseasesId)
            $dataSet[] = [
                'examination_report_id' => $this->id,
                'eye_diseases_id'       => $eyeDiseasesId
            ];
        $connection->table($table->getTable())->insert($dataSet);
    }

}