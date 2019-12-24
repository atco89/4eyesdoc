<?php
declare(strict_types=1);

namespace App\Repositories\Recommendation;

use App\Models\EnumWebRecommendationModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class WebRecommendationRepository
 * @package App\Repositories\Recommendation
 */
class WebRecommendationRepository
{

    /** @var Container */
    protected $container;

    /**
     * WebRecommendationRepository constructor.
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
        return EnumWebRecommendationModel::with($relations);
    }

}