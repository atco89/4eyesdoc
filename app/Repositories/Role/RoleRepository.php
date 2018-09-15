<?php
declare(strict_types=1);

namespace App\Repositories\Role;

use App\Models\EnumRolesModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class RoleRepository
 * @package App\Repositories\Role
 */
class RoleRepository
{

    /** @var Container */
    protected $container;

    /**
     * RoleRepository constructor.
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
        return EnumRolesModel::with($relations);
    }

}