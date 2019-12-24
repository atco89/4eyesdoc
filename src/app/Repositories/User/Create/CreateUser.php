<?php
declare(strict_types=1);

namespace App\Repositories\User\Create;

use App\Models\TblUserModel;
use App\Repositories\GroupRole\GroupRoleRepository;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateUser
 * @package App\Repositories\User\Create
 */
class CreateUser
{

    /** @var int */
    public $id;
    /** @var boolean */
    public $isDoctor;
    /** @var TblUserModel */
    protected $table;
    /** @var Container */
    protected $container;

    /**
     * CreateUser constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->table = new TblUserModel;
    }

    /**
     * @param Connection $connection
     * @param Request $request
     */
    public function run(Connection &$connection, Request $request): void
    {
        $groupRoleId = (new GroupRoleRepository($this->container))->loadGroupRoleId(
            intval($request->getParam('groupId')),
            intval($request->getParam('roleId'))
        );

        $dataSet = [
            'name'           => $request->getParam('name'),
            'surname'        => $request->getParam('surname'),
            'group_role_id'  => $groupRoleId,
            'username'       => $request->getParam('username'),
            'password'       => sha1($request->getParam('password')),
            'user_color'     => $request->getParam('color'),
            'email'          => $request->getParam('email'),
            'dial_number_id' => $request->getParam('dialNumberId'),
            'phone_number'   => $request->getParam('phoneNumber'),
            'created_by'     => $_SESSION['id'],
            'updated_by'     => $_SESSION['id']
        ];
        $connection->table($this->table->getTable())->insert($dataSet);

        $this->id = intval($connection->getPdo()->lastInsertId());
        $this->isDoctor = in_array($request->getParam('groupId'), [1, 6]);
    }

}