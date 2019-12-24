<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\TblUserModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository
{

    /** @var Container */
    protected $container;

    /**
     * UserRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function loadAllUsers(): array
    {
        return $this->loadModel(['groupRole.group', 'groupRole.role', 'dialNumber'])->get()->toArray();
    }

    /**
     * @return array
     */
    public function loadActiveDoctors(): array
    {
        return $this->loadModel(['groupRole.group', 'groupRole.role'])->get()
            ->where('active', '=', true)
            ->whereIn('groupRole.group_id', [1, 6])->toArray();
    }

    /**
     * @return array
     */
    public function loadAssignedDoctors(): array
    {
        $followersRepository = new FollowersRepository($this->container);
        $following = $followersRepository->findWhatUserFollows($_SESSION['id']);
        return $this->loadModel(['groupRole.role'])->get()
            // only active users
            ->where('active', '=', true)
            // only what user follows
            ->whereIn('id', $following)
            // only with doctors role
            ->whereIn('groupRole.group_id', [1, 6])
            // return as array
            ->toArray();
    }

    /**
     * @param string $username
     * @param string $password
     * @return array|null
     */
    public function loadByUsernameAndPassword(string $username, string $password): ?array
    {
        return $this->loadModel(['groupRole.role'])->get()
            ->where('active', '=', true)
            ->where('username', '=', $username)
            ->where('password', '=', sha1($password))
            ->map(function ($row) {
                return [
                    'id'                  => $row->id,
                    'name'                => $row->name,
                    'surname'             => $row->surname,
                    'username'            => $row->username,
                    'group_id'            => $row->groupRole->group_id,
                    'role_id'             => $row->groupRole->role_id,
                    'role_title'          => $row->groupRole->role->title,
                    'may_do_examinations' => $row->groupRole->role->may_do_examinations
                ];
            })->first();
    }

    /**
     * @param int|null $id
     * @return array|null
     */
    public function findById(?int $id): ?array
    {
        if ($id === null)
            return null;

        return $this->loadModel()->get()->where('active', '=', true)
            ->where('id', '=', $id)->toArray();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function loadById(int $id): ?array
    {
        $followersRepository = new FollowersRepository($this->container);
        $userMayDoExaminations = new UserMayDoExaminations($this->container);

        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('id', '=', $id)
            ->map(function ($row) use ($id, $followersRepository, $userMayDoExaminations) {
                return [
                    'name'         => $row->name,
                    'surname'      => $row->surname,
                    'groupId'      => $row->groupRole->group_id,
                    'roleId'       => $row->groupRole->role_id,
                    'username'     => $row->username,
                    'color'        => $row->user_color,
                    'email'        => $row->email,
                    'dialNumberId' => $row->dial_number_id,
                    'phoneNumber'  => $row->phone_number,
                    'following'    => $followersRepository->findWhatUserFollows($id),
                    'followers'    => $followersRepository->findFollowers($id),
                    'examinations' => $userMayDoExaminations->findWhichExamsUserMayDo($id)
                ];
            })->first();
    }

    /**
     * @param int|null $id
     * @param Request $request
     * @return bool
     */
    public function uniqueConstraint(?int $id, Request $request): bool
    {
        $color = $request->getParam('color');
        $username = $request->getParam('username');

        $numberOfRows = $this->loadModel()->get()->filter(function ($row) use ($id, $username, $color) {
            if ($id !== null)
                return $row->id !== $id && ($row->username === $username || $row->user_color === $color);
            return $row->username === $username || $row->user_color === $color;
        })->count();

        return $numberOfRows === 0;
    }

    /**
     * @return array
     */
    public function loadExcelReport(): array
    {
        return $this->loadModel()->get()->map(function ($row) {
            return [
                'name'        => $row->name,
                'surname'     => $row->surname,
                'group'       => $row->groupRole->group->name,
                'role'        => $row->groupRole->role->name,
                'username'    => $row->username,
                'color'       => $row->user_color,
                'email'       => $row->email,
                'phoneNumber' => "({$row->dialNumber->dial_number}{$row->phone_number})",
                'updatedAt'   => $row->updated_at->format('d.m.Y H:i:s'),
                'updatedBy'   => $row->updatedBy->username,
                'active'      => $row->active ? 'Aktivan' : 'Neaktivan'
            ];
        })->toArray();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblUserModel::with($relations);
    }

}