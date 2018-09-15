<?php
declare(strict_types=1);

namespace App\Repositories\Recommendation;

use App\Models\EnumRecommendationTypeModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class RecommendationTypeRepository
 * @package App\Repositories\Recommendation
 */
class RecommendationTypeRepository
{

    /** @var Container */
    protected $container;

    /**
     * RecommendationTypeRepository constructor.
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
        return EnumRecommendationTypeModel::with($relations);
    }

}