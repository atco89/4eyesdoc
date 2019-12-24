<?php
declare(strict_types=1);

namespace App\Repositories\EyeDisease;

use App\Models\EnumEyeDiseasesModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class EyeDiseaseRepository
 * @package App\Repositories\EyeDisease
 */
class EyeDiseaseRepository
{

    /** @var Container */
    protected $container;

    /**
     * EyeDiseaseRepository constructor.
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
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return EnumEyeDiseasesModel::with($relations);
    }

}