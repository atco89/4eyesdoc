<?php
declare(strict_types=1);

namespace App\Repositories\Associates;

use App\Models\TblAssociatesModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class AssociatesRepository
 * @package App\Repositories\Associates
 */
class AssociatesRepository
{

    /** @var Container */
    protected $container;

    /**
     * AssociatesRepository constructor.
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
        return $this->loadModel(['dialNumber'])->get()->where('active', '=', true)->toArray();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblAssociatesModel::with($relations);
    }

}