<?php
declare(strict_types=1);

namespace App\Repositories\Group;

use App\Models\EnumGroupsModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class GroupRepository
 * @package App\Repositories\Group
 */
class GroupRepository
{

    /** @var Container */
    protected $container;

    /**
     * GroupRepository constructor.
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
        return EnumGroupsModel::with($relations);
    }

}