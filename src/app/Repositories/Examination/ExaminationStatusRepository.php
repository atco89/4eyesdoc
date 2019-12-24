<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Models\EnumExaminationStatusModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class ExaminationStatusRepository
 * @package App\Repositories\Examination
 */
class ExaminationStatusRepository
{

    /** @var Container */
    protected $container;

    /**
     * ExaminationStatusRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function loadActive(): array
    {
        return $this->loadModel()->get()->where('active', '=', true)->toArray();
    }


    /**
     * @return array
     */
    public function loadVisible(): array
    {
        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('visible', '=', true)
            ->toArray();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return EnumExaminationStatusModel::with($relations);
    }

}