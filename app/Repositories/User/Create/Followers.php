<?php
declare(strict_types=1);

namespace App\Repositories\User\Create;

use App\Models\TblUserFollowersModel;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class Followers
 * @package App\Repositories\User\Create
 */
class Followers
{

    /** @var Container */
    protected $container;
    /** @var TblUserFollowersModel */
    protected $table;

    /**
     * Followers constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->table = new TblUserFollowersModel;
    }

    /**
     * @param int $userId
     * @param Connection $connection
     * @param Request $request
     */
    public function following(int $userId, Connection &$connection, Request $request): void
    {
        $following = $request->getParam('following');
        foreach ($following as $followUserID)
            $dataSet[] = [
                'follower_id'  => $userId,
                'following_id' => $followUserID,
                'created_by'   => $_SESSION['id']
            ];

        $following = empty($following) ? [] : $following;

        array_push($following, $userId);

        if (!empty($dataSet)) {
            $connection->table($this->table->getTable())->where('follower_id', '=', $userId)->update([
                'active' => false
            ]);
            $connection->table($this->table->getTable())->insert($dataSet);
        }
    }

    /**
     * @param int $userId
     * @param Connection $connection
     * @param Request $request
     */
    public function followers(int $userId, Connection &$connection, Request $request): void
    {
        $followers = $request->getParam('followers');
        foreach ($followers as $followerID)
            $dataSet[] = [
                'follower_id'  => $followerID,
                'following_id' => $userId,
                'created_by'   => $_SESSION['id']
            ];

        if (!empty($dataSet)) {
            $connection->table($this->table->getTable())->where('following_id', '=', $userId)->update([
                'active' => false
            ]);
            $connection->table($this->table->getTable())->insert($dataSet);
        }
    }

}