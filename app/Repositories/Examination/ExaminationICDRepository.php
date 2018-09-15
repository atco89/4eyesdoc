<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Models\TblExaminationReportsICDModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class ExaminationICDRepository
 * @package App\Repositories\Examination
 */
class ExaminationICDRepository
{

    /** @var Container */
    protected $container;

    /**
     * ExaminationICDRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function loadDeasesByID(int $id): ?array
    {
        return $this->loadModel(['eyeDiseases'])->get()
            ->where('examination_report_id', '=', $id)
            ->map(function ($row) {
                return $row->eyeDiseases->code . ' - ' . $row->eyeDiseases->name;
            })->toArray();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function loadDeasesIDsByID(int $id): ?array
    {
        return $this->loadModel(['eyeDiseases'])->get()
            ->where('examination_report_id', '=', $id)
            ->map(function ($row) {
                return $row->eyeDiseases->id;
            })->toArray();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblExaminationReportsICDModel::with($relations);
    }

}