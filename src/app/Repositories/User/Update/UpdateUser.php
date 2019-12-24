<?php
declare(strict_types=1);

namespace App\Repositories\User\Update;

use App\Models\TblUserModel;
use App\Repositories\GroupRole\GroupRoleRepository;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class UpdateUser
 * @package App\Repositories\User\Update
 */
class UpdateUser
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
     * UpdateUser constructor.
     * @param int $id
     * @param Container $container
     */
    public function __construct(int $id, Container $container)
    {
        $this->id = $id;
        $this->container = $container;
        $this->table = new TblUserModel;
    }

    /**
     * @param Connection $connection
     * @param Request $request
     */
    public function run(Connection &$connection, Request $request): void
    {
        $groupId = (integer)$request->getParam('groupId');
        $roleId = (integer)$request->getParam('roleId');
        $groupRoleId = (new GroupRoleRepository($this->container))->loadGroupRoleId($groupId, $roleId);

        $this->isDoctor = in_array($groupId, [1, 6]);

        $dataSet = [
            'name'           => $request->getParam('name'),
            'surname'        => $request->getParam('surname'),
            'group_role_id'  => $groupRoleId,
            'username'       => $request->getParam('username'),
            'user_color'     => $request->getParam('color'),
            'email'          => $request->getParam('email'),
            'dial_number_id' => $request->getParam('dialNumberId'),
            'phone_number'   => $request->getParam('phoneNumber'),
            'updated_by'     => $_SESSION['id']
        ];

        $password = $request->getParam('password');
        if (!empty($password))
            $dataSet['password'] = sha1($password);

        $connection->table($this->table->getTable())->where('id', $this->id)->update($dataSet);
    }

}