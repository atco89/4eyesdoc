<?php
declare(strict_types=1);

namespace App\Repositories\GroupRole;

use App\Models\TblGroupRoleModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class GroupRoleRepository
 * @package App\Repositories\GroupRole
 */
class GroupRoleRepository
{

    /** @var Container */
    protected $container;

    /**
     * GroupRoleRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int|null $groupId
     * @return array
     */
    public function loadRoleByGroup(?int $groupId): array
    {
        if ($groupId === null)
            return [];

        return $this->loadModel(['role'])->get()->where('active', '=', true)
            ->where('role.active', '=', true)
            ->where('group_id', '=', $groupId)
            ->map(function ($row) {
                return [
                    'id'                => $row->role->id,
                    'name'              => $row->role->name,
                    'title'             => $row->role->title,
                    'mayDoExaminations' => $row->role->may_do_examinations,
                ];
            })->toArray();
    }

    /**
     * @param int|null $groupId
     * @param int|null $roleId
     * @return int|null
     */
    public function loadGroupRoleId(?int $groupId, ?int $roleId): ?int
    {
        if ($groupId === null || $roleId === null)
            return null;

        $groupRole = $this->loadModel()->get()->where('active', '=', true)
            ->where('group_id', '=', $groupId)
            ->where('role_id', '=', $roleId)
            ->first();

        return $groupRole->id;
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblGroupRoleModel::with($relations);
    }

}