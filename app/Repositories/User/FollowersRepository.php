<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\TblUserFollowersModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class FollowersRepository
 * @package App\Repositories\User
 */
class FollowersRepository
{

    /** @var Container */
    protected $container;

    /**
     * FollowersRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function findWhatUserFollows(int $id): ?array
    {
        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('follower_id', '=', $id)
            ->map(function ($row) {
                return $row->following_id;
            })->toArray();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function findFollowers(int $id): ?array
    {
        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('following_id', '=', $id)
            ->map(function ($row) {
                return $row->follower_id;
            })->toArray();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblUserFollowersModel::with($relations);
    }

}