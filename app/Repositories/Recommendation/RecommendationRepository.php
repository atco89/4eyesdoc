<?php
declare(strict_types=1);

namespace App\Repositories\Recommendation;

use App\Models\TblRecommendationsModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class RecommendationRepository
 * @package App\Repositories\Recommendation
 */
class RecommendationRepository
{

    /** @var Container */
    protected $container;

    /**
     * RecommendationRepository constructor.
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
     * @param int $id
     * @return array|null
     */
    public function loadById(int $id): ?array
    {
        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('patient_id', '=', $id)
            ->map(function ($row) {
                return [
                    'typeId'      => $row->recommendation_type_id,
                    'associateId' => $row->associate_id
                ];
            })->first();
    }

    /**
     * @param int $id
     * @return int
     */
    public function loadNumberOfRecByUserId(int $id): int
    {
        return $this->loadModel()->get()
            ->where('recommendation_type_id', '=', 3)
            ->where('recommendation_id', '=', $id)
            ->count();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblRecommendationsModel::with($relations);
    }

}