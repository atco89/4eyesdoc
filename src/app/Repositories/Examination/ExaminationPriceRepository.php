<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Models\TblExaminationPriceModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class ExaminationPriceRepository
 * @package App\Repositories\Examination
 */
class ExaminationPriceRepository
{

    /** @var Container */
    protected $container;

    /**
     * ExaminationPriceRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function loadActiveExaminations(): array
    {
        return $this->loadModel(['medicalExamination'])->get()
            ->where('active', '=', true)
            ->where('medicalExamination.active', '=', true)
            ->toArray();
    }

    /**
     * @param array $relationships
     * @return Builder
     */
    protected function loadModel(array $relationships = []): Builder
    {
        return TblExaminationPriceModel::with($relationships);
    }

}